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
 * Create a comment
 * 
 * @var Quip $quip
 * @var modX $modx
 * @var array $scriptProperties
 * @var QuipThreadReplyController $this
 *
 * @package quip
 * @subpackage processors
 */
$errors = array();

/* if using reCaptcha */
$disableRecaptchaWhenLoggedIn = $this->getProperty('disableRecaptchaWhenLoggedIn',true);
if ($this->getProperty('recaptcha',false) && !($disableRecaptchaWhenLoggedIn && $this->hasAuth)) {
    if (isset($scriptProperties['auth_nonce']) && !empty($scriptProperties['preview_mode'])) {
        /* if already passed reCaptcha via preview mode, verify nonce to prevent spam/fraud attacks */
        $passedNonce = $quip->checkNonce($scriptProperties['auth_nonce'],'quip-form-');
        if (!$passedNonce) {
            $errors['comment'] = $modx->lexicon('quip.err_fraud_attempt');
            return $errors;
        }
    } else {
        /* otherwise validate reCaptcha */
        /** @var reCaptcha $recaptcha */
        $recaptcha = $modx->getService('recaptcha','reCaptcha',$quip->config['modelPath'].'recaptcha/');
        if (!($recaptcha instanceof reCaptcha)) {
            $errors['recaptcha'] = $modx->lexicon('quip.recaptcha_err_load');
        } elseif (empty($recaptcha->config[reCaptcha::OPT_PRIVATE_KEY])) {
            $errors['recaptcha'] = $modx->lexicon('recaptcha.no_api_key');
        } else {
            $response = $recaptcha->checkAnswer($_SERVER['REMOTE_ADDR'],$scriptProperties['recaptcha_challenge_field'],$scriptProperties['recaptcha_response_field']);

            if (!$response->is_valid) {
                $errors['recaptcha'] = $modx->lexicon('recaptcha.incorrect',array(
                    'error' => $response->error != 'incorrect-captcha-sol' ? $response->error : '',
                ));
            }
        }
    }
}

/* verify against spam */
if ($modx->loadClass('stopforumspam.StopForumSpam',$quip->config['modelPath'],true,true)) {
    $sfspam = new StopForumSpam($modx);
    $spamResult = $sfspam->check($_SERVER['REMOTE_ADDR'],$scriptProperties['email']);
    if (!empty($spamResult)) {
        $spamFields = implode($modx->lexicon('quip.spam_marked')."\n<br />",$spamResult);
        $errors['email'] = $modx->lexicon('quip.spam_blocked',array(
            'fields' => $spamFields,
        ));
    }
} else {
    $modx->log(modX::LOG_LEVEL_ERROR,'[Quip] Couldnt load StopForumSpam class.');
}

/* cleanse body from XSS and other junk */
$scriptProperties['body'] = $quip->cleanse($scriptProperties['comment'],$scriptProperties);
$scriptProperties['body'] = $quip->parseLinks($scriptProperties['body'],$scriptProperties);
if (empty($scriptProperties['body'])) $errors['comment'] = $modx->lexicon('quip.message_err_ns');
$scriptProperties['body'] = str_replace(array('<br><br>','<br /><br />'),'',nl2br($scriptProperties['body']));

/* run preHooks */
$quip->loadHooks('pre');
$quip->preHooks->loadMultiple($this->getProperty('preHooks',''),$scriptProperties,array(
    'hooks' => $this->getProperty('preHooks',''),
));

/* if a prehook sets a field, do so here */
$fs = $quip->preHooks->getFields();
if (!empty($fs)) {
    /* better handling of checkbox values when input name is an array[] */
    foreach ($fs as $f => $v) {
        if (is_array($v)) { implode(',',$v); }
        $fs[$f] = $v;
    }
    /* assign new fields values */
    $scriptProperties = $quip->preHooks->getFields();
}
/* if any errors in preHooks */
if (!empty($quip->preHooks->errors)) {
    foreach ($quip->preHooks->errors as $key => $error) {
        $errors[$key] = $error;
    }
}

/* if no errors, save comment */
if (!empty($errors)) return $errors;

/** @var quipComment $comment */
$comment = $modx->newObject('quipComment');
$comment->fromArray($scriptProperties);
$comment->set('ip',$_SERVER['REMOTE_ADDR']);
$comment->set('createdon',strftime('%b %d, %Y at %I:%M %p',time()));
$comment->set('body',$scriptProperties['body']);

/* if moderation is on, don't auto-approve comments */
if ($this->getProperty('moderate',false)) {
    /* by default moderate, unless special cases pass */
    $approved = false;

    /* never moderate mgr users */
    if ($modx->user->hasSessionContext('mgr') && $this->getProperty('dontModerateManagerUsers',true)) {
        $approved = true;
    /* check logged in status in current context*/
    } else if ($modx->user->hasSessionContext($modx->context->get('key'))) {
        /* if moderating only anonymous users, go ahead and approve since the user is logged in */
        if ($this->getProperty('moderateAnonymousOnly',false)) {
            $approved = true;

        } else if ($this->getProperty('moderateFirstPostOnly',true)) {
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

/* URL preservation information
 * @deprecated 0.5.0, this now goes on the Thread, will remove code in 0.5.1
 */
if (!empty($scriptProperties['parent'])) {
    /* for threaded comments, persist the parents URL */
    /** @var quipComment $parentComment */
    $parentComment = $modx->getObject('quipComment',$scriptProperties['parent']);
    if ($parentComment) {
        $comment->set('resource',$parentComment->get('resource'));
        $comment->set('idprefix',$parentComment->get('idprefix'));
        $comment->set('existing_params',$parentComment->get('existing_params'));
    }
} else {
    $comment->set('resource',$this->getProperty('resource',$modx->resource->get('id')));
    $comment->set('idprefix',$this->getProperty('idPrefix','qcom'));

    /* save existing parameters to comment to preserve URLs */
    $p = $modx->request->getParameters();
    unset($p['reported']);
    $comment->set('existing_params',$p);
}

/* ensure author is set */
if ($this->hasAuth) {
    $comment->set('author', $modx->user->get('id'));
}


/* save comment */
if ($comment->save() == false) {
    $errors['message'] = $modx->lexicon('quip.comment_err_save');
    return $errors;
} elseif ($this->getProperty('requireAuth',false)) {
    /* if successful and requireAuth is true, update user profile */
    $profile = $modx->user->getOne('Profile');
    if ($profile) {
        if (!empty($scriptProperties['name'])) $profile->set('fullname',$scriptProperties['name']);
        if (!empty($scriptProperties['email'])) $profile->set('email',$scriptProperties['email']);
        $profile->set('website',$scriptProperties['website']);
        $profile->save();
    }
}

/* if comment is approved, send emails */
if ($comment->get('approved')) {
    /** @var quipThread $thread */
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
if (!empty($scriptProperties['notify'])) {
    /** @var quipCommentNotify $quipCommentNotify */
    $quipCommentNotify = $modx->getObject('quipCommentNotify',array(
        'thread' => $comment->get('thread'),
        'email' => $scriptProperties['email'],
    ));
    if (empty($quipCommentNotify)) {
        $quipCommentNotify = $modx->newObject('quipCommentNotify');
        $quipCommentNotify->set('thread',$comment->get('thread'));
        $quipCommentNotify->set('email',$scriptProperties['email']);
        $quipCommentNotify->save();
    }
}

/* run postHooks */
$commentArray = $comment->toArray();
$quip->loadHooks('post');
$quip->postHooks->loadMultiple($this->getProperty('postHooks',''),$commentArray,array(
    'hooks' => $this->getProperty('postHooks',''),
));

return $comment;