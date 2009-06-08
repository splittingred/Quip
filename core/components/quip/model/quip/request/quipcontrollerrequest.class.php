<?php
/**
 * @package quip
 */
require_once MODX_CORE_PATH . 'model/modx/modrequest.class.php';
/**
 * Encapsulates the interaction of MODx manager with an HTTP request.
 *
 * {@inheritdoc}
 *
 * @package quip
 */
class QuipControllerRequest extends modRequest {
    var $quip = null;
    var $actionVar = 'action';
    var $defaultAction = 'home';

    function __construct(Quip &$quip) {
        parent :: __construct($quip->modx);
        $this->quip =& $quip;
    }

    public function handleRequest() {
        $this->loadErrorHandler();

        /* save page to manager object. allow custom actionVar choice for extending classes. */
        $this->action = isset($_REQUEST[$this->actionVar]) ? $_REQUEST[$this->actionVar] : $this->defaultAction;

        return $this->_prepareResponse();
    }

    /**
     * Prepares the MODx response to a mgr request that is being handled.
     *
     * @access public
     * @return boolean True if the response is properly prepared.
     */
    function _prepareResponse() {
        $modx =& $this->modx;
        $quip =& $this->quip;
        $viewHeader = include $this->quip->config['core_path'].'controllers/mgr/header.php';

        $f = $this->quip->config['core_path'].'controllers/mgr/'.$this->action.'.php';
        if (file_exists($f)) {
            $viewOutput = include $f;
        } else {
            $viewOutput = 'Action not found: '.$f;
        }

        return $viewHeader.$viewOutput;
    }
}