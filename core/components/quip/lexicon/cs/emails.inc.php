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
 * Translated by Jiri Pavlicek <jiri@pavlicek.cz>, www.pavlicek.cz
 * UTF-8 encoded
 *
 * @package quip
 * @subpackage lexicon
 */
$_lang['quip.email_comment_approved'] = '<p>Dobrý den [[+name]],</p>

<p>Váš komentář byl schválen. Můžete ho vidět zde:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>Děkuji,<br />
<em>Quip</em></p>';
$_lang['quip.email_comment_approved_subject'] = '[Quip] Váš komentář byl schválen';
$_lang['quip.email_notify'] = '<p>Dobrý den,</p>

<p>Nový komentář od [[+name]] byl vložen na:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p>Toto je automaticky generovaný e-mail. Prosím, neodpovídejte na něj. ID
komentáře je: <strong>[[+id]]</strong> ve vlákně "[[+thread]]".</p>

[[+unsubscribeText]]

<p>
Děkuji,<br />
<em>Quip</em></p>';
$_lang['quip.email_notify_subject'] = '[Quip] Nová odpověď vložena';
$_lang['quip.email_moderate'] = '<p>Dobrý den,</p>

<p>Nový komentář od [[+name]] potřebuje moderovat. Byl vložen na:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p><a href="[[+approveUrl]]">Schválit komentář</a> | <a href="[[+rejectUrl]]">Odstranit komentář</a> | <a href="[[+unapprovedUrl]]">Neschválit komentář</a></p>

<p>Toto je automaticky generovaný e-mail. Prosím, neodpovídejte na něj. ID
komentáře je: <strong>[[+id]]</strong> ve vlákně "[[+thread]]".</p>

<p>
Děkuji,<br />
<em>Quip</em></p>';
$_lang['quip.email_moderate_subject'] = '[Quip] Nový příspěvek vyžaduje moderovat';
$_lang['quip.unsubscribe_text'] = '<p>Pokud se chcete odhlásit z odběru těchto e-mailů, klikněte zde:</p>

<p><a href="[[+unsubscribeUrl]]">[[+unsubscribeUrl]]</a></p>';