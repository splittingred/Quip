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
 * Get a list of comments for a thread
 *
 * @package quip
 * @subpackage processors
 */
if (!$modx->hasPermission('quip.comment_list')) return $modx->error->failure($modx->lexicon('access_denied'));

/* set default properties */
$isLimit = !empty($scriptProperties['limit']);
$isCombo = !empty($scriptProperties['combo']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'createdon');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$deleted = $modx->getOption('deleted',$scriptProperties,0);

if (!empty($scriptProperties['thread'])) {
    $thread = $modx->getObject('quipThread',$scriptProperties['thread']);
    if (empty($thread)) return $modx->error->failure($modx->lexicon('quip.thread_err_nf'));
    if (!$thread->checkPolicy('view')) return $modx->error->failure($modx->lexicon('access_denied'));
}

$ugns = $modx->user->getUserGroupNames();
$userGroupNames = '';
foreach ($ugns as $ugn) {
    $userGroupNames .= '"'.$ugn.'",';
}
$userGroupNames = rtrim($userGroupNames,',');

/* build query */
$c = $modx->newQuery('quipComment');
$c->leftJoin('modUser','Author');
$c->leftJoin('modResource','Resource');
$c->innerJoin('quipThread','Thread');
$c->where(array(
    'quipComment.deleted' => $deleted,
    'quipComment.approved' => false,
));
/* handle moderator permissions */
$c->andCondition(array('(
    `Thread`.`moderated` = 0
        OR `Thread`.`moderator_group` IN ('.$userGroupNames.')
        OR "'.$modx->user->get('username').'" IN (`Thread`.`moderators`)
)'));
if (!empty($scriptProperties['thread'])) {
    $c->where(array(
        'quipComment.thread' => $scriptProperties['thread'],
    ));
}
$count = $modx->getCount('quipComment',$c);

/* get comments sql */
$subc = $modx->newQuery('quipComment');
$subc->setClassAlias('ct');
$subc->select('COUNT(`ct`.`id`)');
$subc->where('`ct`.`thread` = `quipComment`.`thread`');
$subc->where(array(
    'ct.deleted' => 0,
    'ct.approved' => 1,
));
$subc->prepare();
$commentsSql = $subc->toSql();

$c->select(array('quipComment.*','Author.username','Resource.pagetitle'));
$c->select('
    ('.$commentsSql.') AS `comments`
');
$c->sortby($sort,$dir);
if ($isCombo || $isLimit) {
    $c->limit($limit,$start);
}
$comments = $modx->getCollection('quipComment',$c);

$canApprove = $modx->hasPermission('quip.comment_approve');
$canRemove = $modx->hasPermission('quip.comment_remove');
$canUpdate = $modx->hasPermission('quip.comment_update');
if ($thread) {
    $canApprove = $canApprove && $thread->checkPolicy('comment_approve');
    $canRemove = $canRemove && $thread->checkPolicy('comment_remove');
    $canUpdate = $canUpdate && $thread->checkPolicy('comment_update');
}
$cls = array();
if ($canApprove) $cls[] = 'papprove';
if ($canUpdate) $cls[] = 'pupdate';
if ($canRemove) $cls[] = 'premove';
$cls = implode(',',$cls);

$list = array();
foreach ($comments as $comment) {
    $commentArray = $comment->toArray();
    $commentArray['url'] = $comment->makeUrl();
    if (empty($commentArray['pagetitle'])) {
        $commentArray['pagetitle'] = $modx->lexicon('quip.view');
    }

    if (empty($commentArray['username'])) $commentArray['username'] = $commentArray['name'];
    $commentArray['body'] = str_replace('<br />','',$commentArray['body']);

    $commentArray['createdon'] = strftime('%a %b %d, %Y %I:%M %p',strtotime($commentArray['createdon']));
    $commentArray['cls'] = $cls;
    
    $list[]= $commentArray;
}
return $this->outputArray($list,$count);