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
 * Completely truncate a thread of comments.
 *
 * @package quip
 * @subpackage processors
 */
if (empty($scriptProperties['thread'])) return $modx->error->failure($modx->lexicon('quip.thread_err_ns'));

/* make sure user can truncate thread */
if (empty($scriptProperties['thread'])) return $modx->error->failure($modx->lexicon('quip.thread_err_ns'));
$thread = $modx->getObject('quipThread',$scriptProperties['thread']);
if (empty($thread)) return $modx->error->failure($modx->lexicon('quip.thread_err_nf'));
if (!$thread->checkPolicy('truncate')) return $modx->error->failure($modx->lexicon('access_denied'));

/* get all comments in thread */
$c = $modx->newQuery('quipComment');
$c->where(array(
    'thread' => $scriptProperties['thread'],
));
$comments = $modx->getCollection('quipComment',$c);

foreach ($comments as $comment) {
    $comment->set('deleted',true);
    $comment->set('deletedon',strftime('%Y-%m-%d %H:%M:%S'));
    $comment->set('deletedby',$modx->user->get('id'));
    $comment->save();
}

return $modx->error->success();