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
 * Default snippet properties for QuipLatestComments
 *
 * @package quip
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'type',
        'desc' => 'Whether to grab a list from all comments, per thread, or per user.',
        'type' => 'list',
        'options' => array(
            array('text' => 'All','value' => 'all'),
            array('text' => 'Thread','value' => 'thread'),
            array('text' => 'User','value' => 'user'),
        ),
        'value' => 'thread',
    ),
    array(
        'name' => 'tpl',
        'desc' => 'The chunk tpl to use for each row.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quipLatestComment',
    ),
    array(
        'name' => 'thread',
        'desc' => 'The thread ID to pull from. Only if type is set to Thread.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'user',
        'desc' => 'The User ID or username to pull from. Only if type is set to User.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'stripTags',
        'desc' => 'If set to true, tags will be stripped from the body text.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'dateFormat',
        'desc' => 'The format of the dates displayed for a comment.',
        'type' => 'textfield',
        'options' => '',
        'value' => '%b %d, %Y at %I:%M %p',
    ),
    array(
        'name' => 'bodyLimit',
        'desc' => 'The number of characters to limit the body field in the comment display to before adding an ellipsis.',
        'type' => 'textfield',
        'options' => '',
        'value' => 30,
    ),
    array(
        'name' => 'rowCss',
        'desc' => 'The CSS class to put on each row.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quip-latest-comment',
    ),
    array(
        'name' => 'altRowCss',
        'desc' => 'The CSS class to put on alternating comments.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quip-latest-comment-alt',
    ),
    array(
        'name' => 'limit',
        'desc' => 'The number of comments to pull.',
        'type' => 'textfield',
        'options' => '',
        'value' => 5,
    ),
    array(
        'name' => 'start',
        'desc' => 'The start index of comments to pull from.',
        'type' => 'textfield',
        'options' => '',
        'value' => 0,
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
);
return $properties;