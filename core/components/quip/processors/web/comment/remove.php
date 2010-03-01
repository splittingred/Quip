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
 * Remove a comment
 *
 * @package quip
 * @subpackage processors
 */
$errors = array();
if (empty($_POST['id'])) {
    $errors['message'] = $modx->lexicon('quip.comment_err_ns');
    return $errors;
}

$comment = $modx->getObject('quipComment',$_POST['id']);
if (empty($comment)) { $errors['message'] = $modx->lexicon('quip.comment_err_nf'); }

if (empty($errors) && $comment->remove() === false) {
    $errors['message'] = $modx->lexicon('quip.comment_err_remove');
}

return $errors;
