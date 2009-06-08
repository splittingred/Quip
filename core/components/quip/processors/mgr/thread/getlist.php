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
 * Get a list of threads
 *
 * @package quip
 * @subpackage processors
 */
$limit = isset($_REQUEST['limit']);
$combo = isset($_REQUEST['combo']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('quipComment');
if ($combo || $limit) {
    $c->limit($_REQUEST['limit'], $_REQUEST['start']);
}
$c->groupby('thread');
$c->sortby('thread','ASC');
$count = $modx->getCount('quipComment',$c);
$c->select('quipComment.*,
    (SELECT COUNT(*) FROM '.$modx->getTableName('quipComment').'
     WHERE thread = quipComment.thread) AS comments
');
$threads = $modx->getCollection('quipComment', $c);

$list = array();
foreach ($threads as $thread) {
    $la = $thread->toArray();

    $la['menu'] = array(
        array(
            'text' => $modx->lexicon('quip.thread_manage'),
            'handler' => 'this.manageThread',
        ),
        '-',
        array(
            'text' => $modx->lexicon('quip.thread_truncate'),
            'handler' => 'this.truncateThread',
        )
    );
    $list[]= $la;
}
return $this->outputArray($list,$count);