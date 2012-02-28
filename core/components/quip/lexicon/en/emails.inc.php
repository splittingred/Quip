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

[[+unsubscribeText]]

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

<p><a href="[[+approveUrl]]">Approve Comment</a> | <a href="[[+rejectUrl]]">Delete Comment</a> | <a href="[[+unapprovedUrl]]">Unapproved Comments</a></p>

<p>This is an automated email. Please do not reply directly. The
comment\'s ID is: <strong>[[+id]]</strong> on the thread "[[+thread]]".</p>

<p>
Thanks,<br />
<em>Quip</em></p>';
$_lang['quip.email_moderate_subject'] = '[Quip] New Post in Need of Moderation';
$_lang['quip.unsubscribe_text'] = '<p>If you would like to unsubscribe from these emails, please click here:</p>

<p><a href="[[+unsubscribeUrl]]">[[+unsubscribeUrl]]</a></p>';