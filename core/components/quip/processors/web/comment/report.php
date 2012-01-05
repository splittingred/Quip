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
 * Report a comment
 *
 * @var Quip $quip
 * @var modX $modx
 * @var array $fields
 * @var QuipThreadController $this
 *
 * @package quip
 * @subpackage processors
 */
$errors = array();
if (empty($_REQUEST['quip_comment'])) {
    $errors['message'] = $modx->lexicon('quip.comment_err_ns');
    return $errors;
}

/* get comment */
$c = $modx->newQuery('quipComment');
$c->leftJoin('modUser','Author');
$c->select($modx->getSelectColumns('quipComment','quipComment'));
$c->select($modx->getSelectColumns('modUser','Author','',array('username')));
$c->where(array(
    'id' => $_REQUEST['quip_comment'],
));
/** @var quipComment $comment */
$comment = $modx->getObject('quipComment',$c);
if ($comment == null) {
    $errors['message'] = $modx->lexicon('quip.comment_err_nf');
    return $errors;
}

$emailTo = $modx->getOption('quip.emailsTo',null,$modx->getOption('emailsender'));
if (empty($emailTo)) {
    $errors['message'] = $modx->lexicon('quip.no_email_to_specified');
    return $errors;
}

$properties = $comment->toArray();
$properties['url'] = $comment->makeUrl('','',array('scheme' => 'full'));
if (empty($properties['username'])) $properties['username'] = $comment->get('name');
$body = $modx->lexicon('quip.spam_email',$properties);

/* send spam report */
$modx->getService('mail', 'mail.modPHPMailer');
$emailFrom = $modx->getOption('quip.emailsFrom',null,$emailTo);
$emailReplyTo = $modx->getOption('quip.emailsReplyTo',null,$emailFrom);
$modx->mail->set(modMail::MAIL_BODY, $body);
$modx->mail->set(modMail::MAIL_FROM, $emailFrom);
$modx->mail->set(modMail::MAIL_FROM_NAME, 'Quip');
$modx->mail->set(modMail::MAIL_SENDER, 'Quip');
$modx->mail->set(modMail::MAIL_SUBJECT, $modx->lexicon('quip.spam_email_subject'));
$modx->mail->address('to',$emailTo);
$modx->mail->address('reply-to',$emailReplyTo);
$modx->mail->setHTML(true);
if (!$modx->mail->send()) {
    //$errors['message'] = $modx->lexicon('error_sending_email_to').': '.$emailTo;
}
$modx->mail->reset();

return $errors;