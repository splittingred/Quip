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
    /**
     * @var quipThread $thread
     */
    public $thread;

    public function setUp() {
        parent::setUp();
        $this->controller = $this->quip->loadController('ThreadReply');
        $this->assertInstanceOf('QuipThreadReplyController',$this->controller);
        error_reporting(E_ALL);

        $this->thread = $this->modx->newObject('quipThread');
        $this->thread->fromArray(array(
            'name' => 'unit-test-thread',
            'moderated' => true,
            'moderator_group' => '',
            'moderators' => '',
            'notify_emails' => false,
            'resource' => 1,
            'idprefix' => 'qcom.',
        ),'',true,true);
        $this->thread->save();

        $this->controller->setProperty('thread','unit-test-thread');
    }

    public function tearDown() {
        parent::tearDown();
        $this->thread->remove();
    }


    /**
     * @param boolean $shouldHaveAuth
     * @param boolean $shouldBeModerator
     * @param boolean $requireAuth
     * @param boolean $requireUserGroups
     * @param int $parent
     * @param boolean $debug
     * @dataProvider providerCheckPermissions
     */
    public function testCheckPermissions($shouldHaveAuth = true,$shouldBeModerator = false,$requireAuth = false,$requireUserGroups = false,$parent = 0,$debug = false) {
        $this->controller->setProperty('requireAuth',$requireAuth);
        $this->controller->setProperty('requireUsergroups',$requireUserGroups);
        $this->controller->setProperty('quip_parent',$parent);
        $this->controller->setProperty('debug',$debug);

        $this->controller->getThread();
        $this->controller->checkPermissions();

        if ($shouldBeModerator) {
            $this->assertNotEmpty($this->controller->isModerator,'User is not a moderator when they should be.');
        } else {
            $this->assertEmpty($this->controller->isModerator,'User is a moderator when they should not be.');
        }
        if ($shouldHaveAuth) {
            $this->assertNotEmpty($this->controller->hasAuth,'User is not authenticated when they should be.');
        } else {
            $this->assertEmpty($this->controller->hasAuth,'User is authenticated when they should not be.');
        }
    }
    public function providerCheckPermissions() {
        return array(
            array(true,false,false,false), /* standard setup */
            array(true,false,false,false,0,true), /* check debug mode */
            array(true,false,true,false,0,true), /* check requireAuth pass w/ debug */
            array(false,false,true,false), /* check requireAuth fail */
            array(false,false,true,'FailGroup'), /* check requireUsergroups fail */
        );
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
}