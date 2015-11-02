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
 * Translated by Jakub Kalina <kuba.kalina@gmail.com>
 */
/**
 * @package quip
 * @subpackage lexicon
 */
$_lang['quip.email_comment_approved'] = '<p>Witaj [[+name]],</p>

<p>Twój komentarz został zatwierdzony. Możesz zobaczyć go tutaj:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>Pozdrawiamy,<br />
<em>Quip</em></p>';
$_lang['quip.email_comment_approved_subject'] = '[Quip] Twój komentarz został zatwierdzony';
$_lang['quip.email_notify'] = '<p>Witaj,</p>

<p>Nowy komentarz użytkownika [[+name]] został dodany. Możesz zobaczyć go tutaj:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p>To jest wiadomość wygenerowana automatycznie. Proszę nie odpowiadać na nią bezpośrednio. ID
komentarza to: <strong>[[+id]]</strong> w wątku "[[+thread]]".</p>

[[+unsubscribeText]]

<p>
Pozdrawiamy,<br />
<em>Quip</em></p>';
$_lang['quip.email_notify_subject'] = '[Quip] Nowa odpowiedź';
$_lang['quip.email_moderate'] = '<p>Witaj,</p>

<p>Nowy komentarz użytkownika [[+name]] oczekuje na moderację. Możesz zobaczyć go tutaj:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p><a href="[[+approveUrl]]">Zatwierdź komentarz</a> | <a href="[[+rejectUrl]]">Usuń komentarz</a> | <a href="[[+unapprovedUrl]]">Przeglądaj niezatwierdzone komentarze</a></p>

<p>To jest wiadomość wygenerowana automatycznie. Proszę nie odpowiadać na nią bezpośrednio. ID
komentarza to: <strong>[[+id]]</strong> w wątku "[[+thread]]".</p>

<p>
Pozdrawiamy,<br />
<em>Quip</em></p>';
$_lang['quip.email_moderate_subject'] = '[Quip] Nowy wpis oczekuje na moderację';
$_lang['quip.unsubscribe_text'] = '<p>Jeśli chcesz zrezygnować z otrzymywania takich wiadomości, kliknij:</p>

<p><a href="[[+unsubscribeUrl]]">[[+unsubscribeUrl]]</a></p>';