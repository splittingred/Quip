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

/* get default properties */
$requireAuth = $modx->getOption('requireAuth',$scriptProperties,false);
$commentTpl = $modx->getOption('tplComment',$scriptProperties,'quipComment');
$commentOptionsTpl = $modx->getOption('tplCommentOptions',$scriptProperties,'quipCommentOptions');
$commentsTpl = $modx->getOption('tplComments',$scriptProperties,'quipComments');
$reportCommentTpl = $modx->getOption('tplReport',$scriptProperties,'quipReport');
$addCommentTpl = $modx->getOption('tplAddComment',$scriptProperties,'quipAddComment');
$loginToCommentTpl = $modx->getOption('tplLoginToComment',$scriptProperties,'quipLoginToComment');
$previewTpl = $modx->getOption('tplPreview',$scriptProperties,'quipPreviewComment');

$altRowCss = $modx->getOption('altRowCss',$scriptProperties,'quip-comment-alt');
$dateFormat = $modx->getOption('dateFormat',$scriptProperties,'%b %d, %Y at %I:%M %p');
$showWebsite = $modx->getOption('showWebsite',$scriptProperties,true);
$idPrefix = $modx->getOption('idPrefix',$scriptProperties,'qcom');
$resource = $modx->getOption('resource',$scriptProperties,'');

$sortBy = $modx->getOption('sortBy',$scriptProperties,'createdon');
$sortByAlias = $modx->getOption('sortByAlias',$scriptProperties,'quipComment');
$sortDir = $modx->getOption('sortDir',$scriptProperties,'DESC');


/* handle POSTs */
if (!empty($_POST) && $_POST['thread'] == $scriptProperties['thread']) {
    /* setup POST-only options */
    $removeAction = $modx->getOption('removeAction',$scriptProperties,'quip-remove');
    $previewAction = $modx->getOption('previewAction',$scriptProperties,'quip-preview');
    $postAction = $modx->getOption('postAction',$scriptProperties,'quip-post');
    $reportAction = $modx->getOption('reportAction',$scriptProperties,'quip-report');
    $allowedTags = $modx->getOption('quip.allowed_tags',$scriptProperties,'<br><b><i>');

    /* handle remove post */
    if (!empty($_POST[$removeAction])) {
        $errors = include_once $quip->config['processors_path'].'web/comment/remove.php';
        if (empty($errors)) {
            $params = $modx->request->getParameters();
            $url = $modx->makeUrl($modx->resource->get('id'),'',$params);
            $modx->sendRedirect($url);
        }
        $placeholders['error'] = implode("<br />\n",$errors);

    /* handle post new */
    } else if (!empty($_POST[$postAction])) {
        $errors = include_once $quip->config['processors_path'].'web/comment/create.php';
        if (empty($errors)) {
            $params = $modx->request->getParameters();
            $url = $modx->makeUrl($modx->resource->get('id'),'',$params);
            $modx->sendRedirect($url);
        }
        $placeholders['error'] = implode("<br />\n",$errors);
        $_POST[$previewAction] = true;
    }

    /* handle preview */
    if (!empty($_POST[$previewAction])) {
        $errors = include_once $quip->config['processors_path'].'web/comment/preview.php';

    /* handle report spam */
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
if ($modx->getOption('useCss',$scriptProperties,true)) {
    $modx->regClientCSS($quip->config['css_url'].'web.css');
}

/* if using recaptcha, load recaptcha html */
if ($modx->getOption('recaptcha',$scriptProperties,false)) {
    $recaptcha = $modx->getService('recaptcha','reCaptcha',$quip->config['model_path'].'recaptcha/');
    if ($recaptcha instanceof reCaptcha) {
        $html = $recaptcha->getHtml();
        $modx->setPlaceholder('quip.recaptcha_html',$html);
    } else {
        return $modx->lexicon('quip.recaptcha_err_load');
    }
}

/* get comments */
$c = $modx->newQuery('quipComment');
$c->leftJoin('modUser','Author');
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

    /* check for auth */
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
    if ($showWebsite && !empty($commentArray['website'])) {
        $commentArray['name'] = '<a href="'.$commentArray['website'].'">'.$commentArray['name'].'</a>';
    }
    $placeholders['comments'] .= $quip->getChunk($commentTpl,$commentArray);
    $alt = !$alt;
}

$placeholders['addcomment'] = '';
if ((!$requireAuth || $hasAuth) && !$modx->getOption('closed',$scriptProperties,false)) {
    $phs = array_merge($placeholders,array(
        'username' => $modx->user->get('username'),
        'thread' => $scriptProperties['thread'],
    ));

    /* prefill fields */
    $profile = $modx->user->getOne('Profile');
    if ($profile) {
        $phs['name'] = !empty($_POST['name']) ? $_POST['name'] : $profile->get('fullname');
        $phs['email'] = !empty($_POST['email']) ? $_POST['email'] : $profile->get('email');
        $phs['website'] = !empty($_POST['website']) ? $_POST['website'] : $profile->get('website');
    }

    $placeholders['addcomment'] = $quip->getChunk($addCommentTpl,$phs);
} else {
    $placeholders['addcomment'] = $quip->getChunk($loginToCommentTpl,$placeholders);
}

$p = $modx->request->getParameters();
unset($p['reported']);
$placeholders['url'] = $modx->makeUrl($modx->resource->get('id'),'',$p);

$modx->toPlaceholders($placeholders,'quip');
if ($modx->getOption('useWrapper',$scriptProperties,true)) {
    $output = $quip->getChunk($commentsTpl,$placeholders);
    return $output;
}
return '';

