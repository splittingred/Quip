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
 * QuipReply
 *
 * Displays a reply form for a thread
 *
 * @name QuipReply
 * @author Shaun McCormick <shaun@collabpad.com>
 * @package quip
 */
$quip = $modx->getService('quip','Quip',$modx->getOption('quip.core_path',null,$modx->getOption('core_path').'components/quip/').'model/quip/',$scriptProperties);
if (!($quip instanceof Quip)) return '';

/* setup default properties */
$requireAuth = $modx->getOption('requireAuth',$scriptProperties,false);
$addCommentTpl = $modx->getOption('tplAddComment',$scriptProperties,'quipAddComment');
$loginToCommentTpl = $modx->getOption('tplLoginToComment',$scriptProperties,'quipLoginToComment');
$previewTpl = $modx->getOption('tplPreview',$scriptProperties,'quipPreviewComment');

$thread = $modx->getOption('quip_thread',$_REQUEST,$modx->getOption('thread',$scriptProperties,''));
if (empty($thread)) return '';
$parent = $modx->getOption('quip_parent',$_REQUEST,$modx->getOption('parent',$scriptProperties,0));

/* setup default placeholders */
$placeholders = array();
$p = $modx->request->getParameters();
unset($p['reported']);
$placeholders['parent'] = $parent;
$placeholders['thread'] = $thread;
$placeholders['url'] = $modx->makeUrl($modx->resource->get('id'),'',$p);

/* handle POST */
if (!empty($_POST)) {
    $previewAction = $modx->getOption('previewAction',$scriptProperties,'quip-preview');
    $postAction = $modx->getOption('postAction',$scriptProperties,'quip-post');
    $allowedTags = $modx->getOption('quip.allowed_tags',$scriptProperties,'<br><b><i>');
    
    if (!empty($_POST[$postAction])) {
        $comment = include_once $quip->config['processors_path'].'web/comment/create.php';
        if (is_object($comment) && $comment instanceof quipComment) {
            $url = $comment->makeUrl();
            $modx->sendRedirect($url);
        }
        $placeholders['error'] = implode("<br />\n",$errors);
        $_POST[$previewAction] = true;
    }
    /* handle preview */
    if (!empty($_POST[$previewAction])) {
        $errors = include_once $quip->config['processors_path'].'web/comment/preview.php';
    }
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

/* build reply form */
$replyForm = '';
if ((!$requireAuth || $hasAuth) && !$modx->getOption('closed',$scriptProperties,false)) {
    $phs = array_merge($placeholders,array(
        'username' => $modx->user->get('username'),
    ));

    /* prefill fields */
    $profile = $modx->user->getOne('Profile');
    if ($profile) {
        $phs['name'] = !empty($_POST['name']) ? $_POST['name'] : $profile->get('fullname');
        $phs['email'] = !empty($_POST['email']) ? $_POST['email'] : $profile->get('email');
        $phs['website'] = !empty($_POST['website']) ? $_POST['website'] : $profile->get('website');
    }

    $replyForm = $quip->getChunk($addCommentTpl,$phs);
} else {
    $replyForm = $quip->getChunk($loginToCommentTpl,$placeholders);
}

/* output or set to placeholder */
if ($toPlaceholder) {
    $modx->setPlaceholder($toPlaceholder,$replyForm);
    return '';
} else {
    return $replyForm;
}