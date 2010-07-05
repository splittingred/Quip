<?php
/**
 * Quip
 *
 * Copyright 2010 by Shaun McCormick <shaun@modxcms.com>
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
        'desc' => 'quip.prop_count_type_desc',
        'type' => 'list',
        'options' => array(
            array('text' => 'quip.thread','value' => 'thread'),
            array('text' => 'quip.user','value' => 'user'),
        ),
        'value' => 'thread',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'thread',
        'desc' => 'quip.prop_count_thread_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'user',
        'desc' => 'quip.prop_count_user_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'quip:properties',
    ),
);
return $properties;