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
 * @subpackage controllers
 */
/**
 * Returns the number of comments a given thread/user/family has
 * 
 * @package quip
 * @subpackage controllers
 */
class QuipThreadCountController extends QuipController {
    /**
     * Initialize this controller, setting up default properties
     * @return void
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'type' => 'thread',
            'family' => '',
            'user' => '',
        ));
    }

    /**
     * @param string|array $type
     * @return int
     */
    public function getCount($type = 'thread') {
        if (!is_array($type)) $type = explode(',',$type);
        
        $c = $this->modx->newQuery('quipComment');

        /* filter by user */
        if (in_array('user',$type)) {
            $user = $this->getProperty('user','');

            $c->innerJoin('modUser','Author');
            if (is_numeric($user)) {
                $c->where(array(
                    'Author.id' => $user,
                ));
            } else {
                $c->where(array(
                    'Author.username' => $user,
                ));
            }
        }
        /* filter by thread */
        if (in_array('thread',$type)) {
            $c->where(array(
                'thread' => $this->getProperty('thread',''),
            ));
        }
        /* filter by family */
        if (in_array('family',$type)) {
            $c->where(array(
                'quipComment.thread:LIKE' => $this->getProperty('family',''),
            ));
        }

        $c->where(array(
            'quipComment.approved' => true,
            'quipComment.deleted' => false,
        ));
        return $this->modx->getCount('quipComment',$c);
    }

    /**
     * Process this controller and render the result
     * @return string
     */
    public function process() {
        $output = $this->getCount($this->getProperty('type','thread'));
        return $this->output($output);
    }

    /**
     * Output the result to a placeholder or directly
     * @param string $output
     * @return string
     */
    public function output($output = '') {
        /* output */
        $toPlaceholder = $this->getProperty('toPlaceholder',false);
        if ($toPlaceholder) {
            $this->modx->setPlaceholder($toPlaceholder,$output);
            return '';
        }
        return $output;
    }
}
return 'QuipThreadCountController';