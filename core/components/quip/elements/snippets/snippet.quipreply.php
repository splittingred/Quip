<?php
/**
 * Quip
 *
 * Copyright 2010-11 by Shaun McCormick <shaun@modx.com>
 *
 * This file is part of Quip, a simple commenting component for MODx Revolution.
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
 * Displays a reply form for a thread
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var Quip $quip
 *
 * @name QuipReply
 * @author Shaun McCormick <shaun@modx.com>
 * @package quip
 */
$quip = $modx->getService('quip','Quip',$modx->getOption('quip.core_path',null,$modx->getOption('core_path').'components/quip/').'model/quip/',$scriptProperties);
if (!($quip instanceof Quip)) return '';

$quip->initialize($modx->context->get('key'));
$controller = $quip->loadController('ThreadReply');
$output = $controller->run($scriptProperties);
return $output;

/* get thread */
$thread = $modx->getOption('quip_thread',$_REQUEST,$modx->getOption('thread',$scriptProperties,''));
if (empty($thread)) return '';
$thread = $modx->getObject('quipThread',array('name' => $thread));
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
$requirePreview = $modx->getOption('requirePreview',$scriptProperties,false);
$previewAction = $modx->getOption('previewAction',$scriptProperties,'quip-preview');
$postAction = $modx->getOption('postAction',$scriptProperties,'quip-post');
$allowedTags = $modx->getOption('quip.allowed_tags',$scriptProperties,'<br><b><i>');
$preHooks = $modx->getOption('preHooks',$scriptProperties,'');
$postHooks = $modx->getOption('postHooks',$scriptProperties,'');
$unsubscribeAction = $modx->getOption('unsubscribeAction',$scriptProperties,'quip_unsubscribe');

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
$fields = array();
$hasPreview = false;
if (!empty($_POST)) {
    foreach ($_POST as $k => $v) {
        $fields[$k] = str_replace(array('[',']'),array('&#91;','&#93;'),$v);
    }
    
    $fields['name'] = strip_tags($fields['name']);
    $fields['email'] = strip_tags($fields['email']);
    $fields['website'] = strip_tags($fields['website']);
    
    /* verify a message was posted */
    if (empty($fields['comment'])) $errors['comment'] = $modx->lexicon('quip.message_err_ns');
    if (empty($fields['name'])) $errors['name'] = $modx->lexicon('quip.name_err_ns');
    if (empty($fields['email'])) $errors['email'] = $modx->lexicon('quip.email_err_ns');
    
    if (!empty($_POST[$postAction]) && empty($errors)) {
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
        } else if (is_array($comment)) {
            $errors = array_merge($errors,$comment);
        }
        $fields[$previewAction] = true;
    }
    /* handle preview */
    else if (!empty($fields[$previewAction]) && empty($errors)) {
        $errors = include_once $quip->config['processorsPath'].'web/comment/preview.php';
    }
    if (!empty($errors)) {
        $placeholders['error'] = implode("<br />\n",$errors);
        foreach ($errors as $k => $v) {
            $placeholders['error.'.$k] = $v;
        }
        $placeholders = array_merge($placeholders,$fields);
    }
}
/* display moderated success message */
if (isset($_GET['quip_approved']) && $_GET['quip_approved'] == 0) {
    $placeholders['successMsg'] = $modx->lexicon('quip.comment_will_be_moderated');
}

/* handle unsubscribing from thread */
if (!empty($_GET[$unsubscribeAction]) && $modx->user->hasSessionContext($modx->context->get('key'))) {
    $profile = $modx->user->getOne('Profile');
    if ($profile) {
        $notify = $modx->getObject('quipCommentNotify',array(
            'email' => $profile->get('email'),
            'thread' => $thread,
        ));
        if ($notify && $notify->remove()) {
            $placeholders['successMsg'] = $modx->lexicon('quip.unsubscribed');
        }
    }
}

/* if using recaptcha, load recaptcha html if user is not logged in */
$disableRecaptchaWhenLoggedIn = (boolean)$modx->getOption('disableRecaptchaWhenLoggedIn',$scriptProperties,true);
$useRecaptcha = (boolean)$modx->getOption('recaptcha',$scriptProperties,false);
if ($useRecaptcha && !($disableRecaptchaWhenLoggedIn && $hasAuth) && !$hasPreview) {
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
    $phs['unsubscribe'] = '';

    /* prefill fields */
    $profile = $modx->user->getOne('Profile');
    if ($profile) {
        $phs['name'] = !empty($fields['name']) ? $fields['name'] : $profile->get('fullname');
        $phs['email'] = !empty($fields['email']) ? $fields['email'] : $profile->get('email');
        $phs['website'] = !empty($fields['website']) ? $fields['website'] : $profile->get('website');

        /* allow for unsubscribing for logged-in users */
        if ($modx->user->hasSessionContext($modx->context->get('key'))) {
            $notify = $modx->getObject('quipCommentNotify',array(
                'email' => $profile->get('email'),
                'thread' => $thread,
            ));
            if ($notify) {
                $phs['notifyId'] = $notify->get('id');
                $phs['unsubscribe'] = $quip->getChunk('quipUnsubscribe',$phs);
                $params = $modx->request->getParameters();
                $params[$unsubscribeAction] = 1;
                $phs['unsubscribeUrl'] = $modx->makeUrl($modx->resource->get('id'),'',$params);
            }
        }
    }

    /* if requirePreview == false, auto-can post */
    if (!$requirePreview) {
        $phs['can_post'] = true;
    }
    $phs['post_action'] = $postAction;
    $phs['preview_action'] = $previewAction;
    $phs['allowed_tags'] = $allowedTags;
    $phs['notifyChecked'] = !empty($fields['notify']) ? ' checked="checked"' : '';

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