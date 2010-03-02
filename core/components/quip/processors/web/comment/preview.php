<?php
/**
 * Preview a comment
 *
 * @package quip
 * @subpackage processors
 */
$errors = array();

/* make sure not empty */
if (empty($_POST['comment'])) $errors[] = $modx->lexicon('quip.message_err_ns');

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
    $modx->log(modX::LOG_LEVEL_ERROR,'[Quip] '.$modx->lexicon('quip.sfs_err_load'));
}

if ($requireAuth) {
    if ($modx->user->hasSessionContext($modx->context->get('key'))) {
        $errors['message'] = $modx->lexicon('quip.err_not_logged_in');
        return $errors;
    }
}

/* strip tags */
$body = $_POST['comment'];
$body = preg_replace("/<script(.*)<\/script>/i",'',$body);
$body = preg_replace("/<iframe(.*)<\/iframe>/i",'',$body);
$body = preg_replace("/<iframe(.*)\/>/i",'',$body);
$body = strip_tags($body,$allowedTags);

/* if no errors, add preview field */
if (empty($errors)) {
    $preview = array_merge($_POST,array(
        'comment' => $body,
        'createdon' => strftime($dateFormat,time()),
    ));
    if (!$requireAuth) {
        $preview['username'] = $_POST['name'];
        $preview['author'] = 0;
    } else {
        $profile = $modx->user->getOne('Profile');
        if ($profile) {
            $preview['username'] = $profile->get('fullname');
        } else {
            $preview['username'] = $modx->user->get('username');
        }
        $preview['author'] = $modx->user->get('id');
    }
    if (!empty($_POST['website'])) {
        $preview['username'] = '<a href="'.$_POST['website'].'">'.$preview['username'].'</a>';
    }
    $placeholders['preview'] = $quip->getChunk($previewTpl,$preview);
} else {
    $placeholders['error'] = implode("<br />\n",$errors);
}

$placeholders = array_merge($placeholders,$_POST);
$placeholders['comment'] = $body;


return $errors;