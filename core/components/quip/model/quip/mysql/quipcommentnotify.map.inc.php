<?php
/**
 * @package quip
 */
$xpdo_meta_map['quipCommentNotify']= array (
  'package' => 'quip',
  'table' => 'quip_comment_notify',
  'fields' => 
  array (
    'thread' => '',
    'email' => '',
  ),
  'fieldMeta' => 
  array (
    'thread' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'email' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'aggregates' => 
  array (
    'Thread' => 
    array (
      'class' => 'quipThread',
      'local' => 'thread',
      'foreign' => 'name',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Comments' => 
    array (
      'class' => 'quipComment',
      'local' => 'thread',
      'foreign' => 'thread',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
