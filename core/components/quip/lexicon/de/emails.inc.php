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
 * Translated by Dennis "DenSchub" Schubert <software@dsx.cc>
 *
 * @package quip
 * @subpackage lexicon
 */
$_lang['quip.email_comment_approved'] = '<p>Hallo [[+name]],</p>

<p>Ihr Kommentar wurde genehmigt. Sie können ihn hier anschauen:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>Danke,<br />
<em>Quip</em></p>';
$_lang['quip.email_comment_approved_subject'] = '[Quip] Ihr Kommentar wurde genehmigt';
$_lang['quip.email_notify'] = '<p>Hallo,</p>

<p>Ein neuer Kommentar wurde von [[+name]] geschrieben:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p>Dies ist eine automatisch generierte E-Mail. Bitte antworten Sie nicht direkt darauf. Die
ID des Kommentars ist <strong>[[+id]]</strong> im Thread "[[+thread]]".</p>

<p>
Danke,<br />
<em>Quip</em></p>';
$_lang['quip.email_notify_subject'] = '[Quip] Neue Antwort wurde geschrieben';
$_lang['quip.email_moderate'] = '<p>Hallo,</p>

<p>Ein neuer Kommentar von [[+name]] wartet auf Moderation:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p><a href="[[+approveUrl]]">Kommentar genehmigen</a> | <a href="[[+rejectUrl]]">Kommentar löschen</a> | <a href="[[+unapprovedUrl]]">Ungenehmigte Kommentare</a></p>

<p>Dies ist eine automatisch generierte E-Mail. Bitte antworten Sie nicht direkt darauf. Die
ID des Kommentars ist <strong>[[+id]]</strong> im Thread "[[+thread]]".</p>

<p>
Danke,<br />
<em>Quip</em></p>';
$_lang['quip.email_moderate_subject'] = '[Quip] Neuer Kommentar wartet auf Moderation';
$_lang['quip.unsubscribe_text'] = '<p>Wenn Sie diese E-Mails nicht mehr empfangen möchten, klicken Sie bitte hier:</p>

<p><a href="[[+unsubscribeUrl]]">[[+unsubscribeUrl]]</a></p>';