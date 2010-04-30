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
 * Quip English language file
 *
 * @package quip
 * @subpackage lexicon
 */
$_lang['quip'] = 'Quip';
$_lang['quip_desc'] = 'A simple commenting component.';
$_lang['quip.ago'] = ' ago';
$_lang['quip.ago_days'] = '[[+days]] days';
$_lang['quip.ago_hours'] = '[[+hours]] hrs';
$_lang['quip.ago_minutes'] = '[[+minutes]] min';
$_lang['quip.ago_seconds'] = '[[+seconds]] sec';
$_lang['quip.allowed_tags'] = 'Allowed tags: [[+tags]]';
$_lang['quip.author'] = 'Author';
$_lang['quip.back_to_threads'] = 'Back to Thread Listing';
$_lang['quip.body'] = 'Body';
$_lang['quip.close'] = 'Close';
$_lang['quip.comment'] = 'Comment';
$_lang['quip.comment_add_new'] = 'Add a new comment:';
$_lang['quip.comment_err_nf'] = 'Comment not found.';
$_lang['quip.comment_err_ns'] = 'Comment not specified.';
$_lang['quip.comment_err_remove'] = 'An error occurred while trying to remove the comment.';
$_lang['quip.comment_err_save'] = 'An error occurred while trying to save the comment.';
$_lang['quip.comment_remove'] = 'Remove Comment';
$_lang['quip.comment_remove_confirm'] = 'Are you sure you want to remove this comment?';
$_lang['quip.comment_update'] = 'Update Comment';
$_lang['quip.comments'] = 'Comments';
$_lang['quip.email'] = 'Email';
$_lang['quip.email_err_ns'] = 'Please specify an email address.';
$_lang['quip.err_fraud_attempt'] = 'Error: IDs of users do not match. Fraud attempt detected.';
$_lang['quip.err_not_logged_in'] = 'You are not logged in and therefore are not authorized to post comments.';
$_lang['quip.intro_msg'] = 'Manage your Quip comments and threads here.';
$_lang['quip.login_to_comment'] = 'Please login to comment.';
$_lang['quip.message_err_ns'] = 'Please specify a message to post.';
$_lang['quip.name'] = 'Name';
$_lang['quip.name_err_ns'] = 'Please specify a name.';
$_lang['quip.no_email_to_specified'] = 'Quip cannot send a spam report email because no admin email was specified.';
$_lang['quip.notify_email'] = '<p>Hello,</p>

<p>A new comment by [[+username]] has been posted at:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p>This is an automated email. Please do not reply directly. The
comment\'s ID is: <strong>[[+id]]</strong></p>

<p>
Thanks,<br />
<em>Quip</em>
</p>';
$_lang['quip.notify_me'] = 'Notify of New Replies';
$_lang['quip.notify_subject'] = '[Quip] New Reply Posted';
$_lang['quip.post'] = 'Post';
$_lang['quip.postedon'] = 'Posted On';
$_lang['quip.preview'] = 'Preview';
$_lang['quip.recaptcha_err_load'] = 'Could not load reCaptcha service class.';
$_lang['quip.reply'] = 'Reply';
$_lang['quip.remove'] = 'Remove';
$_lang['quip.report_as_spam'] = 'Report as Spam';
$_lang['quip.reported_as_spam'] = 'Reported as Spam';
$_lang['quip.sfs_err_load'] = 'Could not load StopForumSpam class.';
$_lang['quip.spam_blocked'] = 'Your submission was blocked by a spam filter: [[+fields]]';
$_lang['quip.spam_email'] = '<p>Hello,</p>

<p>A comment by [[+username]] has been reported as spam at:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>This is an automated email. Please do not reply directly. The
comment\'s ID is: <strong>[[+id]]</strong></p>

<p>
Thanks,<br />
<em>Quip</em>
</p>';
$_lang['quip.spam_email_subject'] = '[Quip] Comment Spam Reported';
$_lang['quip.spam_marked'] = ' - marked as spam.';
$_lang['quip.thread'] = 'Thread';
$_lang['quip.thread_err_ns'] = 'No thread specified.';
$_lang['quip.thread_manage'] = 'Manage Thread';
$_lang['quip.thread_truncate'] = 'Truncate Thread';
$_lang['quip.thread_truncate_confirm'] = 'Are you sure you want to remove all comments in this thread?';
$_lang['quip.threads'] = 'Threads';
$_lang['quip.username_said'] = '<strong>[[+username]]</strong> said:';
$_lang['quip.website'] = 'Website';


$_lang['setting_quip.allowedTags'] = 'Allowed Tags';
$_lang['setting_quip.allowedTags_desc'] = 'The tags allowed by users in comment posting. See <a target="_blank" href="http://php.net/strip_tags">php.net/strip_tags</a> for a list of acceptable values.';
$_lang['setting_quip.emailsFrom'] = 'From Email';
$_lang['setting_quip.emailsFrom_desc'] = 'The email address to send system emails from.';
$_lang['setting_quip.emailsTo'] = 'To Email';
$_lang['setting_quip.emailsTo_desc'] = 'The email address to send system emails to.';
$_lang['setting_quip.emailsReplyTo'] = 'Reply-To Email';
$_lang['setting_quip.emailsReplyTo_desc'] = 'The email address to set the reply-to to. Defaults to emailFrom.';