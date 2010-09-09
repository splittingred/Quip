<?php
/**
 * Quip
 *
 * Copyright 2010 by Shaun McCormick <shaun@modxcms.com>
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
 * @author Shaun McCormick <shaun@modxcms.com>
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

/* get thread */
$threadPK = $thread;
$thread = $modx->getObject('quipThread',$threadPK);
if ($thread) {
    $ps = $thread->get('quip_call_params');
    if (!empty($ps)) {
        $diff = array_diff($ps,$scriptProperties);
        if (empty($diff)) $diff = array_diff_assoc($scriptProperties,$ps);
    }
    if (!empty($diff) || empty($ps)) {
        $thread->set('quip_call_params',$scriptProperties);
        $thread->save();
    }
}

/* get default properties */
$commentTpl = $modx->getOption('tplComment',$scriptProperties,'quipComment');
$commentOptionsTpl = $modx->getOption('tplCommentOptions',$scriptProperties,'quipCommentOptions');
$commentsTpl = $modx->getOption('tplComments',$scriptProperties,'quipComments');
$reportCommentTpl = $modx->getOption('tplReport',$scriptProperties,'quipReport');

$rowCss = $modx->getOption('rowCss',$scriptProperties,'quip-comment');
$altRowCss = $modx->getOption('altRowCss',$scriptProperties,'quip-comment-alt');
$olCss = $modx->getOption('olCss',$scriptProperties,'quip-comment-parent');
$unapprovedCss = $modx->getOption('unapprovedCls',$scriptProperties,'quip-unapproved');
$dateFormat = $modx->getOption('dateFormat',$scriptProperties,'%b %d, %Y at %I:%M %p');
$showWebsite = $modx->getOption('showWebsite',$scriptProperties,true);
$idPrefix = $modx->getOption('idPrefix',$scriptProperties,'qcom');
$resource = $modx->getOption('resource',$scriptProperties,'');

$moderate = $modx->getOption('moderate',$scriptProperties,false);
$moderators = $modx->getOption('moderators',$scriptProperties,false);
$moderatorGroup = $modx->getOption('moderatorGroup',$scriptProperties,false);

$threaded = $modx->getOption('threaded',$scriptProperties,true);
$threadedPostMargin = $modx->getOption('threadedPostMargin',$scriptProperties,15);
$useMargins = $modx->getOption('useMargins',$scriptProperties,false);
$maxDepth = $modx->getOption('maxDepth',$scriptProperties,5);
$replyResourceId = !empty($scriptProperties['replyResourceId']) ? $scriptProperties['replyResourceId'] : $modx->resource->get('id');

$closeAfter = $modx->getOption('closeAfter',$scriptProperties,14);
$useGravatar = $modx->getOption('useGravatar',$scriptProperties,true);
$gravatarIcon = $modx->getOption('gravatarIcon',$scriptProperties,'identicon');
$gravatarSize = $modx->getOption('gravatarSize',$scriptProperties,50);

$sortBy = $modx->getOption('sortBy',$scriptProperties,'rank');
$sortByAlias = $modx->getOption('sortByAlias',$scriptProperties,'quipComment');
$sortDir = $modx->getOption('sortDir',$scriptProperties,'ASC');

$limit = $modx->getOption('quip_limit',$_REQUEST,$modx->getOption('limit',$scriptProperties,0));
$start = $modx->getOption('quip_start',$_REQUEST,$modx->getOption('start',$scriptProperties,0));

/* ensure thread exists, set thread properties if changed
 * (prior to 0.5.0 threads will be handled in install resolver) */
if (!$thread) {
    $thread = $modx->newObject('quipThread');
    $thread->set('name',$threadPK);
    $thread->set('createdon',strftime('%Y-%m-%d %H:%M:%S'));
    $thread->set('moderated',$moderate);
    $thread->set('moderator_group',$moderatorGroup);
    $thread->set('moderators',$moderators);
    $thread->set('resource',$modx->getOption('resource',$scriptProperties,$modx->resource->get('id')));
    $thread->set('idprefix',$modx->getOption('idPrefix',$scriptProperties,'qcom'));
    $thread->set('quip_call_params',$scriptProperties);
    if (!empty($scriptProperties['moderatorGroup'])) $thread->set('moderator_group',$scriptProperties['moderatorGroup']);
    /* save existing parameters to comment to preserve URLs */
    $p = $modx->request->getParameters();
    unset($p['reported'],$p['quip_start'],$p['quip_limit']);
    $thread->set('existing_params',$p);
    $thread->save();
} else {
    /* sync properties with thread row values */
    $thread->sync($scriptProperties);
}
unset($threadPK);

/* handle options */
$removeAction = $modx->getOption('removeAction',$scriptProperties,'quip_remove');
$reportAction = $modx->getOption('reportAction',$scriptProperties,'quip_report');
$isModerator = $thread->checkPolicy('moderate');
$hasAuth = $modx->user->hasSessionContext($modx->context->get('key')) || $modx->getOption('debug',$scriptProperties,false);

/* handle remove post */
if (!empty($_REQUEST[$removeAction])) {
    $errors = include_once $quip->config['processorsPath'].'web/comment/remove.php';
    if (empty($errors)) {
        $params = $modx->request->getParameters();
        unset($params[$removeAction],$params['quip_comment']);
        $url = $modx->makeUrl($modx->resource->get('id'),'',$params);
        $modx->sendRedirect($url);
    }
    $placeholders['error'] = implode("<br />\n",$errors);
}
/* handle report spam */
if (!empty($_REQUEST[$reportAction]) && $modx->getOption('allowReportAsSpam',$scriptProperties,true)) {
    $errors = include_once $quip->config['processorsPath'].'web/comment/report.php';
    if (empty($errors)) {
        $params = $modx->request->getParameters();
        unset($params[$reportAction],$params['quip_comment']);
        $params['reported'] = $_POST['id'];
        $url = $modx->makeUrl($modx->resource->get('id'),'',$params);
        $modx->sendRedirect($url);
    }
    $placeholders['error'] = implode("<br />\n",$errors);
}

/* if css, output */
if ($modx->getOption('useCss',$scriptProperties,true)) {
    $modx->regClientCSS($quip->config['cssUrl'].'web.css');
}
/* set idprefix */
$placeholders['idprefix'] = $thread->get('idprefix');

/* if pagination is on, get IDs of root comments so can limit properly */
$ids = array();
if (!empty($limit)) {
    $c = $modx->newQuery('quipComment');
    $c->select($modx->getSelectColumns('quipComment','quipComment','',array('id')));
    $c->where(array(
        'quipComment.thread' => $thread->get('name'),
        'quipComment.deleted' => false,
        'quipComment.parent' => 0,
    ));
    if (!$thread->checkPolicy('moderate')) {
        $c->where(array(
            'quipComment.approved' => true,
            'OR:quipComment.author:=' => $modx->user->get('id'),
        ));
    }
    $placeholders['rootTotal'] = $modx->getCount('quipComment',$c);
    $c->limit($limit,$start);
    $comments = $modx->getCollection('quipComment',$c);
    $ids = array();
    foreach ($comments as $comment) {
        $ids[] = $comment->get('id');
    }
    $ids = array_unique($ids);
}

/* get comments */
$c = $modx->newQuery('quipComment');
$c->innerJoin('quipThread','Thread');
$c->leftJoin('quipCommentClosure','Descendants');
$c->leftJoin('quipCommentClosure','RootDescendant','`RootDescendant`.`descendant` = `quipComment`.`id` AND `RootDescendant`.`ancestor` = 0');
$c->leftJoin('quipCommentClosure','Ancestors');
$c->leftJoin('modUser','Author');
$c->leftJoin('modResource','Resource');
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
if (!empty($ids)) {
    $c->where('`Descendants`.`ancestor` IN ('.implode(',',$ids).')');
}
$c->select(array(
    'quipComment.*',
    'Thread.resource',
    'Thread.idprefix',
    'Thread.existing_params',
    'RootDescendant.depth',
    'Author.username',
    'Resource.pagetitle',
));
$c->sortby('`'.$sortByAlias.'`.`'.$sortBy.'`',$sortDir);
$comments = $modx->getCollection('quipComment',$c);

$pagePlaceholders = array();

/* iterate */
$stillOpen = $thread->checkIfStillOpen($closeAfter) && !$modx->getOption('closed',$scriptProperties,false);
$placeholders['comments'] = '';
$alt = false;
$idx = 0;
$commentList = array();
foreach ($comments as $comment) {
    $commentArray = $comment->toArray();
    $commentArray['alt'] = $alt ? $altRowCss : 's';
    $commentArray['createdon'] = strftime($dateFormat,strtotime($comment->get('createdon')));
    $commentArray['url'] = $comment->makeUrl();
    $commentArray['idx'] = $idx;
    $commentArray['threaded'] = $threaded;
    $commentArray['depth'] = $comment->get('depth');
    if ($useMargins) {
        $commentArray['depth_margin'] = (int)($threadedPostMargin * $comment->get('depth'))+7;
    }
    $commentArray['cls'] = $rowCss.($comment->get('approved') ? '' : ' '.$unapprovedCls);
    $commentArray['olCls'] = $olCss;
    if ($useGravatar) {
        $commentArray['md5email'] = md5($comment->get('email'));
        $commentArray['gravatarIcon'] = $gravatarIcon;
        $commentArray['gravatarSize'] = $gravatarSize;
    }

    /* check for auth */
    if ($hasAuth) {
        /* allow removing of comment if moderator or own comment */
        $commentArray['allowRemove'] = $modx->getOption('allowRemove',$scriptProperties,true);
        if ($commentArray['allowRemove']) {
            if ($isModerator && false) {
                /* Always allow remove for moderators */
                $commentArray['allowRemove'] = true;
            } else if ($comment->get('author') == $modx->user->get('id')) {
                /* if not moderator but author of post, check for remove
                 * threshold, which prevents removing comments after X minutes
                 */
                $removeThreshold = $modx->getOption('removeThreshold',$scriptPropeties,3);
                if (!empty($removeThreshold)) {
                    $diff = time() - strtotime($comment->get('createdon'));
                    if ($diff > ($removeThreshold * 60)) $commentArray['allowRemove'] = false;
                }
            }
        }
        
        if (!empty($_GET['reported']) && $_GET['reported'] == $comment->get('id')) {
            $commentArray['reported'] = 1;
        }
        if ($comment->get('author') == $modx->user->get('id') || $isModerator) {
            $params = $modx->request->getParameters();
            $params['quip_comment'] = $comment->get('id');
            $params[$removeAction] = true;
            $commentArray['removeUrl'] = $comment->makeUrl('',$params,null,false);
            $commentArray['options'] = $quip->getChunk($commentOptionsTpl,$commentArray);
        } else {
            $commentArray['options'] = '';
        }

        if ($modx->getOption('allowReportAsSpam',$scriptProperties,true)) {
            $params = $modx->request->getParameters();
            $params['quip_comment'] = $comment->get('id');
            $params[$reportAction] = true;
            $commentArray['reportUrl'] = $comment->makeUrl('',$params,null,false);
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

    if ($threaded && $stillOpen && $comment->get('depth') < $maxDepth && $comment->get('approved')
        && (!$requireAuth || $hasAuth) && !$modx->getOption('closed',$scriptProperties,false)) {

        $params = $modx->request->getParameters();
        $params['quip_thread'] = $comment->get('thread');
        $params['quip_parent'] = $comment->get('id');
        $commentArray['replyUrl'] = $modx->makeUrl($replyResourceId,'',$params);
    }
    $commentList[] = $commentArray;
    $alt = !$alt;
    $idx++;
    $pagePlaceholders['pagetitle'] = $commentArray['pagetitle'];
    $pagePlaceholders['resource'] = $commentArray['resource'];
    unset($commentArray);
}

$placeholders['comments'] = '';
if ($useMargins) {
    foreach ($commentList as $commentArray) {
        $placeholders['comments'] .= $quip->getChunk($commentTpl,$commentArray);
    }
} else {
    if ($modx->loadClass('QuipTreeParser',$quip->config['modelPath'].'quip/',true,true)) {
        $quip->treeParser = new QuipTreeParser($quip);
        
        $placeholders['comments'] = $quip->treeParser->parse($commentList,$commentTpl);
    }
}

if (!empty($limit)) {
    $url = $modx->makeUrl($modx->resource->get('id'));
    $placeholders['pagination'] = $quip->buildPagination(array(
        'count' => $placeholders['rootTotal'],
        'limit' => $limit,
        'start' => $start,
        'url' => $url,
    ));
}

/* wrap */
if ($modx->getOption('useWrapper',$scriptProperties,true)) {
    $output = $quip->getChunk($commentsTpl,$placeholders);
}

/* output */
$pagePlaceholders = array_merge($placeholders,$pagePlaceholders);
$placeholderPrefix = $modx->getOption('placeholderPrefix',$scriptProperties,'quip');
$modx->toPlaceholders($pagePlaceholders,$placeholderPrefix);
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);
if ($toPlaceholder) {
    $modx->setPlaceholder($toPlaceholder,$output);
    return '';
}
return $output;

