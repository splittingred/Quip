<?php
/**
 * @package quip
 */
/**
 * Simple commenting component
 *
 * @package quip
 */
class Quip {
    protected $chunks = array();

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