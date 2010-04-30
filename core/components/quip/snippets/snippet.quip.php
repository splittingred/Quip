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

$altRowCss = $modx->getOption('altRowCss',$scriptProperties,'quip-comment-alt');
$dateFormat = $modx->getOption('dateFormat',$scriptProperties,'%b %d, %Y at %I:%M %p');
$showWebsite = $modx->getOption('showWebsite',$scriptProperties,true);
$idPrefix = $modx->getOption('idPrefix',$scriptProperties,'qcom');
$resource = $modx->getOption('resource',$scriptProperties,'');

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

/* get comments */
$c = $modx->newQuery('quipComment');
$c->leftJoin('quipCommentClosure','Descendants','`Descendants`.`descendant` = `quipComment`.`id` AND `Descendants`.`ancestor` = 0');
$c->leftJoin('quipCommentClosure','Ancestors');
$c->leftJoin('modUser','Author');
$c->where(array(
    'quipComment.thread' => $thread,
));
if (!empty($parent)) {
    $c->where(array(
        'Ancestors.descendant' => $parent,
    ));
}
$placeholders['total'] = $modx->getCount('quipComment',$c);
$c->select('
    `quipComment`.*,
    `Descendants`.`depth` AS `depth`,
    `Author`.`username` AS `username`
');
$c->sortby('`'.$sortByAlias.'`.`'.$sortBy.'`',$sortDir);
$comments = $modx->getCollection('quipComment',$c);

/* iterate */
$hasAuth = $modx->user->hasSessionContext($modx->context->get('key')) || $modx->getOption('debug',$scriptProperties,false);
$placeholders['comments'] = '';
$alt = false;
$idx = 0;
foreach ($comments as $comment) {
    if (!empty($idPrefix)) { /* autoset changed idprefix */
        if ($comment->get('idprefix') != $idPrefix) {
            $comment->set('idprefix',$idPrefix);
            $comment->save();
        }
    }
    /* persist existing GET params */
    if ($comment->get('existing_params') == '') {
        $p = $modx->request->getParameters();
        $comment->set('existing_params',$p);
        $comment->save();
        unset($p);
    }

    /* fix to set resource field from older versions to map comment to a resource */
    if ($comment->get('resource') == 0) {
        if (empty($resource)) $resource = $modx->resource->get('id');
        $comment->set('resource',$resource);
        $comment->save();
    }

    $commentArray = $comment->toArray();
    if ($alt) { $commentArray['alt'] = $altRowCss; }
    $commentArray['createdon'] = strftime($dateFormat,strtotime($comment->get('createdon')));
    $commentArray['url'] = $comment->makeUrl();
    $commentArray['idx'] = $idx;
    $commentArray['threaded'] = $threaded;
    $commentArray['depth'] = $comment->get('depth');
    $commentArray['depth_margin'] = (int)($threadedPostMargin * $comment->get('depth'))+7;

    /* check for auth */
    if ($hasAuth) {
        $commentArray['allowRemove'] = $modx->getOption('allowRemove',$scriptProperties,false);
        
        if (!empty($_GET['reported']) && $_GET['reported'] == $comment->get('id')) {
            $commentArray['reported'] = 1;
        }
        if ($comment->get('author') == $modx->user->get('id')) {
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
    $nameField = $modx->getOption('nameField',$scriptProperties,'username');
    if (empty($commentArray[$nameField])) $nameField = 'name';
    $commentArray['authorName'] = $commentArray[$nameField];

    if ($showWebsite && !empty($commentArray['website'])) {
        $commentArray['authorName'] = '<a href="'.$commentArray['website'].'">'.$commentArray['authorName'].'</a>';
    }

    if ($threaded && $comment->get('depth') < $maxDepth && (!$requireAuth || $hasAuth) && !$modx->getOption('closed',$scriptProperties,false)) {
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

