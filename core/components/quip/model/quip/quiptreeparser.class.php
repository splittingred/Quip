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
 * Handles row-parsing of naive tree data.
 *
 * @package quip
 */
class QuipTreeParser {
    /**
     * The last node output
     * @var string $last
     */
    protected $last = '';
    /**
     * The current open thread node
     * @var array $openThread
     */
    protected $openThread = array();
    /**
     * The current collected output
     * @var string $output
     */
    protected $output = '';
    /**
     * The chunk tpl to use for each node
     * @var string $tpl
     */
    protected $tpl = '';

    /**
     * @param Quip $quip A reference to the Quip instance
     * @param array $config An array of configuration options
     */
    function __construct(Quip &$quip,array $config = array()) {
        $this->quip =& $quip;
        $this->config = $config;
    }

    /**
     * Parse the array in tree iteration
     * 
     * @param array $array The array of nodes to parse
     * @param string $tpl The chunk to use for each node
     * @return string The outputted content
     */
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

    /**
     * Iterate across the current node
     * 
     * @param array $current
     * @return string
     */
    private function _iterate($current){
        $depth = isset($current['depth']) ? $current['depth'] : null;
        $parent = isset($current['parent']) ? $current['parent'] : null;
        $item = '';

        if (is_null($this->last)) {
            // Set first children in the tree
            $this->setOpenThread($current, $depth);

        } elseif ($this->last < $depth){

            //Set current as new openthread for the current depth
            $this->setOpenThread($current, $depth);

        } elseif ($depth == $this->last) {

            if ($depth == 0) {
                //If last thread is on the root, close it and add it the output item
                $item .= $this->getOpenThread(0);
            }
            //Set current as new openthread for the current depth
            $this->setOpenThread($current, $depth);

        } elseif ($this->last > $depth) {
            $nb = $depth > 0 ? $depth : 0;

            for ($i = $this->last; $i >= $nb; $i--) {
                if ($i > 0) {
                    //Process children and add id to parent children placeholder
                    $children = $this->getOpenThread($i);
                    $this->setOpenThreadChildren($i - 1, $children);
                }
            }

            if ($depth == 0) {
                // Close & chunkify the openThread only if we are on the root level
                $item .= $this->getOpenThread(0);
                //Set current thread as new root
                $this->setOpenThread($current,0);
            } else {
                //Set current thread as the last open thread
                $this->setOpenThread($current, $depth);
            }
        }

        $this->last = $depth;
        return $item;
    }

    /**
     * Set the current open thread node
     * @param string $string
     * @param int $depth
     * @return void
     */
    protected function setOpenThread($string, $depth) {
        if (!empty($this->openThread[$depth]) && $depth > 0) {
            if (empty($this->openThread[$depth-1])) $this->openThread[$depth-1] = array('children' => '');
            $this->openThread[$depth - 1]['children'] .= $this->quip->getChunk($this->tpl, $this->openThread[$depth]);
        }
        unset($this->openThread[$depth]);
        $this->openThread[$depth] = $string;
    }

    /**
     * Set the current open thread node's children
     * @param int $depth The current depth
     * @param string $string The children data to set
     * @return void
     */
    protected function setOpenThreadChildren($depth, $string) {
        $this->openThread[$depth]['children'] .= $string;
    }

    /**
     * Get the current open thread parsed
     * @param int $depth
     * @return string
     */
    protected function getOpenThread($depth) {
        $thread = $this->quip->getChunk($this->tpl, $this->openThread[$depth]);
        unset($this->openThread[$depth]);
        return $thread;
    }
}