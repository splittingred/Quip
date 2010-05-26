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

/* if requireAuth */
if ($requireAuth) {
    if (!$modx->user->hasSessionContext($modx->context->get('key'))) {
        $errors['message'] = $modx->lexicon('quip.err_not_logged_in');
        return $errors;
    }
}

/* if using reCaptcha */
if ($modx->getOption('recaptcha',$scriptProperties,false)) {
    $recaptcha = $modx->getService('recaptcha','reCaptcha',$quip->config['model_path'].'recaptcha/');
    if (!($recaptcha instanceof reCaptcha)) {
        $errors['recaptcha'] = $modx->lexicon('quip.recaptcha_err_load');
    } elseif (empty($recaptcha->config[reCaptcha::OPT_PRIVATE_KEY])) {
        $errors['recaptcha'] = $modx->lexicon('recaptcha.no_api_key');
    } else {
        $response = $recaptcha->checkAnswer($_SERVER['REMOTE_ADDR'],$_POST['recaptcha_challenge_field'],$_POST['recaptcha_response_field']);

        if (!$response->is_valid) {
            $errors['recaptcha'] = $modx->lexicon('recaptcha.incorrect',array(
                'error' => $response->error != 'incorrect-captcha-sol' ? $response->error : '',
            ));
        }
    }
}

/* strip tags */
$body = $_POST['comment'];
$body = preg_replace("/<script(.*)<\/script>/i",'',$body);
$body = preg_replace("/<iframe(.*)<\/iframe>/i",'',$body);
$body = preg_replace("/<iframe(.*)\/>/i",'',$body);
$body = strip_tags($body,$allowedTags);
/* replace MODx tags with entities */
$body = str_replace(array('[',']'),array('&#91;','&#93;'),$body);
$formattedBody = nl2br($body);

/* auto-convert links to tags */
if ($modx->getOption('autoConvertLinks',$scriptProperties,true)) {
    $pattern = "@\b(https?://)?(([0-9a-zA-Z_!~*'().&=+$%-]+:)?[0-9a-zA-Z_!~*'().&=+$%-]+\@)?(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-zA-Z_!~*'()-]+\.)*([0-9a-zA-Z][0-9a-zA-Z-]{0,61})?[0-9a-zA-Z]\.[a-zA-Z]{2,6})(:[0-9]{1,4})?((/[0-9a-zA-Z_!~*'().;?:\@&=+$,%#-]+)*/?)@";
    $formattedBody = preg_replace($pattern, '<a href="\0">\0</a>',$formattedBody);
}

/* if no errors, add preview field */
if (empty($errors)) {
    $preview = array_merge($_POST,array(
        'body' => $body,
        'comment' => $formattedBody,
        'createdon' => strftime('%b %d, %Y at %I:%M %p',time()),
    ));
    if ($modx->getOption('useGravatar',$scriptProperties,true)) {
        $preview['md5email'] = md5($_POST['email']);
        $preview['gravatarIcon'] = $modx->getOption('gravatarIcon',$scriptProperties,'identicon');
        $preview['gravatarSize'] = $modx->getOption('gravatarSize',$scriptProperties,'50');
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
} else {
    $placeholders['error'] = implode("<br />\n",$errors);
}

$placeholders = array_merge($placeholders,$_POST);
$placeholders['comment'] = $body;


return $errors;