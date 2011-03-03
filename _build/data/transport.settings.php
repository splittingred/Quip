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

$settings['quip.emails_from_name']= $modx->newObject('modSystemSetting');
$settings['quip.emails_from_name']->fromArray(array(
    'key' => 'quip.emails_from_name',
    'value' => 'Quip',
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

$settings['recaptcha.public_key']= $modx->newObject('modSystemSetting');
$settings['recaptcha.public_key']->fromArray(array(
    'key' => 'recaptcha.public_key',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'recaptcha',
    'area' => 'reCaptcha',
),'',true,true);

$settings['recaptcha.private_key']= $modx->newObject('modSystemSetting');
$settings['recaptcha.private_key']->fromArray(array(
    'key' => 'recaptcha.private_key',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'recaptcha',
    'area' => 'reCaptcha',
),'',true,true);

$settings['recaptcha.use_ssl']= $modx->newObject('modSystemSetting');
$settings['recaptcha.use_ssl']->fromArray(array(
    'key' => 'recaptcha.use_ssl',
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'recaptcha',
    'area' => 'reCaptcha',
),'',true,true);



return $settings;