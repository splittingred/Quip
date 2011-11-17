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
class QuipCommentUnApproveMultipleProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('quip.comment_approve');
    }

    public function initialize() {
        $comments = $this->getProperty('comments');
        if (empty($comments)) {
            return $this->modx->lexicon('quip.comment_err_ns');
        }
        return parent::initialize();
    }

    public function process() {
        $comments = explode(',',$this->getProperty('comments'));
        foreach ($comments as $commentId) {
            /** @var $comment quipComment */
            $comment = $this->modx->getObject('quipComment',$commentId);
            if (empty($comment)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[Quip] Comment not found to unapprove with ID `'.$commentId.'`');
                continue;
            }
            $comment->unapprove();
        }

        return $this->success();
    }
}
return 'QuipCommentUnApproveMultipleProcessor';
