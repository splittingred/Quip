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
 * Default snippet properties for QuipLatestComments
 *
 * @package quip
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'type',
        'desc' => 'quip.prop_late_type_desc',
        'type' => 'list',
        'options' => array(
            array('name' => 'quip.all','value' => 'all'),
            array('name' => 'quip.thread','value' => 'thread'),
            array('name' => 'quip.user','value' => 'user'),
        ),
        'value' => 'all',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'tpl',
        'desc' => 'quip.prop_late_tpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quipLatestComment',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'thread',
        'desc' => 'quip.prop_late_thread_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'user',
        'desc' => 'quip.prop_late_user_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'stripTags',
        'desc' => 'quip.prop_late_striptags_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'dateFormat',
        'desc' => 'quip.prop_late_dateformat_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '%b %d, %Y at %I:%M %p',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'bodyLimit',
        'desc' => 'quip.prop_late_bodylimit_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 30,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'rowCss',
        'desc' => 'quip.prop_late_rowcss_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quip-latest-comment',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'altRowCss',
        'desc' => 'quip.prop_late_altrowcss_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quip-latest-comment-alt',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'limit',
        'desc' => 'quip.prop_late_limit_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 5,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'start',
        'desc' => 'quip.prop_late_start_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 0,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'sortBy',
        'desc' => 'quip.prop_late_sortby_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'createdon',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'sortByAlias',
        'desc' => 'quip.prop_late_sortbyalias_desc',
        'type' => 'list',
        'options' => array(
            array('text' => 'quip.comment','value' => 'quipComment'),
            array('text' => 'quip.author','value' => 'Author'),
        ),
        'value' => 'quipComment',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'sortDir',
        'desc' => 'quip.prop_late_sortdir_desc',
        'type' => 'list',
        'options' => array(
            array('text' => 'quip.ascending','value' => 'ASC'),
            array('text' => 'quip.descending','value' => 'DESC'),
        ),
        'value' => 'DESC',
        'lexicon' => 'quip:properties',
    ),
);
return $properties;