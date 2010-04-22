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
/* set default properties */
$isLimit = !empty($scriptProperties['limit']);
$isCombo = !empty($scriptProperties['combo']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'createdon');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');

/* build query */
$c = $modx->newQuery('quipComment');
$c->leftJoin('modUser','Author');
$c->where(array(
    'thread' => $scriptProperties['thread'],
));
$count = $modx->getCount('quipComment',$c);

$c->select('
    `quipComment`.*,
    `Author`.`username` AS `username`
');
$c->sortby($sort,$dir);
if ($isCombo || $isLimit) {
    $c->limit($limit,$start);
}
$comments = $modx->getCollection('quipComment', $c);

$list = array();
foreach ($comments as $comment) {
    $commentArray = $comment->toArray();
    if (empty($commentArray['username'])) $commentArray['username'] = $commentArray['name'];
    $commentArray['body'] = str_replace('<br />','',$commentArray['body']);

    $commentArray['menu'] = array(
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
    $list[]= $commentArray;
}
return $this->outputArray($list,$count);