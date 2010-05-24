<?php
/**
 * @package quip
 * @subpackage lexicon
 */
$_lang['quip.email_comment_approved'] = '<p>Hello [[+name]],</p>

<p>Your comment has been approved. You can view it here:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>Thanks,<br />
<em>Quip</em></p>';
$_lang['quip.email_comment_approved_subject'] = '[Quip] Your Comment Has Been Approved';
$_lang['quip.email_notify'] = '<p>Hello,</p>

<p>A new comment by [[+name]] has been posted at:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p>This is an automated email. Please do not reply directly. The
comment\'s ID is: <strong>[[+id]]</strong> on the thread "[[+thread]]".</p>

<p>
Thanks,<br />
<em>Quip</em></p>';
$_lang['quip.email_notify_subject'] = '[Quip] New Reply Posted';
$_lang['quip.email_moderate'] = '<p>Hello,</p>

<p>A new comment by [[+name]] is in need of moderation. It has been posted at:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p>This is an automated email. Please do not reply directly. The
comment\'s ID is: <strong>[[+id]]</strong> on the thread "[[+thread]]".</p>

<p>
Thanks,<br />
<em>Quip</em></p>';
$_lang['quip.email_moderate_subject'] = '[Quip] New Post in Need of Moderation';