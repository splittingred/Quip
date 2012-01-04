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
 * Get a list of threads
 *
 * @package quip
 * @subpackage processors
 */
class QuipThreadGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'quipThread';
    public $primaryKeyField = 'name';
    public $objectType = 'quip.thread';
    public $checkListPermission = false;
    public $languageTopics = array('quip:default');

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('modResource','Resource');

        $search = $this->getProperty('search');
        if ($search) {
            $c->where(array(
                'quipThread.name:LIKE' => '%'.$search.'%',
                'OR:quipThread.moderator_group:LIKE' => '%'.$search.'%',
                'OR:Resource.pagetitle:LIKE' => '%'.$search.'%',
            ),null,2);
        }
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        /* get approved comments sql */
        $subc = $this->modx->newQuery('quipComment');
        $subc->setClassAlias('ApprovedComments');
        $subc->select('COUNT(*)');
        $subc->where(array(
            'quipThread.name = ApprovedComments.thread',
            'ApprovedComments.deleted' => 0,
            'ApprovedComments.approved' => 1,
        ));
        $subc->prepare(); $approvedCommentsSql = $subc->toSql();

        /* get unapproved comments sql */
        $subc = $this->modx->newQuery('quipComment');
        $subc->setClassAlias('ApprovedComments');
        $subc->select('COUNT(*)');
        $subc->where(array(
            'quipThread.name = ApprovedComments.thread',
            'ApprovedComments.deleted' => 0,
            'ApprovedComments.approved' => 0,
        ));
        $subc->prepare(); $unapprovedCommentsSql = $subc->toSql();

        $c->select($this->modx->getSelectColumns('quipThread','quipThread'));
        $c->select(array(
            'Resource.pagetitle',
            'Resource.context_key',
            '('.$approvedCommentsSql.') AS comments',
            '('.$unapprovedCommentsSql.') AS unapproved_comments',
        ));
        return $c;
    }

    /**
     * @param xPDOObject|quipThread $object
     * @return boolean
     */
    public function prepareRow(xPDOObject $object) {
        if (!$object->checkPolicy('view')) return false;
        $threadArray = $object->toArray();
        $resourceTitle = $object->get('pagetitle');
        if (!empty($resourceTitle)) {
            $threadArray['url'] = $object->makeUrl();
        }

        $cls = '';
        $cls .= $object->checkPolicy('truncate') ? ' truncate' : '';
        $cls .= $object->checkPolicy('remove') ? ' remove' : '';
        $threadArray['perm'] = $cls;

        return $threadArray;
    }
}
return 'QuipThreadGetListProcessor';