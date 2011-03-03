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
$_lang['quip.email_comment_approved'] = '<p>Bonjour [[+name]],</p>

<p>Votre commentaire a été accepté. Vous pouvez le voir ici :</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>Merci,<br />
<em>Quip</em></p>';
$_lang['quip.email_comment_approved_subject'] = '[Quip] Votre commentaire a été accepté';
$_lang['quip.email_notify'] = '<p>Bonjour,</p>

<p>Un nouveau commentaire de [[+name]] a été posté ici :</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p>Ceci est un email automatique. Veuillez ne pas y répondre directement. 
Le numéro du commentaire est : <strong>[[+id]]</strong> dans le sujet "[[+thread]]".</p>

<p>
Merci,<br />
<em>Quip</em></p>';
$_lang['quip.email_notify_subject'] = '[Quip] Nouvelle réponse postée';
$_lang['quip.email_moderate'] = '<p>Bonjour,</p>

<p>Un nouveau commentaire de [[+name]] est en attente de modération. Vous pouvez le voir ici :</p>

<p><a href="[[+url]]">[[+url]]</a></p>

<p>----------------------------------------------------</p>

<p>[[+body]]</p>

<p>----------------------------------------------------</p>

<p><a href="[[+approveUrl]]">Accepter le commentaire</a> | <a href="[[+rejectUrl]]">Supprimer le commentaire</a> | <a href="[[+unapprovedUrl]]">Commentaire non approuvés</a></p>

<p>Ceci est un email automatique. Veuillez ne pas y répondre directement. 
Le numéro du commentaire est : <strong>[[+id]]</strong> dans le sujet "[[+thread]]".</p>

<p>
Merci,<br />
<em>Quip</em></p>';
$_lang['quip.email_moderate_subject'] = '[Quip] Nouveau commentaire en attente de modération';