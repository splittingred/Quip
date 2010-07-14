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

            /* add resource mapping changes */
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD `resource` INT(10) unsigned NOT NULL default '0'");
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD `idprefix` VARCHAR(255) NOT NULL default 'qcom'");
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD `existing_params` TEXT");
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD INDEX `resource` (`resource`)");

            /* add threaded changes */
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD `ip` VARCHAR(255) NOT NULL default '0.0.0.0' AFTER `website`");
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD `rank` TINYTEXT AFTER `parent`");

            /* add approval/deleted changes */
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD `approvedby` INT(10) unsigned NOT NULL default '0' AFTER `approvedon`");
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD `deleted` TINYINT(1) unsigned NOT NULL default '0' AFTER `ip`");
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD `deletedon` DATETIME AFTER `deleted`");
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD `deletedby` INT(10) unsigned NOT NULL default '0' AFTER `deletedon`");
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD INDEX `approvedby` (`approvedby`)");
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD INDEX `deleted` (`deleted`)");
            $modx->exec("ALTER TABLE {$modx->getTableName('quipComment')} ADD INDEX `deletedby` (`deletedby`)");

            /* add call_params to quipThread */
            $modx->exec("ALTER TABLE {$modx->getTableName('quipThread')} ADD `quip_call_params` TEXT AFTER `existing_params`");
            $modx->exec("ALTER TABLE {$modx->getTableName('quipThread')} ADD `quipreply_call_params` TEXT AFTER `quip_call_params`");

            /* create thread objects for comments if they dont exist */
            $c = $modx->newQuery('quipComment');
            $c->sortby('createdon','DESC');
            $comments = $modx->getCollection('quipComment',$c);
            foreach ($comments as $comment) {
                $thread = $comment->getOne('Thread');
                if (empty($thread)) {
                    $thread = $modx->newObject('quipThread');
                    $thread->set('name',$comment->get('thread'));
                    $thread->set('idprefix',$comment->get('idprefix'));
                    $thread->set('existing_params',$comment->get('existing_params'));
                    $thread->set('resource',$comment->get('resource'));
                    $thread->set('createdon',$comment->get('createdon'));
                    $thread->set('moderator_group','Administrator');
                    $thread->save();
                }
                unset($thread);
            }
            unset($comments,$comment,$c);

            break;
    }
}
return true;