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
 * Remove old files
 *
 * @package quip
 * @subpackage build
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $corePath = $modx->getOption('quip.core_path',null,$modx->getOption('core_path').'components/quip/');
            $modx->addPackage('quip',$corePath.'model/');

            $opts = array('deleteTop' => true,'skipDirs' => false,'extensions' => '*');
            $modx->cacheManager->deleteTree($corePath.'chunks/',$opts);
            $modx->cacheManager->deleteTree($corePath.'snippets/',$opts);
            break;
    }
}
return true;