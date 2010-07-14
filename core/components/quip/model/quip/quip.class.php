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
 * This file is the main class file for Quip.
 *
 * @copyright Copyright (C) 2010, Shaun McCormick <shaun@modxcms.com>
 * @author Shaun McCormick <shaun@modxcms.com>
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

        /* allows you to set paths in different environments
         * this allows for easier SVN management of files
         */
        $corePath = $this->modx->getOption('quip.core_path',null,$modx->getOption('core_path').'components/quip/');
        $assetsPath = $this->modx->getOption('quip.assets_path',null,$modx->getOption('assets_path').'components/quip/');
        $assetsUrl = $this->modx->getOption('quip.assets_url',null,$modx->getOption('assets_url').'components/quip/');

        $this->config = array_merge(array(
            'core_path' => $corePath,
            'model_path' => $corePath.'model/',
            'processors_path' => $corePath.'processors/',
            'controllers_path' => $corePath.'controllers/',
            'chunks_path' => $corePath.'chunks/',

            'base_url' => $assetsUrl,
            'css_url' => $assetsUrl.'css/',
            'js_url' => $assetsUrl.'js/',
            'connector_url' => $assetsUrl.'connector.php',

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
            $this->modx->setLogLevel(modX::LOG_LEVEL_ERROR);

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
        }
        return $output;
    }

    /**
     * Gets a Chunk and caches it; also falls back to file-based templates
     * for easier debugging.
     *
     * Will always use the file-based chunk if $debug is set to true.
     *
     * @access public
     * @param string $name The name of the Chunk
     * @param array $properties The properties for the Chunk
     * @return string The processed content of the Chunk
     */
    public function getChunk($name,$properties = array()) {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            if (!$this->modx->getOption('quip.debug',null,false)) {
                $chunk = $this->modx->getObject('modChunk',array('name' => $name));
            }
            if (empty($chunk)) {
                $chunk = $this->_getTplChunk($name);
                if ($chunk == $name) return $name;
            }
            $this->chunks[$name] = $chunk->getContent();
        } else {
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }
    /**
     * Returns a modChunk object from a template file.
     *
     * @access private
     * @param string $name The name of the Chunk. Will parse to name.chunk.tpl
     * @return modChunk/boolean Returns the modChunk object if found, otherwise
     * false.
     */
    private function _getTplChunk($name) {
        $chunk = $name;
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
     * @access public
     * @param array $options An array of options:
     * - count The total number of records
     * - limit The number to limit to
     * - start The record to start on
     * - url The URL to prefix pagination urls with
     * @return string The rendered template.
     */
    public function buildPagination(array $options = array()) {
        $pageCount = $options['count'] / $options['limit'];
        $curPage = $options['start'] / $options['limit'];
        $pages = '';

        $params = $_GET;
        unset($params[$this->modx->getOption('request_param_alias',null,'q')]);

        $tplItem = $this->modx->getOption('tplPaginationItem',$options,'quipPaginationItem');
        $tplCurrentItem = $this->modx->getOption('tplPaginationCurrentItem',$options,'quipPaginationCurrentItem');
        $pageCls = $this->modx->getOption('pageCls',$options,'quip-page-number');
        $currentPageCls = $this->modx->getOption('currentPageCls',$options,'quip-page-current');

        for ($i=0;$i<$pageCount;$i++) {
            $newStart = $i*$options['limit'];
            $u = $options['url'].(strpos($options['url'],'?') !== false ? '&' : '?').http_build_query(array_merge($params,array(
                'quip_start' => $newStart,
                'quip_limit' => $options['limit'],
            )));
            if ($i != $curPage) {
                $pages .= $this->getChunk($tplItem,array(
                    'url' => $u,
                    'idx' => $i+1,
                    'cls' => $pageCls,
                ));
            } else {
                $pages .= $this->getChunk($tplCurrentItem,array(
                    'idx' => $i+1,
                    'cls' => $pageCls.' '.$currentPageCls,
                ));
            }
        }
        return $this->getChunk($this->modx->getOption('tplPagination',$options,'quipPagination'),array(
            'pages' => $pages,
            'cls' => $this->modx->getOption('paginationCls',$options,'quip-pagination'),
        ));
    }

    /**
     * Gets a properly formatted "time ago" from a specified timestamp
     */
    public function getTimeAgo($time = '') {
        if (empty($time)) return false;
        
        $agoTS = $this->timesince($time);
        $ago = array();
        if (!empty($agoTS['days'])) {
            $ago[] = $this->modx->lexicon('quip.ago_days',$agoTS);
        }
        if (!empty($agoTS['hours'])) {
            $ago[] = $this->modx->lexicon('quip.ago_hours',$agoTS);
        }
        if (!empty($agoTS['minutes']) && empty($agoTS['days'])) {
            $ago[] = $this->modx->lexicon('quip.ago_minutes',$agoTS);
        }
        if (empty($ago)) { /* handle <1 min */
            $ago[] = $this->modx->lexicon('quip.ago_seconds',$agoTS);
        }
        return implode(', ',$ago).' '.$this->modx->lexicon('ago');
    }

    /**
     * Gets a proper array of time since a timestamp
     */
    public function timesince($input) {
        $output = '';
        $uts['start'] = strtotime($input);
        $uts['end'] = time();
        if( $uts['start']!==-1 && $uts['end']!==-1 ) {
            if( $uts['end'] >= $uts['start'] ) {
                $diff = $uts['end'] - $uts['start'];
                $days = intval((floor($diff/86400)));
                if ($days) $diff = $diff % 86400;
                $hours = intval((floor($diff/3600)));
                if ($hours) $diff = $diff % 3600;
                $minutes = intval((floor($diff/60)));
                if ($minutes) $diff = $diff % 60;

                $diff = intval($diff);
                $output = array(
                    'days' => $days
                    ,'hours' => $hours
                    ,'minutes' => $minutes
                    ,'seconds' => $diff
                );
            }
        }
        return $output;
    }
}