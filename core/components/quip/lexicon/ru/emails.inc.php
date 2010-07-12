<?php
/**
 * @package quip
 * @subpackage lexicon
 */
$_lang['quip.email_comment_approved'] = '<p>Здравствуйте [[+name]],</p>

<p>Ваш комментарий был одобрен. Вы можете увидеть его здесь:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>Спасибо,<br />
<em>Quip</em></p>';
$_lang['quip.email_comment_approved_subject'] = '[Quip] Ваш комментарий был одобрен';
$_lang['quip.email_notify'] = '<p>Здравствуйте,</p>

<p>Пользователь[[+name]] добавил новый комментарий:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p>Это письмо сгенерировано автоматически. Пожалуйста не отвечайте на него. Идентификатор комментария: <strong>[[+id]]</strong> в теме "[[+thread]]".</p>

<p>
Спасибо,<br />
<em>Quip</em></p>';
$_lang['quip.email_notify_subject'] = '[Quip] Добавлен новый комментарий';
$_lang['quip.email_moderate'] = '<p>Здравствуйте,</p>

<p>Новый комментарий [[+name]] нуждается в модерации. Комментарий был добавлен к:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p><a href="[[+approveUrl]]">Approve Comment</a> | <a href="[[+rejectUrl]]">Delete Comment</a> | <a href="[[+unapprovedUrl]]">Unapproved Comments</a></p>

<p>Это письмо сгенерировано автоматически. Пожалуйста не отвечайте на него. Идентификатор комментария: <strong>[[+id]]</strong> в теме "[[+thread]]".</p>

<p>
Спасибо,<br />
<em>Quip</em></p>';
$_lang['quip.email_moderate_subject'] = '[Quip] Новый комментарий нуждается в модерации';