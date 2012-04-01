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
$_lang['quip.email_comment_approved'] = '<p>Hallo [[+name]],</p>

<p>Uw reactie is goedgekeurd. Bekijk uw reactie hier:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>Bedankt,<br />
<em>Quip</em></p>';
$_lang['quip.email_comment_approved_subject'] = '[Quip] Uw reactie is goedgekeurd';
$_lang['quip.email_notify'] = '<p>Hallo,</p>

<p>Een nieuwe reactie van [[+name]] is geplaats op:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p>Dit is een automatiche email. Reageer a.u.b. niet direct. Het ID van de reageerder is: <strong>[[+id]]</strong> op de thread: "[[+thread]]".</p>

[[+unsubscribeText]]

<p>
Bedankt,<br />
<em>Quip</em></p>';
$_lang['quip.email_notify_subject'] = '[Quip] Nieuwe reactie geplaatst';
$_lang['quip.email_moderate'] = '<p>Hallo,</p>

<p>Een nieuwe reactie van [[+name]] moet beoordeeld worden. Het is geplaatst op:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p><a href="[[+approveUrl]]">Reactie goedkeuren</a> | <a href="[[+rejectUrl]]">Verwijder reactie</a> | <a href="[[+unapprovedUrl]]">Reactie afwijzen</a></p>

<p>Dit is een automatiche email. Reageer a.u.b. niet direct. Het ID van de reageerder is: <strong>[[+id]]</strong> op de thread: "[[+thread]]".</p>

<p>
Bedankt,<br />
<em>Quip</em></p>';
$_lang['quip.email_moderate_subject'] = '[Quip] Nieuwe reactie in afwachting van goedkeuring';
$_lang['quip.unsubscribe_text'] = '<p>Als u deze mails niet wilt ontvangen, klik dan hier:</p>

<p><a href="[[+unsubscribeUrl]]">[[+unsubscribeUrl]]</a></p>';