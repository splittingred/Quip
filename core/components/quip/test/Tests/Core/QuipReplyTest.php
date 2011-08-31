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
     * Test getThread
     * 
     * @param bool $shouldPass
     * @param string $threadName
     * @dataProvider providerGetThread
     */
    public function testGetThread($shouldPass = true,$threadName = 'unit-test-thread') {
        $this->controller->setProperty('thread',$threadName);
        $thread = $this->controller->getThread();
        if ($shouldPass) {
            $this->assertNotEmpty($thread);
            $this->assertInstanceOf('quipThread',$thread);
        } else {
            $this->assertEmpty($thread);
        }
    }
    /**
     * @return array
     */
    public function providerGetThread() {
        return array(
            array(true,'unit-test-thread'),
        );
    }

    /**
     * @param boolean $shouldHaveAuth
     * @param boolean $shouldBeModerator
     * @param boolean $requireAuth
     * @param boolean $requireUserGroups
     * @param int $parent
     * @param boolean $debug
     * @dataProvider providerCheckPermissions
     * @depends testGetThread
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
    /**
     * @return array
     */
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
     * Test for openness of thread
     * 
     * @param boolean $shouldPass
     * @param boolean $closed
     * @param int $closeAfter
     * @param int $setCreatedToDaysAway
     * @dataProvider providerIsOpen
     * @depends testGetThread
     */
    public function testIsOpen($shouldPass = true,$closed = false,$closeAfter = 14,$setCreatedToDaysAway = 0) {
        $this->controller->setProperty('closed',$closed);
        $this->controller->setProperty('closeAfter',$closeAfter);
        $this->controller->getThread();
        $secondsAway = $setCreatedToDaysAway * 24 * 60 * 60;
        $this->controller->thread->set('createdon',(time() - $secondsAway));
        $isOpen = $this->controller->isOpen();

        $this->assertEquals($shouldPass,$isOpen);
    }
    public function providerIsOpen() {
        return array(
            array(true),
            array(false,true), /* assert closed */
            array(false,false,14,15), /* assert closeAfter fails after days */
            array(true,false,14,13), /* assert closeAfter works if within */
            array(true,false,0,21), /* assert closeAfter works if 0 */
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

    /**
     * Test check for moderated comment after post
     * 
     * @param boolean $shouldBeSet
     * @param boolean $approved
     * @return void
     */
    public function testCheckForModeration($shouldBeSet = true,$approved = false) {
        $_GET['quip_approved'] = $approved;
        $this->controller->checkForModeration();
        if ($shouldBeSet) {
            $this->assertArrayHasKey('successMsg',$this->controller->getPlaceholders());
        } else {
            $this->assertArrayNotHasKey('successMsg',$this->controller->getPlaceholders());
        }
        unset($_GET['quip_approved']);
    }
    /**
     * @return array
     */
    public function providerCheckForModeration() {
        return array(
            array(true,false), /* comment moderated */
            array(false,true), /* coment not moderated */
        );
    }
}