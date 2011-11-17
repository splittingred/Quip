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
 * @package quip
 * @subpackage processors
 */
class QuipCommentDeleteProcessor extends modObjectProcessor {
    public $classKey = 'quipComment';
    public $permission = 'quip.comment_remove';
    public $languageTopics = array('quip:default');

    /** @var quipComment $comment */
    public $comment;

    public function initialize() {
        $id = $this->getProperty('id');
        if (empty($id)) return $this->modx->lexicon('quip.comment_err_ns');
        $this->comment = $this->modx->getObject($this->classKey,$id);
        if (empty($this->comment)) return $this->modx->lexicon('quip.comment_err_nf');
        return parent::initialize();
    }

    public function process() {
        if ($this->comment->delete() === false) {
            return $this->failure($this->modx->lexicon('quip.comment_err_delete'));
        }

        return $this->success();
    }
}
return 'QuipCommentDeleteProcessor';