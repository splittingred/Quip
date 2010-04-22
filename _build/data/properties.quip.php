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
 * Default snippet properties for Quip
 *
 * @package quip
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'requireAuth',
        'desc' => 'If set to true, only logged-in users can post comments.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    ),
    array(
        'name' => 'closed',
        'desc' => 'If set to true, the thread will not accept new comments.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    ),
    array(
        'name' => 'dateFormat',
        'desc' => 'The format of the dates displayed for a comment.',
        'type' => 'textfield',
        'options' => '',
        'value' => '%b %d, %Y at %I:%M %p',
    ),
    array(
        'name' => 'useCss',
        'desc' => 'If true, Quip will provide a basic CSS template for the presentation.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'altRowCss',
        'desc' => 'The CSS class to put on alternating comments.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quip-comment-alt',
    ),
    array(
        'name' => 'notifyEmails',
        'desc' => 'A comma-separated list of email addresses to send a notification email to when a new post is made on this thread.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'recaptcha',
        'desc' => 'If true, will enable reCaptcha support.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    ),
    array(
        'name' => 'sortBy',
        'desc' => 'The field to sort by.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'createdon',
    ),
    array(
        'name' => 'sortByAlias',
        'desc' => 'The alias of classes to use with sort by.',
        'type' => 'list',
        'options' => array(
            array('text' => 'Comment','value' => 'quipComment'),
            array('text' => 'Author','value' => 'Author'),
        ),
        'value' => 'quipComment',
    ),
    array(
        'name' => 'sortDir',
        'desc' => 'The direction to sort by.',
        'type' => 'list',
        'options' => array(
            array('text' => 'ASC','value' => 'ASC'),
            array('text' => 'DESC','value' => 'DESC'),
        ),
        'value' => 'DESC',
    ),
    array(
        'name' => 'tplAddComment',
        'desc' => 'The add comment form. Can either be a chunk name or value. If set to a value, will override the chunk.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quipAddComment',
    ),
    array(
        'name' => 'tplComment',
        'desc' => 'The comment itself. Can either be a chunk name or value. If set to a value, will override the chunk.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quipComment',
    ),
    array(
        'name' => 'tplCommentOptions',
        'desc' => 'The options, such as delete, shown to an owner of a comment. Can either be a chunk name or value. If set to a value, will override the chunk.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quipCommentOptions',
    ),
    array(
        'name' => 'tplComments',
        'desc' => 'The outer wrapper for comments. Can either be a chunk name or value. If set to a value, will override the chunk.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quipComments',
    ),
    array(
        'name' => 'tplLoginToComment',
        'desc' => 'The portion to show when the user is not logged in. Can either be a chunk name or value. If set to a value, will override the chunk.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quipLoginToComment',
    ),
    array(
        'name' => 'tplPreview',
        'desc' => 'The tpl for the preview text. Can either be a chunk name or value. If set to a value, will override the chunk.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quipPreviewComment',
    ),
    array(
        'name' => 'tplReport',
        'desc' => 'The link on a comment to report as spam. Can either be a chunk name or value. If set to a value, will override the chunk.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quipReport',
    ),
    array(
        'name' => 'postAction',
        'desc' => 'The name of the submit field to initiate a comment post.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quip-post',
    ),
    array(
        'name' => 'previewAction',
        'desc' => 'The name of the submit field to initiate a comment preview.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quip-preview',
    ),
    array(
        'name' => 'reportAction',
        'desc' => 'The name of the submit field to initiate a comment report as spam.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quip-report',
    ),
    array(
        'name' => 'removeAction',
        'desc' => 'The name of the submit field to initiate a comment remove.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quip-remove',
    ),
    array(
        'name' => 'debug',
        'desc' => 'Set to true to turn on debug mode. Not recommended for production sites.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    ),
    array(
        'name' => 'debugUser',
        'desc' => 'If debug is on, will set the username of $modx->user to this value.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'debugUserId',
        'desc' => 'If debug is on, will set the id of $modx->user to this value.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
);
return $properties;