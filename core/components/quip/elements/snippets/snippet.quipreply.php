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
 * QuipReply
 *
 * Displays a reply form for a thread
 *
 * @name QuipReply
 * @author Shaun McCormick <shaun@modxcms.com>
 * @package quip
 */
$quip = $modx->getService('quip','Quip',$modx->getOption('quip.core_path',null,$modx->getOption('core_path').'components/quip/').'model/quip/',$scriptProperties);
if (!($quip instanceof Quip)) return '';

/* get thread */
$thread = $modx->getOption('quip_thread',$_REQUEST,$modx->getOption('thread',$scriptProperties,''));
if (empty($thread)) return '';
$thread = $modx->getObject('quipThread',$thread);
if (!$thread) return '';

/* sync properties with thread row values */
$thread->sync($scriptProperties);
$ps = $thread->get('quipreply_call_params');
if (!empty($ps)) {
    $diff = array_diff_assoc($ps,$scriptProperties);
    if (empty($diff)) $diff = array_diff_assoc($scriptProperties,$ps);
}
if (empty($_REQUEST['quip_thread']) && (!empty($diff) || empty($ps))) { /* only sync call params if not on threaded reply page */
    $thread->set('quipreply_call_params',$scriptProperties);
    $thread->save();
}
/* if in threaded reply page, get the original passing values to QuipReply in the thread's main page and use those */
if (!empty($_REQUEST['quip_thread']) && is_array($ps) && !empty($ps)) $scriptProperties = array_merge($scriptProperties,$ps);
unset($ps,$diff);

/* setup default properties */
$requireAuth = $modx->getOption('requireAuth',$scriptProperties,false);
$requireUsergroups = $modx->getOption('requireUsergroups',$scriptProperties,false);
$addCommentTpl = $modx->getOption('tplAddComment',$scriptProperties,'quipAddComment');
$loginToCommentTpl = $modx->getOption('tplLoginToComment',$scriptProperties,'quipLoginToComment');
$previewTpl = $modx->getOption('tplPreview',$scriptProperties,'quipPreviewComment');
$closeAfter = $modx->getOption('closeAfter',$scriptProperties,14);

/* get parent and auth */
$parent = $modx->getOption('quip_parent',$_REQUEST,$modx->getOption('parent',$scriptProperties,0));
$hasAuth = $modx->user->hasSessionContext($modx->context->get('key')) || $modx->getOption('debug',$scriptProperties,false) || empty($requireAuth);
if (!empty($requireUsergroups)) {
    $requireUsergroups = explode(',',$requireUsergroups);
    $hasAuth = $modx->user->isMember($requireUsergroups);
}

/* setup default placeholders */
$placeholders = array();
$p = $modx->request->getParameters();
unset($p['reported'],$p['quip_approved']);
$placeholders['parent'] = $parent;
$placeholders['thread'] = $thread->get('name');
$placeholders['url'] = $modx->makeUrl($modx->resource->get('id'),'',$p);
$placeholders['idprefix'] = $thread->get('idprefix');

/* handle POST */
if (!empty($_POST)) {
    foreach ($_POST as $k => $v) {
        $_POST[$k] = str_replace(array('[',']'),array('&#91;','&#93;'),$v);
    }
    $previewAction = $modx->getOption('previewAction',$scriptProperties,'quip-preview');
    $postAction = $modx->getOption('postAction',$scriptProperties,'quip-post');
    $allowedTags = $modx->getOption('quip.allowed_tags',$scriptProperties,'<br><b><i>');
    
    $_POST['name'] = strip_tags($_POST['name']);
    $_POST['email'] = strip_tags($_POST['email']);
    $_POST['website'] = strip_tags($_POST['website']);
    
    if (!empty($_POST[$postAction])) {
        $comment = include_once $quip->config['processorsPath'].'web/comment/create.php';
        if (is_object($comment) && $comment instanceof quipComment) {
            $params = $modx->request->getParameters();
            unset($params[$postAction],$params['quip_parent'],$params['quip_thread']);
            $params['quip_approved'] = $comment->get('approved') ? 1 : 0;
            $url = $comment->makeUrl('',$params);

            /* if not approved, remove # and replace with success message #
             * since comment is not yet visible
             */
            if (!$comment->get('approved')) {
                $url = str_replace('#'.$thread->get('idprefix').$comment->get('id'),'#quip-success-'.$thread->get('idprefix'),$url);
            }
            $modx->sendRedirect($url);
        }
        $placeholders['error'] = implode("<br />\n",$errors);
        $_POST[$previewAction] = true;
    }
    /* handle preview */
    if (!empty($_POST[$previewAction])) {
        $errors = include_once $quip->config['processorsPath'].'web/comment/preview.php';
    }
}
if (isset($_GET['quip_approved']) && $_GET['quip_approved'] == 0) {
    $placeholders['successMsg'] = $modx->lexicon('quip.comment_will_be_moderated');
}

/* if using recaptcha, load recaptcha html if user is not logged in */
$disableRecaptchaWhenLoggedIn = $modx->getOption('disableRecaptchaWhenLoggedIn',$scriptProperties,true);
if ($modx->getOption('recaptcha',$scriptProperties,false) && !($disableRecaptchaWhenLoggedIn && $hasAuth)) {
    $recaptcha = $modx->getService('recaptcha','reCaptcha',$quip->config['modelPath'].'recaptcha/');
    if ($recaptcha instanceof reCaptcha) {
        $recaptchaTheme = $modx->getOption('recaptchaTheme',$scriptProperties,'clean');
        $html = $recaptcha->getHtml($recaptchaTheme);
        $modx->setPlaceholder('quip.recaptcha_html',$html);
    } else {
        return $modx->lexicon('quip.recaptcha_err_load');
    }
}

/* build reply form */
$replyForm = '';

$stillOpen = $thread->checkIfStillOpen($closeAfter) && !$modx->getOption('closed',$scriptProperties,false);
if ($hasAuth && $stillOpen) {
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
} else if (!$stillOpen) {
    $replyForm = $modx->lexicon('quip.thread_autoclosed');
} else {
    $replyForm = $quip->getChunk($loginToCommentTpl,$placeholders);
}

/* output or set to placeholder */
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);
if ($toPlaceholder) {
    $modx->setPlaceholder($toPlaceholder,$replyForm);
    return '';
}
return $replyForm;