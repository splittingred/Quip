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
 * Get a list of notifications for a thread
 *
 * @package quip
 * @subpackage processors
 */
class QuipThreadNotificationGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'quipCommentNotify';
    public $objectType = 'quip.notification';
    public $languageTopics = array('quip:default');
    public $defaultSortField = 'thread';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('quipThread','Thread');

        $c->where(array(
            'quipCommentNotify.thread:=' => $this->getProperty('thread'),
        ));

        $search = $this->getProperty('search');
        if ($search) {
            $c->where(array(
                'quipCommentNotify.email:LIKE' => '%'.$search.'%',
            ),null,2);
        }
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->select($this->modx->getSelectColumns('quipCommentNotify','quipCommentNotify'));
        $c->select(array(
            'Thread.notify_emails',
        ));
        return $c;
    }

    /**
     * @param xPDOObject|quipCommentNotify $object
     * @return boolean
     */
    public function prepareRow(xPDOObject $object) {
        $notifyArray = $object->toArray();
        $notifyArray['cls'] = '';
        $notifyArray['createdon'] = !empty($notifyArray['createdon']) ? strftime('%b %d, %Y %H:%M %p',strtotime($notifyArray['createdon'])) : '';
        return $notifyArray;
    }
}
return 'QuipThreadNotificationGetListProcessor';