<?php
/**
 * Report a comment
 *
 * @package quip
 * @subpackage processors
 */
if (!isset($_POST['id']) || $_POST['id'] == '') {
    return $modx->error->failure($modx->lexicon('quip.comment_err_ns'));
}
$c = $modx->newQuery('quipComment');
$c->leftJoin('modUser','Author');
$c->select('quipComment.*,
    Author.username AS username
');
$c->where(array(
    'id' => $_POST['id'],
));
$comment = $modx->getObject('quipComment',$c);
if ($comment == null) {
    return $modx->error->failure($modx->lexicon('quip.comment_err_nf'));
}


$emailTo = $modx->getOption('quip.emailsTo',null,$modx->getOption('emailsender'));
if (empty($emailTo)) {
    return $modx->error->failure($modx->lexicon('quip.no_email_to_specified'));
}

$properties = $comment->toArray(true);
$properties['url'] = $_POST['url'];
$body = $modx->lexicon('quip.spam_email',$properties);

$modx->getService('mail', 'mail.modPHPMailer');

$emailFrom = $modx->getOption('quip.emailsFrom',null,$emailTo);
$emailReplyTo = $modx->getOption('quip.emailsReplyTo',null,$emailFrom);

$modx->mail->set(MODX_MAIL_BODY, $body);
$modx->mail->set(MODX_MAIL_FROM, $emailFrom);
$modx->mail->set(MODX_MAIL_FROM_NAME, 'Quip');
$modx->mail->set(MODX_MAIL_SENDER, 'Quip');
$modx->mail->set(MODX_MAIL_SUBJECT, $modx->lexicon('quip.spam_email_subject'));
$modx->mail->address('to',$emailTo);
$modx->mail->address('reply-to',$emailReplyTo);
$modx->mail->setHTML(true);
if (!$modx->mail->send()) {
    return $modx->error->failure($modx->lexicon('error_sending_email_to').$emailTo);
}
$modx->mail->reset();

return $modx->error->success();