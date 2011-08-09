<?php
/**
 * Preview a comment
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
/* make sure not empty */
if (empty($scriptProperties['comment'])) $errors['comment'] = $modx->lexicon('quip.message_err_ns');

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
    $modx->log(modX::LOG_LEVEL_ERROR,'[Quip] '.$modx->lexicon('quip.sfs_err_load'));
}

/* if requireAuth */
if ($this->getProperty('requireAuth',false) && !$this->hasAuth) {
    $errors['comment'] = $modx->lexicon('quip.err_not_logged_in');
    return $errors;
}

/* if using reCaptcha */
$disableRecaptchaWhenLoggedIn = $this->getProperty('disableRecaptchaWhenLoggedIn',true);
if ($modx->getOption('recaptcha',$scriptProperties,false) && !($disableRecaptchaWhenLoggedIn && $this->hasAuth)) {
    /* prevent having to do recaptcha more than once */
    $passedNonce = false;
    if (!empty($scriptProperties['auth_nonce'])) {
        $passedNonce = $quip->checkNonce($scriptProperties['auth_nonce'],'quip-form-');
    }
    if (!$passedNonce) {
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

/* strip tags */
$body = $quip->cleanse($scriptProperties['comment']);
$formattedBody = nl2br($body);

/* if no errors, add preview field */
if (empty($errors)) {
    $preview = array_merge($scriptProperties,array(
        'body' => $body,
        'comment' => $formattedBody,
        'createdon' => strftime($this->getProperty('dateFormat'),time()),
    ));
    if ($modx->getOption('useGravatar',$scriptProperties,true)) {
        $preview['md5email'] = md5($scriptProperties['email']);
        $preview['gravatarIcon'] = $modx->getOption('gravatarIcon',$scriptProperties,'identicon');
        $preview['gravatarSize'] = $modx->getOption('gravatarSize',$scriptProperties,'50');
        $urlsep = $modx->getOption('xhtml_urls',$scriptProperties,true) ? '&amp;' : '&';
        $gravatarUrl = $modx->getOption('gravatarUrl',$scriptProperties,'http://www.gravatar.com/avatar/');
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
    $preview['comment'] = $quip->parseLinks($preview['comment'],$scriptProperties);
    $this->setPlaceholder('preview',$quip->getChunk($this->getProperty('tplPreview'),$preview));
    $this->setPlaceholder('can_post',true);
    $hasPreview = true;

    /* make nonce value to prevent middleman/spam/hijack attacks */
    $nonce = $quip->createNonce('quip-form-');
}

$this->setPlaceholders($scriptProperties);
if (!empty($nonce)) {
    $this->setPlaceholder('auth_nonce',$nonce);
}
if (!empty($hasPreview)) {
    $this->setPlaceholder('preview_mode',1);
}
$this->setPlaceholder('comment',$body);

return $errors;