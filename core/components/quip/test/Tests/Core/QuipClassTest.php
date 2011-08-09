<?php
/**
 * @package quip-test
 */
/**
 * Tests related to basic Quip class methods
 *
 * @package quip-test
 * @group Core
 */
class QuipClassTest extends QuipTestCase {

    /**
     * Test loading of hooks
     * @return void
     */
    public function testLoadHooks() {
        $hooks = $this->quip->loadHooks('unit');
        $this->assertInstanceOf('quipHooks',$hooks);
        $this->assertInstanceOf('quipHooks',$this->quip->unitHooks);
        $this->quip->unitHooks = null;
    }

    /**
     * Test the loading of a controller
     * @return void
     */
    public function testLoadController() {
        $controller = $this->quip->loadController('Thread');
        $this->assertInstanceOf('quipController',$controller);
        $this->assertInstanceOf('quipController',$this->quip->controller);
        $this->quip->controller = null;
    }
}