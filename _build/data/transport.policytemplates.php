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
 * Default Quip Access Policy Templates
 *
 * @package quip
 * @subpackage build
 */
$templates = array();

/* administrator template/policy */
$templates['1']= $modx->newObject('modAccessPolicyTemplate');
$templates['1']->fromArray(array(
    'id' => 1,
    'name' => 'QuipModeratorPolicyTemplate',
    'description' => 'A policy for moderating Quip comments.',
    'lexicon' => 'quip:permissions',
    'template_group' => 1,
));
$permissions = include dirname(__FILE__).'/permissions/moderator.policy.php';
if (is_array($permissions)) {
    $templates['1']->addMany($permissions);
} else { $modx->log(modX::LOG_LEVEL_ERROR,'Could not load Quip Moderator Policy Template.'); }

return $templates;
