<?php
/**
 * Quip
 *
 * Copyright 2009 by Shaun McCormick <shaun@collabpad.com>
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
 * Quip Connector
 *
 * @package quip
 */
/* Load custom Quip defines and modx object */
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

require_once MODX_CORE_PATH.'components/quip/model/quip/quip.class.php';
$modx->quip = new Quip($modx);

if (isset($_REQUEST['resource'])) {
    $modx->resource = $modx->getObject('modResource',$_REQUEST['resource']);
}

/* handle request */
$path = $modx->getOption('processors_path',$modx->quip->config,$modx->getOption('core_path').'components/quip/processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));