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
    'Comment' => 
    array (
      'class' => 'quipComment',
      'local' => 'thread',
      'foreign' => 'thread',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
