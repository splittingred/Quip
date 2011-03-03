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
if (!$modx->hasPermission('quip.thread_list')) return $modx->error->failure($modx->lexicon('access_denied'));

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$isCombo = !empty($scriptProperties['combo']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* build query */
$c = $modx->newQuery('quipThread');
$c->leftJoin('modResource','Resource');
$count = $modx->getCount('quipThread',$c);

if ($isCombo || $isLimit) {
    $c->limit($limit,$start);
}
$c->sortby($sort,$dir);

/* get approved comments sql */
$subc = $modx->newQuery('quipComment');
$subc->setClassAlias('ApprovedComments');
$subc->select('COUNT(*)');
$subc->where('`quipThread`.`name` = `ApprovedComments`.`thread`');
$subc->where(array(
    'ApprovedComments.deleted' => 0,
    'ApprovedComments.approved' => 1,
));
$subc->prepare(); $approvedCommentsSql = $subc->toSql();

/* get unapproved comments sql */
$subc = $modx->newQuery('quipComment');
$subc->setClassAlias('ApprovedComments');
$subc->select('COUNT(*)');
$subc->where('`quipThread`.`name` = `ApprovedComments`.`thread`');
$subc->where(array(
    'ApprovedComments.deleted' => 0,
    'ApprovedComments.approved' => 0,
));
$subc->prepare(); $unapprovedCommentsSql = $subc->toSql();

$c->select(array(
    'quipThread.*',
    'Resource.pagetitle',
));
$c->select('
    ('.$approvedCommentsSql.') AS `comments`,
    ('.$unapprovedCommentsSql.') AS `unapproved_comments`
');
$threads = $modx->getCollection('quipThread', $c);

$list = array();
foreach ($threads as $thread) {
    if (!$thread->checkPolicy('view')) continue;
    $threadArray = $thread->toArray();
    $threadArray['url'] = $thread->makeUrl();

    $cls = '';
    $cls .= $thread->checkPolicy('truncate') ? ' ptruncate' : '';
    $cls .= $thread->checkPolicy('remove') ? ' premove' : '';
    $threadArray['perm'] = $cls;

    $list[]= $threadArray;
}
return $this->outputArray($list,$count);