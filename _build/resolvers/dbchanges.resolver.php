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
 * Resolves db changes
 *
 * @var xPDOObject $object
 * @var array $options
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

            $manager = $modx->getManager();

            $manager->addField('quipComment','name');
            $manager->addField('quipComment','email');
            $manager->addField('quipComment','website');

            /* alter approved field */
            $manager->alterField('quipComment','approved');

            /* add resource mapping changes */
            $manager->addField('quipComment','resource');
            $manager->addField('quipComment','idprefix');
            $manager->addField('quipComment','existing_params');
            $manager->addIndex('quipComment','resource');

            /* add threaded changes */
            $manager->addField('quipComment','ip',array('after' => 'website'));
            $manager->addField('quipComment','rank',array('after' => 'parent'));

            /* add approval/deleted changes */
            $manager->addField('quipComment','approvedby',array('after' => 'approvedon'));
            $manager->addField('quipComment','deleted',array('after' => 'ip'));
            $manager->addField('quipComment','deletedon',array('after' => 'deleted'));
            $manager->addField('quipComment','deletedby',array('after' => 'deletedon'));
            $manager->addIndex('quipComment','approvedby');
            $manager->addIndex('quipComment','deleted');
            $manager->addIndex('quipComment','deletedby');

            /* add call_params to quipThread */
            $manager->addField('quipThread','quip_call_params');
            $manager->addField('quipThread','quipreply_call_params');

            /* create thread objects for comments if they dont exist */
            $c = $modx->newQuery('quipComment');
            $c->sortby('createdon','DESC');
            $comments = $modx->getCollection('quipComment',$c);
            /** @var quipComment $comment */
            foreach ($comments as $comment) {
                $thread = $comment->getOne('Thread');
                /** @var quipThread $thread */
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

            /* add createdon, user to quipNotifyComment */
            $manager->addField('quipNotifyComment','createdon');
            $manager->addField('quipNotifyComment','user');

            break;
    }
}
return true;