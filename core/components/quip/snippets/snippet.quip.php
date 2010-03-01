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
if (empty($scriptProperties['thread'])) { return ''; }
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
$placeholders['self'] = $modx->makeUrl($modx->resource->get('id'),'',$params);

/* get default properties */
$commentTpl = $modx->getOption('tplComment',$scriptProperties,'quipComment');
$commentOptionsTpl = $modx->getOption('tplCommentOptions',$scriptProperties,'quipCommentOptions');
$commentsTpl = $modx->getOption('tplComments',$scriptProperties,'quipComments');
$reportCommentTpl = $modx->getOption('tplReport',$scriptProperties,'quipReport');
$addCommentTpl = $modx->getOption('tplAddComment',$scriptProperties,'quipAddComment');
$loginToCommentTpl = $modx->getOption('tplLoginToComment',$scriptProperties,'quipLoginToComment');
$previewTpl = $modx->getOption('tplPreview',$scriptProperties,'QuipPreviewComment');

$useCss = $modx->getOption('useCss',$scriptProperties,true);
$altRowCss = $modx->getOption('altRowCss',$scriptProperties,'quip-comment-alt');
$dateFormat = $modx->getOption('dateFormat',$scriptProperties,'%b %d, %Y at %I:%M %p');

$sortBy = $modx->getOption('sortBy',$scriptProperties,'createdon');
$sortByAlias = $modx->getOption('sortByAlias',$scriptProperties,'quipComment');
$sortDir = $modx->getOption('sortDir',$scriptProperties,'DESC');


/* handle POSTs */
if (!empty($_POST)) {
    /* setup POST-only options */
    $removeAction = $modx->getOption('removeAction',$scriptProperties,'quip-remove');
    $previewAction = $modx->getOption('previewAction',$scriptProperties,'quip-preview');
    $postAction = $modx->getOption('postAction',$scriptProperties,'quip-post');
    $reportAction = $modx->getOption('reportAction',$scriptProperties,'quip-report');
    $allowedTags = $modx->getOption('quip.allowed_tags',$scriptProperties,'<br><b><i>');

    if (!empty($_POST[$removeAction])) {
        $errors = include_once $quip->config['processors_path'].'web/comment/remove.php';
        if (empty($errors)) {
            $params = $modx->request->getParameters();
            $url = $modx->makeUrl($modx->resource->get('id'),'',$params);
            $modx->sendRedirect($url);
        }
        $placeholders['error'] = implode("<br />\n",$errors);

    } else if (!empty($_POST[$previewAction])) {
        $errors = include_once $quip->config['processors_path'].'web/comment/preview.php';

    } else if (!empty($_POST[$postAction])) {
        $errors = include_once $quip->config['processors_path'].'web/comment/create.php';
        if (empty($errors)) {
            $params = $modx->request->getParameters();
            $url = $modx->makeUrl($modx->resource->get('id'),'',$params);
            $modx->sendRedirect($url);
        }
        $placeholders['error'] = implode("<br />\n",$errors);

    } else if (!empty($_POST[$reportAction])) {
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
if ($useCss) {
    $modx->regClientCSS($quip->config['css_url'].'web.css');
}

/* get comments */
$c = $modx->newQuery('quipComment');
$c->innerJoin('modUser','Author');
$c->where(array(
    'quipComment.thread' => $scriptProperties['thread'],
));
$placeholders['total'] = $modx->getCount('quipComment',$c);
$c->select('
    `quipComment`.*,
    `Author`.`username` AS `username`
');
$c->sortby('`'.$sortByAlias.'`.`'.$sortBy.'`',$sortDir);
$comments = $modx->getCollection('quipComment',$c);

/* iterate */
$hasAuth = $modx->user->hasSessionContext($modx->context->get('key')) || $modx->getOption('debug',$scriptProperties,false);
$placeholders['comments'] = '';
$alt = false;
foreach ($comments as $comment) {
    $commentArray = $comment->toArray();
    if ($alt) { $commentArray['alt'] = $altRowCss; }
    $commentArray['createdon'] = strftime($dateFormat,strtotime($comment->get('createdon')));

    if ($hasAuth) {
        if (!empty($_GET['reported']) && $_GET['reported'] == $comment->get('id')) {
            $commentArray['reported'] = 1;
        }
        if ($comment->get('author') == $modx->user->get('id')) {
            $commentArray['options'] = $quip->getChunk($commentOptionsTpl,$commentArray);
        } else {
            $commentArray['options'] = '';
        }

        $commentArray['report'] = $quip->getChunk($reportCommentTpl,$commentArray);
    } else {
        $commentArray['report'] = '';
    }
    $commentArray['self'] = $placeholders['self'];
    $placeholders['comments'] .= $quip->getChunk($commentTpl,$commentArray);
    $alt = !$alt;
}

$placeholders['addcomment'] = '';
if ($hasAuth && !$modx->getOption('closed',$scriptProperties,false)) {
    $placeholders['addcomment'] = $quip->getChunk($addCommentTpl,array(
        'self' => $placeholders['self'],
        'username' => $modx->user->get('username'),
        'thread' => $scriptProperties['thread'],
        'error' => $placeholders['error'],
        'comment' => $placeholders['comment'],
    ));
} else {
    $placeholders['addcomment'] = $quip->getChunk($loginToCommentTpl,$placeholders);
}
$modx->toPlaceholders($placeholders,'quip');
if ($modx->getOption('useWrapper',$scriptProperties,true)) {
    $output = $quip->getChunk($commentsTpl,$placeholders);
    return $output;
}
return '';

