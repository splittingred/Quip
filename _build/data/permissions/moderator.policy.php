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
 * The default Permission scheme for the QuipModeratorPolicy.
 *
 * @package quip
 * @subpackage build
 */
$permissions = array();
$permissions[] = $modx->newObject('modAccessPermission',array(
    'name' => 'quip.comment_approve',
    'description' => 'perm.comment_approve',
    'value' => true,
));
$permissions[] = $modx->newObject('modAccessPermission',array(
    'name' => 'quip.comment_list',
    'description' => 'perm.comment_list',
    'value' => true,
));
$permissions[] = $modx->newObject('modAccessPermission',array(
    'name' => 'quip.comment_list_unapproved',
    'description' => 'perm.comment_list_unapproved',
    'value' => true,
));
$permissions[] = $modx->newObject('modAccessPermission',array(
    'name' => 'quip.comment_remove',
    'description' => 'perm.comment_remove',
    'value' => true,
));
$permissions[] = $modx->newObject('modAccessPermission',array(
    'name' => 'quip.comment_update',
    'description' => 'perm.comment_update',
    'value' => true,
));
$permissions[] = $modx->newObject('modAccessPermission',array(
    'name' => 'quip.thread_list',
    'description' => 'perm.thread_list',
    'value' => true,
));
$permissions[] = $modx->newObject('modAccessPermission',array(
    'name' => 'quip.thread_manage',
    'description' => 'perm.thread_manage',
    'value' => true,
));
$permissions[] = $modx->newObject('modAccessPermission',array(
    'name' => 'quip.thread_remove',
    'description' => 'perm.thread_remove',
    'value' => true,
));
$permissions[] = $modx->newObject('modAccessPermission',array(
    'name' => 'quip.thread_truncate',
    'description' => 'perm.thread_truncate',
    'value' => true,
));
$permissions[] = $modx->newObject('modAccessPermission',array(
    'name' => 'quip.thread_update',
    'description' => 'perm.thread_update',
    'value' => true,
));
$permissions[] = $modx->newObject('modAccessPermission',array(
    'name' => 'quip.thread_view',
    'description' => 'perm.thread_view',
    'value' => true,
));
return $permissions;