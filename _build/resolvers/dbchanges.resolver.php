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
 * Resolves db changes
 *
 * @package quip
 * @subpackage build
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('quip.core_path',null,$modx->getOption('core_path').'components/quip/').'model/';
            $modx->addPackage('quip',$modelPath);

            /* add name,email,website fields */
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD `name` VARCHAR(255) NOT NULL default ''");
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD `email` VARCHAR(255) NOT NULL default ''");
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD `website` VARCHAR(255) NOT NULL default ''");

            /* make sure approved default is 1 */
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} CHANGE `approved` `approved` TINYINT(1) UNSIGNED NOT NULL DEFAULT  '1'");

            break;
    }
}
return true;