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
 * Get a list of comments
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

/* build query */
$c = $modx->newQuery('quipComment');
$c->leftJoin('modUser','Author');
$c->leftJoin('modResource','Resource');
if ($thread) {
    $c->where(array(
        'quipComment.thread' => $thread->get('name'),
    ));
}
$c->where(array(
    'quipComment.deleted' => $deleted,
));
$count = $modx->getCount('quipComment',$c);

$c->select(array('quipComment.*','Author.username','Resource.pagetitle'));
$c->select('
    (SELECT COUNT(*) FROM '.$modx->getTableName('quipComment').' AS `ct` WHERE `thread` = `quipComment`.`thread` AND `deleted` = 0 AND `approved` = 1) AS `comments`
');
$c->sortby($sort,$dir);
if ($isCombo || $isLimit) {
    $c->limit($limit,$start);
}
$comments = $modx->getCollection('quipComment', $c);

$canApprove = $modx->hasPermission('quip.comment_approve');
$canRemove = $modx->hasPermission('quip.comment_remove');
$canUpdate = $modx->hasPermission('quip.comment_update');
if ($thread) {
    $canApprove = $canApprove && $thread->checkPolicy('comment_approve');
    $canRemove = $canRemove && $thread->checkPolicy('comment_remove');
    $canUpdate = $canUpdate && $thread->checkPolicy('comment_update');
}

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

    $commentArray['menu'] = array();
    if ($canUpdate) {
        $commentArray['menu'][] = array(
            'text' => $modx->lexicon('quip.comment_update'),
            'handler' => 'this.updateComment',
        );
    }
    if ($canApprove && !$comment->get('approved')) {
        $commentArray['menu'][] = array(
            'text' => $modx->lexicon('quip.comment_approve'),
            'handler' => 'this.approveComment',
        );
    } else if ($canApprove) {
        $commentArray['menu'][] = array(
            'text' => $modx->lexicon('quip.comment_unapprove'),
            'handler' => 'this.unapproveComment',
        );
    }
    if ($canRemove && !$comment->get('deleted')) {
        $commentArray['menu'][] = '-';
        $commentArray['menu'][] = array(
            'text' => $modx->lexicon('quip.comment_delete'),
            'handler' => 'this.deleteComment',
        );
    } else if ($canRemove) {
        $commentArray['menu'][] = '-';
        $commentArray['menu'][] = array(
            'text' => $modx->lexicon('quip.comment_undelete'),
            'handler' => 'this.undeleteComment',
        );
    }
    if ($canRemove && $comment->get('deleted')) {
        $commentArray['menu'][] = '-';
        $commentArray['menu'][] = array(
            'text' => $modx->lexicon('quip.comment_remove'),
            'handler' => 'this.removeComment',
        );
    }
    $list[]= $commentArray;
}
return $this->outputArray($list,$count);