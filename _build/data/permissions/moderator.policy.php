<?php
/**
 * The default Permission scheme for the Resource Policy.
 *
 * @package modx
 */
$permissions = array();
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'quip.comment_approve',
    'description' => 'perm.comment_approve',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'quip.comment_list',
    'description' => 'perm.comment_list',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'quip.comment_list_unapproved',
    'description' => 'perm.comment_list_unapproved',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'quip.comment_remove',
    'description' => 'perm.comment_remove',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'quip.comment_update',
    'description' => 'perm.comment_update',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'quip.thread_list',
    'description' => 'perm.thread_list',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'quip.thread_manage',
    'description' => 'perm.thread_manage',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'quip.thread_truncate',
    'description' => 'perm.thread_truncate',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'quip.thread_update',
    'description' => 'perm.thread_update',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'quip.thread_view',
    'description' => 'perm.thread_view',
    'value' => true,
));
return $permissions;