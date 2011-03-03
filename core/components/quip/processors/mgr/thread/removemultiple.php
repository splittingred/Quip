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
 * Completely remove multiple threads.
 *
 * @package quip
 * @subpackage processors
 */
if (empty($scriptProperties['threads'])) {
    return $modx->error->failure($modx->lexicon('quip.thread_err_ns'));
}

$threads = explode(',',$scriptProperties['threads']);

foreach ($threads as $threadName) {
    /* make sure user can truncate thread */
    $thread = $modx->getObject('quipThread',$threadName);
    if (empty($thread)) {
        $modx->log(modX::LOG_LEVEL_ERROR,'[Quip] Thread not found to remove with name `'.$threadName.'`');
        continue;
    }
    if (!$thread->checkPolicy('remove')) continue;

    $thread->remove();
}

return $modx->error->success();