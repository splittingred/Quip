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
class QuipThreadRemoveMultipleProcessor extends modProcessor {
    public function initialize() {
        $threads = $this->getProperty('threads');
        if (empty($threads)) {
            return $this->modx->lexicon('quip.thread_err_ns');
        }
        return parent::initialize();
    }
    public function process() {
        $threads = explode(',',$this->getProperty('threads'));
        foreach ($threads as $threadName) {
            /** @var $thread quipThread */
            $thread = $this->modx->getObject('quipThread',$threadName);
            if (empty($thread)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[Quip] Thread not found to remove with name `'.$threadName.'`');
                continue;
            }
            if ($thread->checkPolicy('remove')) {
                $thread->remove();
            }
        }

        return $this->success();
    }
}
return 'QuipThreadRemoveMultipleProcessor';