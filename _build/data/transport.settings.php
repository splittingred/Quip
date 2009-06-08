<?php
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

return $settings;