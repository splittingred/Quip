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
*  Italian translation
 * @package quip
 * @subpackage lexicon
 */
$_lang['quip.email_comment_approved'] = '<p>Salve [[+name]],</p>

<p>il tuo Commento &egrave; stato approvato. Puoi visionarlo qui:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>Grazie,<br />
<em>Quip</em></p>';
$_lang['quip.email_comment_approved_subject'] = '[Quip] Il tuo Commento è stato approvato';
$_lang['quip.email_notify'] = '<p>Salve,</p>

<p>Un Nuovo Commento di [[+name]] &egrave; stato postato in:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p>Mail di risposta automatica, NON RISPONDERE DIRETTAMENTE ALLA MAIL. Il codice 
ID del commento &egrave;: <strong>[[+id]]</strong> relativo alla discussione con codice "[[+thread]]".</p>

<p>
Grazie,<br />
<em>Quip</em></p>';
$_lang['quip.email_notify_subject'] = '[Quip] Nuova Risposta Inserita';
$_lang['quip.email_moderate'] = '<p>Salve,</p>

<p>un nuovo Commento di [[+name]] deve essere moderato. Lo puoi vedere in:</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p><a href="[[+approveUrl]]">Approva Commento</a> | <a href="[[+rejectUrl]]">Cancella Commento</a> | <a href="[[+unapprovedUrl]]">NON Approvare Commento</a></p>

<p>Mail di risposta automatica, NON RISPONDERE ALLA MAIL. Il
codice ID del commento &egrave;: <strong>[[+id]]</strong> relativo alla discussione con codice "[[+thread]]".</p>

<p>
Grazie,<br />
<em>Quip</em></p>';
$_lang['quip.email_moderate_subject'] = '[Quip] Nuovo messaggio da Moderare';
$_lang['quip.unsubscribe_text'] = '<p>Se vuoi disiscriverti da queste emails, clicca qui:</p>

<p><a href="[[+unsubscribeUrl]]">[[+unsubscribeUrl]]</a></p>';