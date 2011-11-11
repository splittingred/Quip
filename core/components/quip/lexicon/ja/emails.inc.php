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
$_lang['quip.email_comment_approved'] = '<p>[[+name]] 様</p> 

<p>下記のコメントが承認されました。</p> 

<p><a href="[[+url]]">[[+url]]</a></p> 

'; 
$_lang['quip.email_comment_approved_subject'] = 'コメントが承認されました'; 
$_lang['quip.email_notify'] = '<p>Hello,</p> 

<p>[[+name]] 様によるコメントが下記に投稿されました。</p> 

<p><a href="[[+url]]">[[+url]]</a></p> 

<p>----------------------------------------------------</p> 

<p>[[+body]]</p> 

<p>----------------------------------------------------</p> 

<p>このメールは自動送信です。直接返信しないでください。 
コメントIDは [[+thread]] スレッドの [[+id]] です。</p> 
'; 
$_lang['quip.email_notify_subject'] = '新規コメントが投稿されました'; 
$_lang['quip.email_moderate'] = '<p>こんにちは，</p> 

<p>[[+name]] 様による新しいコメントが下記に投稿され，モデレート待ちです。</p> 

<p><a href="[[+url]]">[[+url]]</a></p> 

<p>----------------------------------------------------</p> 

<p>[[+body]]</p> 

<p>----------------------------------------------------</p> 

<p><a href="[[+approveUrl]]">コメントを承認</a> | <a href="[[+rejectUrl]]">コメントを削除</a> | <a href="[[+unapprovedUrl]]">コメントを非承認</a></p> 

<p>このメールは自動送信です。直接返信しないでください。 
コメントIDは [[+thread]] スレッドの [[+id]] です。</p> 
'; 
$_lang['quip.email_moderate_subject'] = '新規投稿がモデレーション待ちです';