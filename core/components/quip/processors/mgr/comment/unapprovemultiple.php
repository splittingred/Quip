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
 * Unapprove multiple comments
 *
 * @package quip
 * @subpackage processors
 */
if (!$modx->hasPermission('quip.comment_approve')) return $modx->error->failure($modx->lexicon('access_denied'));
if (empty($scriptProperties['comments'])) {
    return $modx->error->failure($modx->lexicon('quip.comment_err_ns'));
}

$commentIds = explode(',',$scriptProperties['comments']);

foreach ($commentIds as $commentId) {
    $comment = $modx->getObject('quipComment',$commentId);
    if ($comment == null) continue;
    if (!$comment->get('approved')) continue;

    $comment->set('approved',false);
    $comment->set('approvedon','0000-00-00 00:00:00');
    $comment->set('approvedby',0);

    if ($comment->save() === false) {
        return $modx->error->failure($modx->lexicon('quip.comment_err_save'));
    }
}

return $modx->error->success();
