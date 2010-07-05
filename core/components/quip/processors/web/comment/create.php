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
 * Create a comment
 *
 * @package quip
 * @subpackage processors
 */
$errors = array();
/* verify a message was posted */
if (empty($_POST['comment'])) $errors[] = $modx->lexicon('quip.message_err_ns');
if (empty($_POST['name'])) $errors[] = $modx->lexicon('quip.name_err_ns');
if (empty($_POST['email'])) $errors[] = $modx->lexicon('quip.email_err_ns');

/* prevent spambots, author spoofing, greasemonkey kiddos, etc */
if ($requireAuth && $_POST['author'] != $modx->user->get('id')) {
    $errors['message'] = $modx->lexicon('quip.err_fraud_attempt');
    return $errors;
}

/* verify against spam */
if ($modx->loadClass('stopforumspam.StopForumSpam',$quip->config['model_path'],true,true)) {
    $sfspam = new StopForumSpam($modx);
    $spamResult = $sfspam->check($_SERVER['REMOTE_ADDR'],$_POST['email']);
    if (!empty($spamResult)) {
        $spamFields = implode($modx->lexicon('quip.spam_marked')."\n<br />",$spamResult);
        $errors['email'] = $modx->lexicon('quip.spam_blocked',array(
            'fields' => $spamFields,
        ));
    }
} else {
    $modx->log(modX::LOG_LEVEL_ERROR,'[Quip] Couldnt load StopForumSpam class.');
}

/* sanity checks - strip out iframe/javascript */
$body = $_POST['comment'];
$body = preg_replace("/<script(.*)<\/script>/i",'',$body);
$body = preg_replace("/<iframe(.*)<\/iframe>/i",'',$body);
$body = preg_replace("/<iframe(.*)\/>/i",'',$body);
$body = strip_tags($body,$allowedTags);
$body = str_replace(array('<br><br>','<br /><br />'),'',nl2br($body));
/* replace MODx tags with entities */
$body = str_replace(array('[',']'),array('&#91;','&#93;'),$body);

/* auto-convert links to tags */
if ($modx->getOption('autoConvertLinks',$scriptProperties,true)) {
    $pattern = "@\b(https?://)?(([0-9a-zA-Z_!~*'().&=+$%-]+:)?[0-9a-zA-Z_!~*'().&=+$%-]+\@)?(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-zA-Z_!~*'()-]+\.)*([0-9a-zA-Z][0-9a-zA-Z-]{0,61})?[0-9a-zA-Z]\.[a-zA-Z]{2,6})(:[0-9]{1,4})?((/[0-9a-zA-Z_!~*'().;?:\@&=+$,%#-]+)*/?)@";
    $body = preg_replace($pattern, '<a href="\0">\0</a>',$body);
}


/* if no errors, save comment */
if (!empty($errors)) return $errors;


$comment = $modx->newObject('quipComment');
$comment->fromArray($_POST);
$comment->set('ip',$_SERVER['REMOTE_ADDR']);

/* if moderation is on, don't auto-approve comments */
if ($modx->getOption('moderate',$scriptProperties,false)) {
    /* by default moderate, unless special cases pass */
    $approved = false;

    /* check logged in status */
    if ($modx->user->hasSessionContext($modx->context->get('key'))) {
        /* if moderating only anonymous users, go ahead and approve since the user is logged in */
        if ($modx->getOption('moderateAnonymousOnly',$scriptProperties,false)) {
            $approved = true;

        } else if ($modx->getOption('moderateFirstPostOnly',$scriptProperties,true)) {
            /* if moderating only first post, check to see if user has posted and been approved elsewhere.
             * Note that this only works with logged in users.
             */
            $ct = $modx->getCount('quipComment',array(
                'author' => $modx->user->get('id'),
                'approved' => true,
            ));
            if ($ct > 0) $approved = true;
        }
    }
    $comment->set('approved',$approved);
}

/* set body of comment */
$comment->set('body',$body);

/* URL preservation information
 * @deprecated 0.5.0, this now goes on the Thread, will remove code in 0.5.1
 */
if (!empty($_POST['parent'])) {
    /* for threaded comments, persist the parents URL */
    $parentComment = $modx->getObject('quipComment',$_POST['parent']);
    if ($parentComment) {
        $comment->set('resource',$parentComment->get('resource'));
        $comment->set('idprefix',$parentComment->get('idprefix'));
        $comment->set('existing_params',$parentComment->get('existing_params'));
    }
} else {
    $comment->set('resource',$modx->getOption('resource',$scriptProperties,$modx->resource->get('id')));
    $comment->set('idprefix',$modx->getOption('idPrefix',$scriptProperties,'qcom'));

    /* save existing parameters to comment to preserve URLs */
    $p = $modx->request->getParameters();
    unset($p['reported']);
    $comment->set('existing_params',$p);
}

if ($comment->save() == false) {
    $errors['message'] = $modx->lexicon('quip.comment_err_save');
} elseif ($requireAuth) {
    /* if successful and requireAuth is true, update user profile */
    $profile = $modx->user->getOne('Profile');
    if ($profile) {
        if (!empty($_POST['name'])) $profile->set('fullname',$_POST['name']);
        if (!empty($_POST['email'])) $profile->set('email',$_POST['email']);
        $profile->set('website',$_POST['website']);
        $profile->save();
    }
}

/* if comment is approved, send emails */
if ($comment->get('approved')) {
    $thread = $comment->getOne('Thread');
    if ($thread) {
        if ($thread->notify($comment) == false) {
            $modx->log(modX::LOG_LEVEL_ERROR,'[Quip] Notifications could not be sent for comment: '.print_r($comment->toArray(),true));
        }
    } else {
        $modx->log(modX::LOG_LEVEL_ERROR,'[Quip] Thread not found for comment: '.print_r($comment->toArray(),true));
    }
} else {
    if (!$comment->notifyModerators()) {
        $modx->log(modX::LOG_LEVEL_ERROR,'[Quip] Moderator Notifications could not be sent for comment: '.print_r($comment->toArray(),true));
    }
}

/* if notify is set to true, add user to quipCommentNotify table */
if (!empty($_POST['notify'])) {
    $quipCommentNotify = $modx->getObject('quipCommentNotify',array(
        'thread' => $comment->get('thread'),
        'email' => $_POST['email'],
    ));
    if (empty($quipCommentNotify)) {
        $quipCommentNotify = $modx->newObject('quipCommentNotify');
        $quipCommentNotify->set('thread',$comment->get('thread'));
        $quipCommentNotify->set('email',$_POST['email']);
        $quipCommentNotify->save();
    }
}

return $comment;