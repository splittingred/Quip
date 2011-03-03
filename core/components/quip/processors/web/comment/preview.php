<?php
/**
 * Preview a comment
 *
 * @package quip
 * @subpackage processors
 */
$errors = array();
/* make sure not empty */
if (empty($fields['comment'])) $errors['comment'] = $modx->lexicon('quip.message_err_ns');

/* verify against spam */
if ($modx->loadClass('stopforumspam.StopForumSpam',$quip->config['modelPath'],true,true)) {
    $sfspam = new StopForumSpam($modx);
    $spamResult = $sfspam->check($_SERVER['REMOTE_ADDR'],$fields['email']);
    if (!empty($spamResult)) {
        $spamFields = implode($modx->lexicon('quip.spam_marked')."\n<br />",$spamResult);
        $errors['email'] = $modx->lexicon('quip.spam_blocked',array(
            'fields' => $spamFields,
        ));
    }
} else {
    $modx->log(modX::LOG_LEVEL_ERROR,'[Quip] '.$modx->lexicon('quip.sfs_err_load'));
}

/* if requireAuth */
if ($requireAuth && !$hasAuth) {
    $errors['comment'] = $modx->lexicon('quip.err_not_logged_in');
    return $errors;
}

/* if using reCaptcha */
$disableRecaptchaWhenLoggedIn = $modx->getOption('disableRecaptchaWhenLoggedIn',$scriptProperties,true);
if ($modx->getOption('recaptcha',$scriptProperties,false) && !($disableRecaptchaWhenLoggedIn && $hasAuth)) {
    /* prevent having to do recaptcha more than once */
    $passedNonce = false;
    if (!empty($fields['auth_nonce'])) {
        $passedNonce = $quip->checkNonce($fields['auth_nonce'],'quip-form-');
    }
    if (!$passedNonce) {
        $recaptcha = $modx->getService('recaptcha','reCaptcha',$quip->config['modelPath'].'recaptcha/');
        if (!($recaptcha instanceof reCaptcha)) {
            $errors['recaptcha'] = $modx->lexicon('quip.recaptcha_err_load');
        } elseif (empty($recaptcha->config[reCaptcha::OPT_PRIVATE_KEY])) {
            $errors['recaptcha'] = $modx->lexicon('recaptcha.no_api_key');
        } else {
            $response = $recaptcha->checkAnswer($_SERVER['REMOTE_ADDR'],$fields['recaptcha_challenge_field'],$fields['recaptcha_response_field']);

            if (!$response->is_valid) {
                $errors['recaptcha'] = $modx->lexicon('recaptcha.incorrect',array(
                    'error' => $response->error != 'incorrect-captcha-sol' ? $response->error : '',
                ));
            }
        }
    }
}

/* strip tags */
$body = $quip->cleanse($fields['comment']);
$formattedBody = nl2br($body);

/* if no errors, add preview field */
if (empty($errors)) {
    $preview = array_merge($fields,array(
        'body' => $body,
        'comment' => $formattedBody,
        'createdon' => strftime($dateFormat,time()),
    ));
    if ($modx->getOption('useGravatar',$scriptProperties,true)) {
        $preview['md5email'] = md5($fields['email']);
        $preview['gravatarIcon'] = $modx->getOption('gravatarIcon',$scriptProperties,'identicon');
        $preview['gravatarSize'] = $modx->getOption('gravatarSize',$scriptProperties,'50');
        $urlsep = $modx->getOption('xhtml_urls',$scriptProperties,true) ? '&amp;' : '&';
        $gravatarUrl = $modx->getOption('gravatarUrl',$scriptProperties,'http://www.gravatar.com/avatar/');
        $preview['gravatarUrl'] = $gravatarUrl.$preview['md5email'].'?s='.$preview['gravatarSize'].$urlsep.'d='.$preview['gravatarIcon'];
    }
    if (!$modx->user->hasSessionContext($modx->context->get('key')) && !$requireAuth) {
        $preview['author'] = 0;
    } else {
        $preview['author'] = $modx->user->get('id');
    }
    if (!empty($preview['website'])) {
        if (strpos($preview['website'],'http://') !== 0 && strpos($preview['website'],'https://') !== 0) {
            $preview['website'] = substr($preview['website'],strpos($preview['website'],'//'));
            $preview['website'] = 'http://'.$preview['website'];
        }
        $preview['username'] = '<a href="'.$preview['website'].'">'.$preview['name'].'</a>';
    } else {
        $preview['username'] = $preview['name'];
    }
    $placeholders['preview'] = $quip->getChunk($previewTpl,$preview);
    $placeholders['can_post'] = true;
    $hasPreview = true;
    
    /* make nonce value to prevent middleman/spam/hijack attacks */
    $nonce = $quip->createNonce('quip-form-');
}

$placeholders = array_merge($placeholders,$fields);
if (!empty($nonce)) {
    $placeholders['auth_nonce'] = $nonce;
}
if (!empty($hasPreview)) {
    $placeholders['preview_mode'] = 1;
}
$placeholders['comment'] = $body;


return $errors;