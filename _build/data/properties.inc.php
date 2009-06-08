<?php
/**
 * Quip
 *
 * Copyright 2009 by Shaun McCormick <shaun@collabpad.com>
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
 * Default snippet properties
 *
 * @package quip
 * @subpackage build
 */
$properties = array(
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
    array(
        'name' => 'tplquipAddComment',
        'desc' => 'The add comment form. Can either be a chunk name or value. If set to a value, will override the chunk.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'tplquipComment',
        'desc' => 'The comment itself. Can either be a chunk name or value. If set to a value, will override the chunk.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'tplquipCommentOptions',
        'desc' => 'The options, such as delete, shown to an owner of a comment. Can either be a chunk name or value. If set to a value, will override the chunk.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'tplquipComments',
        'desc' => 'The outer wrapper for comments. Can either be a chunk name or value. If set to a value, will override the chunk.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'tplquipLoginToComment',
        'desc' => 'The portion to show when the user is not logged in. Can either be a chunk name or value. If set to a value, will override the chunk.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'tplquipReport',
        'desc' => 'The link on a comment to report as spam. Can either be a chunk name or value. If set to a value, will override the chunk.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
);
return $properties;