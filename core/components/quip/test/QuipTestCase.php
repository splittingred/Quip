<?php
/**
 * @package quip-test
 */
/**
 * Extends the basic PHPUnit TestCase class to provide Quip specific methods
 *
 * @package quip-test
 */
class QuipTestCase extends PHPUnit_Framework_TestCase {
    /**
     * @var modX $modx
     */
    protected $modx = null;
    /**
     * @var Quip $quip
     */
    protected $quip = null;

    /**
     * Ensure all tests have a reference to the MODX and Quip objects
     */
    public function setUp() {
        $this->modx =& QuipTestHarness::_getConnection();
        $fiCorePath = $this->modx->getOption('quip.core_path',null,$this->modx->getOption('core_path',null,MODX_CORE_PATH).'components/quip/');
        require_once $fiCorePath.'model/quip/quip.class.php';
        $this->quip = new Quip($this->modx);
        /* set this here to prevent emails/headers from being sent */
        $this->quip->inTestMode = true;
        /* make sure to reset MODX placeholders so as not to keep placeholder data across tests */
        $this->modx->placeholders = array();
        $this->modx->quip =& $this->quip;
    }

    /**
     * Remove reference at end of test case
     */
    public function tearDown() {
        $this->modx = null;
        $this->quip = null;
    }
}