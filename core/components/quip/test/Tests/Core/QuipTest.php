<?php
/**
 * @package quip-test
 */
/**
 * Tests for Quip snippet
 *
 * @package quip-test
 * @group Core
 * @group Quip
 */
class QuipTest extends QuipTestCase {
    /**
     * @var QuipThreadController $controller
     */
    public $controller;
    /**
     * @var quipThread $thread
     */
    public $thread;
    /**
     * @var modResource $resource
     */
    public $resource;

    public function setUp() {
        parent::setUp();
        $this->controller = $this->quip->loadController('Thread');
        $this->assertInstanceOf('QuipThreadController',$this->controller);

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

        /** @var quipComment $post */
        $post = $this->modx->newObject('quipComment');
        $post->fromArray(array(
            'id' => 12345,
            'thread' => 'unit-test-thread',
            'rank' => '0000000001',
            'author' => $this->modx->user->get('id'),
            'body' => 'A test comment.',
            'createdon' => strftime('%Y-%m-%d %H:%M:%S'),
            'approved' => true,
            'approvedon' => strftime('%Y-%m-%d %H:%M:%S'),
            'approvedby' => $this->modx->user->get('id'),
            'parent' => 0,
            'name' => 'Mr. Tester',
            'email' => 'tester@example.com',
            'website' => 'http://modx.com/',
            'ip' => '127.0.0.1',
            'deleted' => false,
            'resource' => 123456,
        ),'',true,true);
        $post->save();

        $this->modx->resource = $this->modx->newObject('modResource');
        $this->modx->resource->fromArray(array(
            'id' => 123456,
            'pagetitle' => 'Quip Unit Test Resource',
            'alias' => 'quip-unit-test',
            'class_key' => 'modDocument',
            'hidemenu' => false,
        ),'',true,true);
        $this->modx->resource->save();
        $this->resource =& $this->modx->resource;

        $this->controller->setProperty('thread','unit-test-thread');
    }

    public function tearDown() {
        parent::tearDown();
        $this->thread->remove();
        $this->resource->remove();
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
     * Test the checkPermissions method for the thread
     * 
     * @param boolean $shouldHaveAuth
     * @param boolean $shouldBeModerator
     * @param boolean $requireUserGroups
     * @param boolean $debug
     * @dataProvider providerCheckPermissions
     * @depends testGetThread
     */
    public function testCheckPermissions($shouldHaveAuth = true,$shouldBeModerator = false,$requireUserGroups = false,$debug = false) {
        $this->controller->setProperty('requireUsergroups',$requireUserGroups);
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
            array(true,false,'',true), /* check debug mode */
            array(false,false,'FailGroup'), /* check requireUsergroups fail */
        );
    }

    /**
     * Ensure loadCss properly loads the CSS scripts
     *
     * @param boolean $shouldLoad
     * @param boolean $useCss
     * @dataProvider providerLoadCss
     */
    public function testLoadCss($shouldLoad = true,$useCss = true) {
        $this->controller->loadCss();
        if ($shouldLoad) {
            $this->assertNotEmpty($this->modx->sjscripts);
        } else {
            $this->assertEmpty($this->modx->sjscripts);
        }
        $this->modx->sjscripts = array();
    }
    /**
     * @return array
     */
    public function providerLoadCss() {
        return array(
            array(true,true),
            array(false,false),
        );
    }

    /**
     * @param int $limit
     * @param string $sortByAlias
     * @param string $sortBy
     * @param string $sortDir
     * @dataProvider providerPreparePaginationIds
     * @depends testGetThread
     */
    public function testPreparePaginationIds($limit = 10,$sortByAlias = 'quipComment',$sortBy = 'rank',$sortDir = 'ASC') {
        $this->controller->setProperty('limit',$limit);
        $this->controller->setProperty('sortByAlias',$sortByAlias);
        $this->controller->setProperty('sortBy',$sortBy);
        $this->controller->setProperty('sortDir',$sortDir);

        $this->controller->getThread();
        $ids = $this->controller->preparePaginationIds();

        $this->assertNotEmpty($ids,'Pagination IDs array was not set.');
        $this->assertArrayHasKey('rootTotal',$this->controller->getPlaceholders(),'rootTotal placeholder was not set.');
        $this->assertContains(12345,$ids,'ID of suspected Comment was in there.');
    }
    /**
     * @return array
     */
    public function providerPreparePaginationIds() {
        return array(
            array(10),
        );
    }

    /**
     * @param boolean $shouldPass
     * @param int $parent
     * @param string $sortBy
     * @param string $sortByAlias
     * @param string $sortDir
     * @dataProvider providerGetComments
     * @depends testGetThread
     * @depends testCheckPermissions
     */
    public function testGetComments($shouldPass = true,$parent = 0,$sortBy = 'rank',$sortByAlias = 'quipComment',$sortDir = 'ASC') {
        $this->controller->setProperty('parent',$parent);
        $this->controller->setProperty('sortBy',$sortBy);
        $this->controller->setProperty('sortByAlias',$sortByAlias);
        $this->controller->setProperty('sortDir',$sortDir);

        $this->controller->getThread();
        $this->controller->checkPermissions();
        $this->controller->preparePaginationIds();
        $comments = $this->controller->getComments();

        $phs = $this->controller->getPlaceholders();
        if ($shouldPass) {
            $this->assertNotEmpty($phs['total'],'The total was 0, when should have been greater than zero.');
            $this->assertNotEmpty($comments,'Comments were not found when should have been.');
        } else {
            $this->assertEmpty($phs['total'],'Total was greater than 0 when should not have been.');
            $this->assertEmpty($comments,'Comments were found when should not have been.');
        }

    }
    /**
     * @return array
     */
    public function providerGetComments() {
        return array(
            array(true),
            array(false,1),
        );
    }

    /**
     * @param bool $hasAuth
     * @param bool $isModerator
     * @dataProvider providerPrepareComments
     */
    public function testPrepareComments($hasAuth = true,$isModerator = false) {
        $this->controller->getThread();
        $this->controller->getComments();
        $this->controller->hasAuth = $hasAuth;
        $this->controller->isModerator = $isModerator;
        $commentList = $this->controller->prepareComments();

        $this->assertNotEmpty($commentList);
        $this->assertArrayHasKey('pagetitle',$this->controller->getPlaceholders());
        $this->assertArrayHasKey('resource',$this->controller->getPlaceholders());
    }
    /**
     * @return array
     */
    public function providerPrepareComments() {
        return array(
            array(true,false),
        );
    }

    /**
     * @param bool $shouldPass
     * @param int $total
     * @param int $limit
     * @param int $start
     * @dataProvider providerBuildPagination
     * @depends testGetThread
     */
    public function testBuildPagination($shouldPass = true,$total = 10,$limit = 10,$start = 0) {
        $this->controller->setPlaceholder('rootTotal',$total);
        $this->controller->setProperty('limit',$limit);
        $this->controller->setProperty('start',$start);
        $this->controller->getThread();
        $this->controller->buildPagination();

        if ($shouldPass) {
            $this->assertArrayHasKey('pagination',$this->controller->getPlaceholders());
        } else {
            $this->assertArrayNotHasKey('pagination',$this->controller->getPlaceholders());
        }
    }
    /**
     * @return array
     */
    public function providerBuildPagination() {
        return array(
            array(true),
            array(false,10,0),
            array(true,100,10),
            array(true,100,1000),
        );
    }

    /**
     * Test the output method
     * 
     * @param boolean $toPlaceholder
     * @param string $placeholderPrefix
     * @dataProvider providerOutput
     */
    public function testOutput($toPlaceholder = false,$placeholderPrefix = 'quip') {
        $this->controller->setProperty('toPlaceholder',$toPlaceholder);
        $this->controller->setProperty('placeholderPrefix',$placeholderPrefix);
        $this->controller->setPlaceholder('rootTotal',10);

        $output = $this->controller->output('Test');

        $this->assertArrayHasKey($placeholderPrefix.'.rootTotal',$this->modx->placeholders);
        if (!empty($toPlaceholder)) {
            $this->assertEmpty($output);
            $this->assertArrayHasKey($toPlaceholder,$this->modx->placeholders);
        } else {
            $this->assertNotEmpty($output);
        }
    }
    /**
     * @return array
     */
    public function providerOutput() {
        return array(
            array(false),
            array('comments','quip'),
        );
    }
}