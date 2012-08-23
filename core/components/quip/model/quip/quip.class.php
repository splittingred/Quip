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
 * This file is the main class file for Quip.
 *
 * @copyright Copyright (C) 2010, Shaun McCormick <shaun@modx.com>
 * @author Shaun McCormick <shaun@modx.com>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @package quip
 */
class Quip {
    /**
     * A collection of preprocessed chunk values.
     * @var array $chunks
     */
    protected $chunks = array();
    /**
     * A reference to the modX object.
     * @var modX $modx
     */
    public $modx = null;
    /**
     * A collection of properties to adjust Quip behaviour.
     * @var array $config
     */
    public $config = array();
    /**
     * The request object for the current state
     * @var QuipControllerRequest $request
     */
    public $request;
    /**
     * The controller for the current request
     * @var quipController $controller
     */
    public $controller = null;
    /**
     * Whether or not Quip is in Test Mode for unit testing
     * @var boolean $inTestMode
     */
    public $inTestMode = false;
    /**
     * @var quipTreeParser $treeParser
     */
    public $treeParser;
    /** @var quipHooks $preHooks */
    public $preHooks;
    /** @var quipHooks $postHooks */
    public $postHooks;
    
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
            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'processorsPath' => $corePath.'processors/',
            'controllersPath' => $corePath.'controllers/',
            'templatesPath' => $corePath.'templates/',
            'chunksPath' => $corePath.'elements/chunks/',
            'snippetsPath' => $corePath.'elements/snippets/',

            'baseUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl.'css/',
            'jsUrl' => $assetsUrl.'js/',
            'connectorUrl' => $assetsUrl.'connector.php',

            'thread' => '',

            'tplquipAddComment' => '',
            'tplquipComment' => '',
            'tplquipCommentOptions' => '',
            'tplquipComments' => '',
            'tplquipLoginToComment' => '',
            'tplquipReport' => '',
        ),$config);

        $this->modx->addPackage('quip',$this->config['modelPath']);
        if ($this->modx->lexicon) {
            $this->modx->lexicon->load('quip:default');
        }
        $this->initDebug();
    }

    /**
     * Load debugging settings
     */
    public function initDebug() {
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
                if (!$this->modx->loadClass('quip.request.QuipControllerRequest',$this->config['modelPath'],true,true)) {
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
     * @param string $suffix The suffix to postfix the chunk with
     * @return modChunk/boolean Returns the modChunk object if found, otherwise
     * false.
     */
    private function _getTplChunk($name,$suffix = '.chunk.tpl') {
        $chunk = $name;
        $suffix = $this->modx->getOption('suffix',$this->config,$suffix);
        $f = $this->config['chunksPath'].strtolower($name).$suffix;
        if (file_exists($f)) {
            $o = file_get_contents($f);
            /** @var modChunk $chunk */
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
        $pageCount = $options['count'] / (!empty($options['limit']) ? $options['limit'] : 1);
        $curPage = $options['start'] / (!empty($options['limit']) ? $options['limit'] : 1);
        $pages = '';

        $params = $this->modx->request->getParameters();
        unset($params[$this->modx->context->getOption('request_param_alias','q')]);

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
     * Gets a properly formatted "time ago" from a specified timestamp. Copied
     * from MODx core output filters.
     *
     * @param string $time
     * @return string
     */
    public function getTimeAgo($time = '') {
        if (empty($time)) return false;
        $this->modx->lexicon->load('filters');
        $agoTS = array();

        $uts = array();
        $uts['start'] = strtotime($time);
        $uts['end'] = time();
        if( $uts['start']!==-1 && $uts['end']!==-1 ) {
          if( $uts['end'] >= $uts['start'] ) {
            $diff = $uts['end'] - $uts['start'];

            $years = intval((floor($diff/31536000)));
            if ($years) $diff = $diff % 31536000;

            $months = intval((floor($diff/2628000)));
            if ($months) $diff = $diff % 2628000;

            $weeks = intval((floor($diff/604800)));
            if ($weeks) $diff = $diff % 604800;

            $days = intval((floor($diff/86400)));
            if ($days) $diff = $diff % 86400;

            $hours = intval((floor($diff/3600)));
            if ($hours) $diff = $diff % 3600;

            $minutes = intval((floor($diff/60)));
            if ($minutes) $diff = $diff % 60;

            $diff = intval($diff);
            $agoTS = array(
              'years' => $years,
              'months' => $months,
              'weeks' => $weeks,
              'days' => $days,
              'hours' => $hours,
              'minutes' => $minutes,
              'seconds' => $diff,
            );
          }
        }

        $ago = array();
        if (!empty($agoTS['years'])) {
          $ago[] = $this->modx->lexicon(($agoTS['years'] > 1 ? 'ago_years' : 'ago_year'),array('time' => $agoTS['years']));
        }
        if (!empty($agoTS['months'])) {
          $ago[] = $this->modx->lexicon(($agoTS['months'] > 1 ? 'ago_months' : 'ago_month'),array('time' => $agoTS['months']));
        }
        if (!empty($agoTS['weeks']) && empty($agoTS['years'])) {
          $ago[] = $this->modx->lexicon(($agoTS['weeks'] > 1 ? 'ago_weeks' : 'ago_week'),array('time' => $agoTS['weeks']));
        }
        if (!empty($agoTS['days']) && empty($agoTS['months']) && empty($agoTS['years'])) {
          $ago[] = $this->modx->lexicon(($agoTS['days'] > 1 ? 'ago_days' : 'ago_day'),array('time' => $agoTS['days']));
        }
        if (!empty($agoTS['hours']) && empty($agoTS['weeks']) && empty($agoTS['months']) && empty($agoTS['years'])) {
          $ago[] = $this->modx->lexicon(($agoTS['hours'] > 1 ? 'ago_hours' : 'ago_hour'),array('time' => $agoTS['hours']));
        }
        if (!empty($agoTS['minutes']) && empty($agoTS['days']) && empty($agoTS['weeks']) && empty($agoTS['months']) && empty($agoTS['years'])) {
          $ago[] = $this->modx->lexicon('ago_minutes',array('time' => $agoTS['minutes']));
        }
        if (empty($ago)) { /* handle <1 min */
          $ago[] = $this->modx->lexicon('ago_seconds',array('time' => $agoTS['seconds']));
        }
        $output = implode(', ',$ago);
        $output = $this->modx->lexicon('ago',array('time' => $output));
        return $output;
    }

    /**
     * Gets a proper array of time since a timestamp
     *
     * @access public
     * @param string $input The time to get from
     * @return array An array of times
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

    /**
     * Loads the Hooks class.
     *
     * @param string $type The type of hook to load.
     * @param array $config An array of configuration parameters for the
     * hooks class
     * @return quipHooks An instance of the quipHooks class.
     */
    public function loadHooks($type = 'post',array $config = array()) {
        if (!$this->modx->loadClass('quip.quipHooks',$this->config['modelPath'],true,true)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Quip] Could not load quipHooks class.');
            return false;
        }
        $type = $type.'Hooks';
        $this->$type = new quipHooks($this,$config);
        return $this->$type;
    }

    /**
     * Get a unique nonce value
     *
     * @access public
     * @param string $prefix A prefix to append to the nonce.
     * @return string The generated nonce.
     */
    public function getNonce($prefix = 'quip-') {
        return base64_encode($prefix.$this->modx->resource->get('id').'-'.session_id());
    }
    
    /**
     * Verify that a passed nonce matches the cached nonce
     * 
     * @param string $nonce The nonce to check against
     * @param string $prefix The prefix for the nonce
     * @return bool True if passes
     */
    public function checkNonce($nonce,$prefix = 'quip-') {
        $nonceKey = $this->getNonce($prefix);
        $nonceCache = $this->modx->cacheManager->get('quip/'.$nonceKey);
        $passedNonce = false;
        if ($nonceCache && $nonceCache['value'] == $nonce) {
            $passedNonce = true;
        }
        return $passedNonce;
    }
    
    /**
     * Create a nonce to be used for verification and store in cache
     *
     * @param string $prefix The prefix for the nonce
     * @return string The created nonce
     */
    public function createNonce($prefix = 'quip-') {
        $nonceKey = $this->getNonce($prefix);
        $nonce = uniqid($prefix.$this->modx->resource->get('id'));
        $nonceCache = array('value' => $nonce);
        $this->modx->cacheManager->set('quip/'.$nonceKey,$nonceCache,600);
        return $nonce;
    }

    /**
     * Clean a string of tags and XSS attempts
     * 
     * @param string $body The string to clean
     * @param array $scriptProperties An array of options
     * @return string The cleansed text
     */
    public function cleanse($body,array $scriptProperties = array()) {
        $allowedTags = $this->modx->context->getOption('quip.allowed_tags','<br><b><i>',$scriptProperties);

        /* strip tags */
        $body = preg_replace("/<script(.*)<\/script>/i",'',$body);
        $body = preg_replace("/<iframe(.*)<\/iframe>/i",'',$body);
        $body = preg_replace("/<iframe(.*)\/>/i",'',$body);
        $body = strip_tags($body,$allowedTags);
        // this causes double quotes on a href tags; commenting out for now
        //$body = str_replace(array('"',"'"),array('&quot;','&apos;'),$body);
        /* replace MODx tags with entities */
        $body = str_replace(array('[',']','`'),array('&#91;','&#93;','&#96;'),$body);

        return $body;
    }

    /**
     * Convert links to <a> tags and add rel="nofollow"
     *
     * @param string $body The string to parse
     * @param array $scriptProperties An array of options
     * @return string The parsed text
     */
    public function parseLinks($body,array $scriptProperties = array()) {
        /* auto-convert links to tags */
        $autoConvertLinks = $this->modx->getOption('autoConvertLinks',$scriptProperties,true);
        if ($autoConvertLinks) {
            $extraAutoLinksAttributes = $this->modx->getOption('extraAutoLinksAttributes',$scriptProperties,'');
            if (!empty($extraAutoLinksAttributes) && substr($extraAutoLinksAttributes,0,1) != ' ') {
                $extraAutoLinksAttributes = ' '.$extraAutoLinksAttributes;
            }
            $pattern = "@\b(https?://)?(([0-9a-zA-Z_!~*'().&=+$%-]+:)?[0-9a-zA-Z_!~*'().&=+$%-]+\@)?(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-zA-Z_!~*'()-]+\.)*([0-9a-zA-Z][0-9a-zA-Z-]{0,61})?[0-9a-zA-Z]\.[a-zA-Z]{2,6})(:[0-9]{1,4})?((/[0-9a-zA-Z_!~*'().;?:\@&=+$,%#-]+)*/?)@";
            $body = preg_replace($pattern, '<a href="\0" rel="nofollow"'.$extraAutoLinksAttributes.'>\0</a>',$body);
        }
        return $body;
    }

    /**
     * Load the appropriate controller
     * @param string $controller
     * @return null|quipController
     */
    public function loadController($controller) {
        if ($this->modx->loadClass('quipController',$this->config['modelPath'].'quip/request/',true,true)) {
            $classPath = $this->config['controllersPath'].'web/'.$controller.'.php';
            $className = 'Quip'.$controller.'Controller';
            if (file_exists($classPath)) {
                if (!class_exists($className)) {
                    $className = require_once $classPath;
                }
                if (class_exists($className)) {
                    $this->controller = new $className($this,$this->config);
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'[Quip] Could not load controller: '.$className.' at '.$classPath);
                }
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[Quip] Could not load controller file: '.$classPath);
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Quip] Could not load quipController class.');
        }
        return $this->controller;
    }
}