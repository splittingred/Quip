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
 * Default snippet properties for QuipCount
 *
 * @package quip
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'type',
        'desc' => 'If set to Thread, will count the # of comments in a thread. If set to User, will grab # of total comments by a User.',
        'type' => 'list',
        'options' => array(
            array('text' => 'Thread','value' => 'thread'),
            array('text' => 'User','value' => 'user'),
        ),
        'value' => 'thread',
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
);
return $properties;