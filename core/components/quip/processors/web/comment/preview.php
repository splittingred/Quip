<?php
/**
 * Preview a comment
 *
 * @var Quip $quip
 * @var modX $modx
 * @var array $fields
 * @var QuipThreadReplyController $this
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
if ($this->getProperty('requireAuth',false) && !$this->hasAuth) {
    $errors['comment'] = $modx->lexicon('quip.err_not_logged_in');
    return $errors;
}

/* if using reCaptcha */
$disableRecaptchaWhenLoggedIn = $this->getProperty('disableRecaptchaWhenLoggedIn',true,'isset');
if ($this->getProperty('recaptcha',false) && !($disableRecaptchaWhenLoggedIn && $this->hasAuth)) {
    /* prevent having to do recaptcha more than once */
    $passedNonce = false;
    if (!empty($fields['auth_nonce'])) {
        $passedNonce = $quip->checkNonce($fields['auth_nonce'],'quip-form-');
    }
    if (!$passedNonce) {
        /** @var reCaptcha $recaptcha */
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
        'createdon' => strftime($this->getProperty('dateFormat'),time()),
    ));
    if ($this->getProperty('useGravatar',true)) {
        $preview['md5email'] = md5($fields['email']);
        $preview['gravatarIcon'] = $this->getProperty('gravatarIcon','identicon');
        $preview['gravatarSize'] = $this->getProperty('gravatarSize','50');
        $urlsep = $modx->getOption('xhtml_urls',$this->getProperties(),true) ? '&amp;' : '&';
        $gravatarUrl = $this->getProperty('gravatarUrl','http://www.gravatar.com/avatar/');
        $preview['gravatarUrl'] = $gravatarUrl.$preview['md5email'].'?s='.$preview['gravatarSize'].$urlsep.'d='.$preview['gravatarIcon'];
    }
    if (!$modx->user->hasSessionContext($modx->context->get('key')) && !$this->getProperty('requireAuth',false)) {
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
    $preview['comment'] = $quip->parseLinks($preview['comment'],$this->getProperties());
    $this->setPlaceholder('preview',$quip->getChunk($this->getProperty('tplPreview'),$preview));
    $this->setPlaceholder('can_post',true);
    $hasPreview = true;

    /* make nonce value to prevent middleman/spam/hijack attacks */
    $nonce = $quip->createNonce('quip-form-');
}

$this->setPlaceholders($fields);
if (!empty($nonce)) {
    $this->setPlaceholder('auth_nonce',$nonce);
}
if (!empty($hasPreview)) {
    $this->setPlaceholder('preview_mode',1);
}
$this->setPlaceholder('comment',$body);

return $errors;