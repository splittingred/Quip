<?php
/**
 * @package quip-test
 */
/**
 * Tests for QuipReply snippet
 *
 * @package quip-test
 * @group Core
 * @group QuipReply
 */
class QuipReplyTest extends QuipTestCase {
    /**
     * @var QuipThreadReplyController $controller
     */
    public $controller;

    public function setUp() {
        parent::setUp();
        $this->controller = $this->quip->loadController('ThreadReply');
        $this->assertInstanceOf('QuipThreadReplyController',$this->controller);
    }

    /**
     * Test loading of ReCaptcha on reply form
     * 
     * @param boolean $shouldLoad
     * @param boolean $recaptcha
     * @param boolean $disableRecaptchaWhenLoggedIn
     * @param string $recaptchaTheme
     * @param boolean $userHasAuth
     * @dataProvider providerLoadReCaptcha
     */
    public function testLoadReCaptcha($shouldLoad = true,$recaptcha = true,$disableRecaptchaWhenLoggedIn = true,$recaptchaTheme = 'clean',$userHasAuth = false) {
        $this->controller->setProperty('disableRecaptchaWhenLoggedIn',$disableRecaptchaWhenLoggedIn);
        $this->controller->setProperty('recaptcha',$recaptcha);
        $this->controller->setProperty('recaptchaTheme',$recaptchaTheme);
        if ($userHasAuth) {
            $this->controller->hasAuth = true;
        }

        $success = $this->controller->loadReCaptcha();
        $this->assertTrue($success,'The ReCaptcha class failed to load.');

        if ($shouldLoad) {
            $this->assertNotEmpty($this->modx->placeholders['quip.recaptcha_html']);
        } else {
            $this->assertArrayNotHasKey('quip.recaptcha_html',$this->modx->placeholders);
        }
    }
    /**
     * @return array
     */
    public function providerLoadReCaptcha() {
        return array(
            array(true,true,true,'clean'),
            array(false,true,true,'clean',true),
            array(false,false),
        );
    }


    public function loadReCaptcha() {
        $disableRecaptchaWhenLoggedIn = (boolean)$this->getProperty('disableRecaptchaWhenLoggedIn',true,'isset');
        $useRecaptcha = (boolean)$this->getProperty('recaptcha',false,'isset');
        if ($useRecaptcha && !($disableRecaptchaWhenLoggedIn && $this->hasAuth) && !$this->hasPreview) {
            /** @var reCaptcha $recaptcha */
            $recaptcha = $this->modx->getService('recaptcha','reCaptcha',$this->quip->config['modelPath'].'recaptcha/');
            if ($recaptcha instanceof reCaptcha) {
                $recaptchaTheme = $this->getProperty('recaptchaTheme','clean');
                $html = $recaptcha->getHtml($recaptchaTheme);
                $this->modx->setPlaceholder('quip.recaptcha_html',$html);
            } else {
                return $this->modx->lexicon('quip.recaptcha_err_load');
            }
        }
        return true;
    }
}