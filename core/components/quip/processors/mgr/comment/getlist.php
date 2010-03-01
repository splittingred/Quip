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
 * Get a list of comments for a thread
 *
 * @package quip
 * @subpackage processors
 */
$limit = isset($_REQUEST['limit']);
$combo = isset($_REQUEST['combo']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'createdon';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'DESC';

$c = $modx->newQuery('quipComment');
$c->leftJoin('modUser','Author');
$c->where(array(
    'thread' => $_REQUEST['thread'],
));
$count = $modx->getCount('quipComment',$c);

$c->select('quipComment.*,
    Author.username AS username
');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($combo || $limit) {
    $c->limit($_REQUEST['limit'], $_REQUEST['start']);
}
$comments = $modx->getCollection('quipComment', $c);

$list = array();
foreach ($comments as $comment) {
    $la = $comment->toArray();
    $la['body'] = htmlentities($la['body']);

    $la['menu'] = array(
        array(
            'text' => $modx->lexicon('quip.comment_update'),
            'handler' => 'this.updateComment',
        ),
        '-',
        array(
            'text' => $modx->lexicon('quip.comment_remove'),
            'handler' => 'this.removeComment',
        ),
    );
    $list[]= $la;
}
return $this->outputArray($list,$count);