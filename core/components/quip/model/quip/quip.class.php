<?php
/**
 * Quip
 *
 * Copyright 2009 by Shaun McCormick <shaun@collabpad.com>
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
 * This file is the main class file for Quip.
 *
 * @copyright Copyright (C) 2009, Shaun McCormick <shaun@collabpad.com>
 * @author Shaun McCormick <shaun@collabpad.com>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @package quip
 */
class Quip {
    /**
     * @access protected
     * @var array A collection of preprocessed chunk values.
     */
    protected $chunks = array();
    /**
     * @access public
     * @var modX A reference to the modX object.
     */
    public $modx = null;
    /**
     * @access public
     * @var array A collection of properties to adjust Quip behaviour.
     */
    public $config = array();

    /**
     * The Quip Constructor.
     *
     * This method is used to create a new Quip object.
     *
     * @param modX &$modx A reference to the modX object.
     * @param array $config A collection of properties that modify Quip
     * behaviour.
     * @return Quip A unique Quip instance.
     */
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        $core = $this->modx->getOption('core_path').'components/quip/';
        $assets_url = $this->modx->getOption('assets_url').'components/quip/';
        $assets_path = $this->modx->getOption('assets_path').'components/quip/';
        $this->config = array_merge(array(
            'core_path' => $core,
            'model_path' => $core.'model/',
            'processors_path' => $core.'processors/',
            'controllers_path' => $core.'controllers/',
            'chunks_path' => $core.'chunks/',

            'base_url' => $assets_url,
            'css_url' => $assets_url.'css/',
            'js_url' => $assets_url.'js/',
            'connector_url' => $assets_url.'connector.php',

            'thread' => '',

            'tplquipAddComment' => '',
            'tplquipComment' => '',
            'tplquipCommentOptions' => '',
            'tplquipComments' => '',
            'tplquipLoginToComment' => '',
            'tplquipReport' => '',
        ),$config);

        $this->modx->addPackage('quip',$this->config['model_path']);
        if ($this->modx->lexicon) {
            $this->modx->lexicon->load('quip:default');
        }

        /* load star rating if desired : not yet built in */
        if ($this->modx->getOption('quip.useStarRating',null,false)) {
            $this->modx->addPackage('star_rating',$this->modx->getOption('quip.starRating_path',null,$this->modx->getOption('assets_path').'components/star_rating/').'model/');
        }

        /* load debugging settings */
        if ($this->modx->getOption('debug',$this->config,false)) {
            error_reporting(E_ALL); ini_set('display_errors',true);
            $this->modx->setLogTarget('HTML');
            $this->modx->setLogLevel(MODX_LOG_LEVEL_ERROR);

            $debugUser = $this->config['debugUser'] == '' ? $this->modx->user->get('username') : 'anonymous';
            $user = $this->modx->getObject('modUser',array('username' => $debugUser));
            if ($user == null) {
                $this->modx->user->set('id',$this->modx->getOption('debugUserId',$this->config,1));
                $this->modx->user->set('username',$debugUser);
            } else {
                $this->modx->user = $user;
            }
        }
    }

    /**
     * Initializes Quip based on a specific context.
     *
     * @access public
     * @param string $ctx The context to initialize in.
     * @return string The processed content.
     */
    public function initialize($ctx = 'mgr') {
        $output = '';
        switch ($ctx) {
            case 'mgr':
                if (!$this->modx->loadClass('quip.request.QuipControllerRequest',$this->config['model_path'],true,true)) {
                    return 'Could not load controller request handler.';
                }
                $this->request = new QuipControllerRequest($this);
                $output = $this->request->handleRequest();
                break;
            default:
                if (!$this->modx->loadClass('quip.request.QuipViewRequest',$this->config['model_path'],true,true)) {
                    return 'Could not load view request handler.';
                }
                $this->request = new QuipViewRequest($this);
                $output = $this->request->handle();
                break;
        }
        return $output;
    }

    /**
     * Processes the content of a chunk in either of the following ways:
     *
     * - Should the property tpl+chunkName be set, uses that content
     * - Otherwise, loads chunk from file
     *
     * Also caches the preprocessed chunk content to an array to speed loading
     * times, especially when looping through collections.
     *
     * @access public
     * @param string $name The name of the chunk to process
     * @param array $properties (optional) An array of properties
     * @return string The processed content string
     */
    public function getChunk($name,$properties = array()) {
        /* first check internal cache */
        if (!isset($this->chunks[$name])) {
            /* if specifying chunk value in snippet properties */
            if (!empty($this->config['tpl'.$name])) {
                $chunk = $this->modx->newObject('modChunk');
                $chunk->setContent($this->config['tpl'.$name]);
            }
            /* if using default chunk names, defaulting to files */
            if (empty($chunk)) {
                $chunk = $this->_getTplChunk($name);
                if ($chunk == false) return false;
            }
            $this->chunks[$name] = $chunk->getContent();
        } else { /* load chunk from cache */
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }

    /**
     * Creates a temporary modChunk object from a tpl file.
     *
     * @access private
     * @param string $name The name of the chunk to load from file.
     * @return modChunk The newly created modChunk object.
     */
    private function _getTplChunk($name) {
        $chunk = false;
        $f = $this->config['chunks_path'].strtolower($name).'.chunk.tpl';
        if (file_exists($f)) {
            $o = file_get_contents($f);
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name',$name);
            $chunk->setContent($o);
        }
        return $chunk;
    }


    /**
     * Builds simple pagination markup. Not yet used.
     *
     * TODO: add tpl configurability to li/a tags.
     *
     * @access public
     * @param integer $count The total number of records
     * @param integer $limit The number to limit to
     * @param integer $start The record to start on
     * @param string $url The URL to prefix a hrefs with
     * @return string The rendered template.
     */
    public function buildPagination($count,$limit,$start,$url) {
        $pageCount = $count / $limit;
        $curPage = $start / $limit;
        $pages = '';
        for ($i=0;$i<$pageCount;$i++) {
            $newStart = $i*$limit;
            $u = $url.'&start='.$newStart.'&limit='.$limit;
            if ($i != $curPage) {
                $pages .= '<li class="page-number"><a href="'.$u.'">'.($i+1).'</a></li>';
            } else {
                $pages .= '<li class="page-number pgCurrent">'.($i+1).'</li>';
            }
        }
        return $this->getChunk('quipPagination',array(
            'quip.pages' => $pages,
        ));
    }
}