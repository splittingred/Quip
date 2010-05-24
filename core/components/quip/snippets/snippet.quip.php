<?php
/**
 * Quip
 *
 * Copyright 2010 by Shaun McCormick <shaun@collabpad.com>
 *
 * This file is part of Quip, a simpel commenting component for MODx Revolution.
 *
 * Quip is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Quip is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Quip; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package quip
 */
/**
 * Quip
 *
 * A simple comments component.
 *
 * @name Quip
 * @author Shaun McCormick <shaun@collabpad.com>
 * @package quip
 */
$quip = $modx->getService('quip','Quip',$modx->getOption('quip.core_path',null,$modx->getOption('core_path').'components/quip/').'model/quip/',$scriptProperties);
if (!($quip instanceof Quip)) return '';

$quip->initialize($modx->context->get('key'));

$output = '';
$placeholders = array(
    'comment' => '',
    'error' => '',
);
$params = $modx->request->getParameters();
unset($params['reported']);

/* get default properties */
$parent = $modx->getOption('quip_parent',$_REQUEST,$modx->getOption('parent',$scriptProperties,0));
$thread = $modx->getOption('quip_thread',$_REQUEST,$modx->getOption('thread',$scriptProperties,''));
if (empty($thread)) return '';

$commentTpl = $modx->getOption('tplComment',$scriptProperties,'quipComment');
$commentOptionsTpl = $modx->getOption('tplCommentOptions',$scriptProperties,'quipCommentOptions');
$commentsTpl = $modx->getOption('tplComments',$scriptProperties,'quipComments');
$reportCommentTpl = $modx->getOption('tplReport',$scriptProperties,'quipReport');

$rowCss = $modx->getOption('rowCss',$scriptProperties,'quip-comment');
$altRowCss = $modx->getOption('altRowCss',$scriptProperties,'quip-comment-alt');
$dateFormat = $modx->getOption('dateFormat',$scriptProperties,'%b %d, %Y at %I:%M %p');
$showWebsite = $modx->getOption('showWebsite',$scriptProperties,true);
$idPrefix = $modx->getOption('idPrefix',$scriptProperties,'qcom');
$resource = $modx->getOption('resource',$scriptProperties,'');

$moderate = $modx->getOption('moderate',$scriptProperties,false);
$moderators = $modx->getOption('moderators',$scriptProperties,false);
$moderatorGroup = $modx->getOption('moderatorGroup',$scriptProperties,false);

$threaded = $modx->getOption('threaded',$scriptProperties,true);
$threadedPostMargin = $modx->getOption('threadedPostMargin',$scriptProperties,15);
$maxDepth = $modx->getOption('maxDepth',$scriptProperties,5);
$replyResourceId = !empty($scriptProperties['replyResourceId']) ? $scriptProperties['replyResourceId'] : $modx->resource->get('id');

$sortBy = $modx->getOption('sortBy',$scriptProperties,'rank');
$sortByAlias = $modx->getOption('sortByAlias',$scriptProperties,'quipComment');
$sortDir = $modx->getOption('sortDir',$scriptProperties,'ASC');


/* handle POSTs */
if (!empty($_POST) && $_POST['thread'] == $thread) {
    /* setup POST-only options */
    $removeAction = $modx->getOption('removeAction',$scriptProperties,'quip-remove');
    $reportAction = $modx->getOption('reportAction',$scriptProperties,'quip-report');

    /* handle remove post */
    if (!empty($_POST[$removeAction])) {
        $errors = include_once $quip->config['processors_path'].'web/comment/remove.php';
        if (empty($errors)) {
            $params = $modx->request->getParameters();
            $url = $modx->makeUrl($modx->resource->get('id'),'',$params);
            $modx->sendRedirect($url);
        }
        $placeholders['error'] = implode("<br />\n",$errors);
    }
    /* handle report spam */
    if (!empty($_POST[$reportAction])) {
        $errors = include_once $quip->config['processors_path'].'web/comment/report.php';
        if (empty($errors)) {
            $params = $modx->request->getParameters();
            $params['reported'] = $_POST['id'];
            $url = $modx->makeUrl($modx->resource->get('id'),'',$params);
            $modx->sendRedirect($url);
        }
        $placeholders['error'] = implode("<br />\n",$errors);
    }
}

/* if css, output */
if ($modx->getOption('useCss',$scriptProperties,true)) {
    $modx->regClientCSS($quip->config['css_url'].'web.css');
}

/* ensure thread exists, set thread properties if changed
 * (prior to 0.5.0 threads will be handled in install resolver) */
$threadPK = $thread;
$thread = $modx->getObject('quipThread',$threadPK);
if (!$thread) {
    $thread = $modx->newObject('quipThread');
    $thread->set('name',$threadPK);
    $thread->set('moderated',$moderate);
    $thread->set('moderator_group',$moderatorGroup);
    $thread->set('moderators',$moderators);
    $thread->set('resource',$modx->getOption('resource',$scriptProperties,$modx->resource->get('id')));
    $thread->set('idprefix',$modx->getOption('idPrefix',$scriptProperties,'qcom'));
    if (!empty($scriptProperties['moderatorGroup'])) $thread->set('moderator_group',$scriptProperties['moderatorGroup']);
    /* save existing parameters to comment to preserve URLs */
    $p = $modx->request->getParameters();
    unset($p['reported']);
    $thread->set('existing_params',$p);
    $thread->save();
} else {
    /* sync properties with thread row values */
    $thread->sync($scriptProperties);
}
unset($threadPK);

/* get comments */
$c = $modx->newQuery('quipComment');
$c->innerJoin('quipThread','Thread');
$c->leftJoin('quipCommentClosure','Descendants','`Descendants`.`descendant` = `quipComment`.`id` AND `Descendants`.`ancestor` = 0');
$c->leftJoin('quipCommentClosure','Ancestors');
$c->leftJoin('modUser','Author');
$c->where(array(
    'quipComment.thread' => $thread->get('name'),
    'quipComment.deleted' => false,
));
if (!$thread->checkPolicy('moderate')) {
    $c->andCondition(array(
        'quipComment.approved' => true,
        'OR:quipComment.author:=' => $modx->user->get('id'),
    ),null,2);
}
if (!empty($parent)) {
    $c->where(array(
        'Ancestors.descendant' => $parent,
    ));
}
$placeholders['total'] = $modx->getCount('quipComment',$c);

$c->select(array(
    'quipComment.*',
    'Thread.resource',
    'Thread.idprefix',
    'Thread.existing_params',
    'Descendants.depth',
    'Author.username',
));
$c->sortby('`'.$sortByAlias.'`.`'.$sortBy.'`',$sortDir);
$comments = $modx->getCollection('quipComment',$c);

/* iterate */
$isModerator = $thread->checkPolicy('moderate');
$hasAuth = $modx->user->hasSessionContext($modx->context->get('key')) || $modx->getOption('debug',$scriptProperties,false);
$placeholders['comments'] = '';
$alt = false;
$idx = 0;
foreach ($comments as $comment) {
    $commentArray = $comment->toArray();
    if ($alt) { $commentArray['alt'] = $altRowCss; }
    $commentArray['createdon'] = strftime($dateFormat,strtotime($comment->get('createdon')));
    $commentArray['url'] = $comment->makeUrl();
    $commentArray['idx'] = $idx;
    $commentArray['threaded'] = $threaded;
    $commentArray['depth'] = $comment->get('depth');
    $commentArray['depth_margin'] = (int)($threadedPostMargin * $comment->get('depth'))+7;
    $commentArray['cls'] = $rowCss.($comment->get('approved') ? '' : ' quip-unapproved');

    /* check for auth */
    if ($hasAuth) {
        /* allow removing of comment */
        $commentArray['allowRemove'] = $modx->getOption('allowRemove',$scriptProperties,true);
        /* if not moderator, check for remove threshold, which prevents removing comments
         * after X minutes */
        if (!$isModerator) {
            $removeThreshold = $modx->getOption('removeThreshold',$scriptPropeties,3);
            if (!empty($removeThreshold)) {
                $diff = time() - strtotime($comment->get('createdon'));
                if ($diff > ($removeThreshold * 60)) $commentArray['allowRemove'] = false;
            }
        }
        
        if (!empty($_GET['reported']) && $_GET['reported'] == $comment->get('id')) {
            $commentArray['reported'] = 1;
        }
        if ($comment->get('author') == $modx->user->get('id') || $isModerator) {
            $commentArray['options'] = $quip->getChunk($commentOptionsTpl,$commentArray);
        } else {
            $commentArray['options'] = '';
        }

        if ($modx->getOption('allowReportAsSpam',$scriptProperties,true)) {
            $commentArray['report'] = $quip->getChunk($reportCommentTpl,$commentArray);
        }
    } else {
        $commentArray['report'] = '';
    }
    /* get author display name */
    $nameField = $modx->getOption('nameField',$scriptProperties,'username');
    if (empty($commentArray[$nameField])) {
        $commentArray['authorName'] = $modx->getOption('showAnonymousName',$scriptProperties,false)
            ? $modx->getOption('anonymousName',$scriptProperties,$modx->lexicon('quip.anonymous'))
            : $commentArray['name'];
    } else {
        $commentArray['authorName'] = $commentArray[$nameField];
    }

    if ($showWebsite && !empty($commentArray['website'])) {
        $commentArray['authorName'] = '<a href="'.$commentArray['website'].'">'.$commentArray['authorName'].'</a>';
    }

    if ($threaded && $comment->get('depth') < $maxDepth && $comment->get('approved')
        && (!$requireAuth || $hasAuth) && !$modx->getOption('closed',$scriptProperties,false)) {
        $commentArray['replyUrl'] = $modx->makeUrl($replyResourceId,'',array(
            'quip_thread' => $comment->get('thread'),
            'quip_parent' => $comment->get('id'),
        ));
    }
    $placeholders['comments'] .= $quip->getChunk($commentTpl,$commentArray);
    $alt = !$alt;
    $idx++;
    unset($commentArray);
}

$modx->toPlaceholders($placeholders,'quip');
if ($modx->getOption('useWrapper',$scriptProperties,true)) {
    $output = $quip->getChunk($commentsTpl,$placeholders);
    return $output;
}
return '';

