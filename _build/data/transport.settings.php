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
 * @package quip
 * @subpackage build
 */
$settings = array();
$settings['quip.emailsFrom']= $modx->newObject('modSystemSetting');
$settings['quip.emailsFrom']->fromArray(array(
    'key' => 'quip.emailsFrom',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'quip',
    'area' => 'email',
),'',true,true);

$settings['quip.emailsTo']= $modx->newObject('modSystemSetting');
$settings['quip.emailsTo']->fromArray(array(
    'key' => 'quip.emailsTo',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'quip',
    'area' => 'email',
),'',true,true);

$settings['quip.emailsReplyTo']= $modx->newObject('modSystemSetting');
$settings['quip.emailsReplyTo']->fromArray(array(
    'key' => 'quip.emailsReplyTo',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'quip',
    'area' => 'email',
),'',true,true);

$settings['quip.allowed_tags']= $modx->newObject('modSystemSetting');
$settings['quip.allowed_tags']->fromArray(array(
    'key' => 'quip.allowed_tags',
    'value' => '<b><i><br>',
    'xtype' => 'textfield',
    'namespace' => 'quip',
    'area' => 'Tags',
),'',true,true);

return $settings;