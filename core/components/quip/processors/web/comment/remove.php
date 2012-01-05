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
 * Remove a comment
 *
 * @var Quip $quip
 * @var modX $modx
 * @var array $fields
 * @var QuipThreadController $this
 * 
 * @package quip
 * @subpackage processors
 */
$errors = array();
if (empty($_REQUEST['quip_comment'])) {
    $errors['message'] = $modx->lexicon('quip.comment_err_ns');
    return $errors;
}

/** @var quipComment $comment */
$comment = $modx->getObject('quipComment',$_REQUEST['quip_comment']);
if (empty($comment)) {
    $errors['message'] = $modx->lexicon('quip.comment_err_nf');
    return $errors;
}

/* only allow authors or moderators to remove comments */
if ($comment->get('author') != $modx->user->get('id') && !$this->isModerator) {
    $errors['message'] = $modx->lexicon('quip.comment_err_nf');
    return $errors;
}

$comment->set('deleted',true);
$comment->set('deletedon',strftime('%Y-%m-%d %H:%M:%S'));
$comment->set('deletedby',$modx->user->get('id'));

if (empty($errors) && $comment->save() === false) {
    $errors['message'] = $modx->lexicon('quip.comment_err_remove');
}

return $errors;
