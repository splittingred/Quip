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

if (empty($errors)) {
    $comment = $modx->newObject('quipComment');
    $comment->fromArray($_POST);
    $comment->set('body',$body);
    $comment->set('thread',$scriptProperties['thread']);
    $comment->set('createdon',strftime('%Y-%m-%d %H:%M:%S'));

    if ($comment->save() == false) {
        $errors['message'] = $modx->lexicon('quip.comment_err_save');
    } elseif ($requireAuth) {
        $profile = $modx->user->getOne('Profile');
        if ($profile) {
            $profile->set('fullname',$_POST['name']);
            $profile->set('email',$_POST['email']);
            $profile->set('website',$_POST['website']);
            $profile->save();
        }
    }
}
return $errors;