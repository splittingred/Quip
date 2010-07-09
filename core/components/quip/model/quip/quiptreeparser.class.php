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
 * Handles row-parsing of naive tree data.
 *
 * @package quip
 */
class QuipTreeParser {
    protected $last = '';
    public $openItem =      '<li class="[[+class]]" id="[[+idprefix]][[+id]]">';
    public $closeItem =     '</li>';
    public $openChildren =  '<ol class="[[+olClass]]">';
    public $closeChildren = '</ol>';

    function __construct(Quip &$quip,array $config = array()) {
        $this->quip =& $quip;
        $this->config = $config;
    }

    public function parse(array $array,$tpl = '') {
        /* set a value not possible in a LEVEL column to allow the
         * first row to know it's "firstness" */
        $this->last = null;
        $this->tpl = $tpl;

        /* add a couple dummy "rows" to cap off formatting */
        $array[] = array();
        $array[] = array();

        /* invoke our formatting function via callback */
        $output = array_map(array($this,'_iterate'),$array);

        /* output the results */
        $output = implode("\n", $output);

        return $output;
    }

    private function _iterate($current){
        /* last = the previous row's level, or null on the first row */

        /* structural elements */
        $structure = '';

        /* set class/id for li */
        $openItem = str_replace('[[+id]]',$current['id'],$this->openItem);
        $idPrefix = !empty($current['idPrefix']) ? $current['idprefix'] : 'quip-comment-';
        $openItem = str_replace('[[+idprefix]]',$idPrefix,$openItem);
        $class = !empty($current['cls']) ? $current['cls'] : 'quip-comment-post';
        $openItem = str_replace('[[+class]]',$class,$openItem);

        /* set class for ol */
        $class = !empty($current['olCls']) ? $current['olCls'] : 'quip-comment-parent';
        $openChildren = str_replace('[[+olClass]]',$class,$this->openChildren);

        $closeItem = $this->closeItem;
        $closeChildren = $this->closeChildren;

        if (!isset($current['depth'])) {
            /* add closing structure(s) equal to the very last row's level;
             * this will only fire for the "dummy" */
            return str_repeat($closeItem.$closeChildren,$this->last);
        }

        /* add the item itself */
        if (empty($this->tpl)) {
            $item = $current['body'];
        } else {
            $item = $this->quip->getChunk($this->tpl,$current);
        }

        if (is_null($this->last)) {
            /* add the opening structure in the case of the first row */
            $structure .= $openChildren;
        } elseif ( $this->last < $current['depth'] ) {
            /* add the structure to start new branches */
            $structure .= $openChildren;
        } elseif ( $this->last > $current['depth'] ){
            /* add the structure to close branches equal to the difference
             * between the previous and current levels */
            $structure .= $closeItem
                . str_repeat($closeChildren . $closeItem,
                    $this->last - $current['depth']);
        } else {
            $structure .= $closeItem;
        }

        /* add the item structure */
        $structure .= $openItem;

        /* update last so the next row knows whether this row is really
         * its parent */
        $this->last = $current['depth'];

        return $structure.$item;
    }
}