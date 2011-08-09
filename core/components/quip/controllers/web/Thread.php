<?php
/**
 * @package quip
 * @subpackage controllers
 */
class QuipThreadController extends QuipController {
    /** @var quipThread $thread */
    public $thread;
    /** @var string $this->threadKey */
    public $threadKey;

    /** @var array $ids */
    public $ids = array();
    /** @var array $comments */
    public $comments = array();

    /** @var boolean $hasAuth */
    public $hasAuth = false;
    /** @var boolean $isModerator */
    public $isModerator = false;

    /**
     * Initialize this controller, setting up default properties
     * @return void
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'tplComment' => 'quipComment',
            'tplCommentOptions' => 'quipCommentOptions',
            'tplComments' => 'quipComments',
            'tplReport' => 'quipReport',

            'rowCss' => 'quip-comment',
            'altRowCss' => 'quip-comment-alt',
            'olCss' => 'quip-comment-parent',
            'unapprovedCss' => 'quip-unapproved',

            'dateFormat' => '%b %d, %Y at %I:%M %p',
            'showWebsite' => true,
            'idPrefix' => 'qcom',
            'resource' => '',

            'moderate' => false,
            'moderators' => false,
            'moderatorGroup' => false,
            'requireAuth' => false,
            'requireUsergroups' => false,

            'threaded' => true,
            'threadedPostMargin' => 15,
            'useMargins' => false,
            'maxDepth' => 5,
            'replyResourceId' => $this->modx->resource->get('id'),

            'closeAfter' => 14,
            'useGravatar' => true,
            'gravatarIcon' => 'identicon',
            'gravatarSize' => 50,
            'gravatarUrl' => 'http://www.gravatar.com/avatar/',

            'sortBy' => 'rank',
            'sortByAlias' => 'quipComment',
            'sortDir' => 'ASC',
            'limit' => 0,
            'offset' => 0,

            'parent' => 0,
            'thread' => '',
        ));

        if (!empty($_REQUEST['quip_limit'])) {
            $this->setProperty('limit',$_REQUEST['quip_limit']);
        }
        if (!empty($_REQUEST['quip_start'])) {
            $this->setProperty('offset',$_REQUEST['quip_start']);
        }
        if (!empty($_REQUEST['quip_parent'])) {
            $this->setProperty('parent',$_REQUEST['quip_parent']);
        }
        if (!empty($_REQUEST['quip_thread'])) {
            $this->setProperty('thread',$_REQUEST['quip_thread']);
        }
    }

    /**
     * Get and load the thread
     * @return bool|quipThread
     */
    public function getThread() {
        $threadName = $this->getProperty('thread','');
        if (empty($threadName)) return false;
        
        /** @var quipThread $thread */
        $this->thread = $this->modx->getObject('quipThread',array(
            'name' => $threadName,
        ));
        if ($this->thread) {
            $closeAfter = (int)$this->getProperty('closeAfter',14,'isset');
            $open = $this->thread->checkIfStillOpen($closeAfter) && !$this->getProperty('closed',false);
            $this->setProperty('stillOpen',$open);
        }
        return $this->thread;
    }

    /**
     * Process and load the Thread
     * @return string
     */
    public function process() {
        $this->setPlaceholders(array(
            'comment' => '',
            'error' => '',
        ));
        if (!$this->getThread()) return '';

        $this->setThreadCallParameters();
        $this->sync();
        $this->checkPermissions();
        $this->handleActions();
        $this->loadCss();

        /* set idprefix */
        $this->setPlaceholder('idprefix',$this->thread->get('idprefix'));

        $this->preparePaginationIds();
        $this->getComments();

        $comments = $this->prepareComments();
        $content = $this->render($comments);

        $this->buildPagination();
        $content = $this->wrap($content);

        return $this->output($content);
    }

    /**
     * Wrap the thread in a tpl, if specified
     * @param string $output
     * @return string
     */
    public function wrap($output) {
        if ($this->getProperty('useWrapper',true)) {
            $tpl = $this->getProperty('tplComments','quipComments');
            $placeholders = $this->getPlaceholders();
            $placeholders['comments'] = $output;
            $output = $this->quip->getChunk($tpl,$placeholders);
        }
        return $output;
    }

    /**
     * Output the content of this Thread
     * @param string $content
     * @return string
     */
    public function output($content) {
        /* output */
        $pagePlaceholders = $this->getPlaceholders();
        $placeholderPrefix = $this->getProperty('placeholderPrefix','quip');
        $this->modx->toPlaceholders($pagePlaceholders,$placeholderPrefix);
        $toPlaceholder = $this->getProperty('toPlaceholder',false);
        if ($toPlaceholder) {
            $this->modx->setPlaceholder($toPlaceholder,$content);
            return '';
        }
        return $content;
    }

    /**
     * Build the pagination for the thread
     * @return void
     */
    public function buildPagination() {
        $limit = $this->getProperty('limit',0);
        if (!empty($limit)) {
            $url = $this->modx->makeUrl($this->modx->resource->get('id'));
            $params = array_merge($this->getProperties(),array(
                'count' => $this->getPlaceholder('rootTotal',0),
                'limit' => $limit,
                'start' => $this->getProperty('start',0),
                'url' => $url,
            ));
            $this->setPlaceholder('pagination',$this->quip->buildPagination($params));
        }
    }

    /**
     * Set the Quip call parameters to the thread to enable thread behavior syncing
     * @return void
     */
    public function setThreadCallParameters() {
        if ($this->thread) {
            $ps = $this->thread->get('quip_call_params');
            if (!empty($ps)) {
                $diff = array_diff($ps,$this->getProperties());
                if (empty($diff)) {
                    $diff = array_diff_assoc($this->getProperties(),$ps);
                }
            }
            if (!empty($diff) || empty($ps)) {
                $this->thread->set('quip_call_params',$this->getProperties());
                $this->thread->save();
            }
        }
    }

    /**
     * Sync the call parameters to the thread
     * @return void
     */
    public function sync() {
        /* ensure thread exists, set thread properties if changed
         * (prior to 0.5.0 threads will be handled in install resolver) */
        if (!$this->thread) {
            $this->thread = $this->modx->newObject('quipThread');
            $this->thread->set('name',$this->threadKey);
            $this->thread->set('createdon',strftime('%Y-%m-%d %H:%M:%S'));
            $this->thread->set('moderated',$this->config['moderate']);
            $this->thread->set('moderator_group',$this->config['moderatorGroup']);
            $this->thread->set('moderators',$this->config['moderators']);
            $this->thread->set('resource',$this->getProperty('resource',$this->modx->resource->get('id')));
            $this->thread->set('idprefix',$this->getProperty('idPrefix','qcom'));
            $this->thread->set('quip_call_params',$this->scriptProperties);
            if (!empty($this->scriptProperties['moderatorGroup'])) $this->thread->set('moderator_group',$this->scriptProperties['moderatorGroup']);
            /* save existing parameters to comment to preserve URLs */
            $p = $this->modx->request->getParameters();
            unset($p['reported'],$p['quip_start'],$p['quip_limit']);
            $this->thread->set('existing_params',$p);
            $this->thread->save();
        } else {
            /* sync properties with thread row values */
            $this->thread->sync($this->getProperties());
        }
    }

    /**
     * Check to see what permissions this user has
     * @return void
     */
    public function checkPermissions() {
        $this->isModerator = $this->thread->checkPolicy('moderate');
        $this->hasAuth = $this->modx->user->hasSessionContext($this->modx->context->get('key')) || $this->getProperty('debug',false);
        $requireUsergroups = $this->getProperty('requireUsergroups',false);
        if (!empty($requireUsergroups)) {
            $requireUsergroups = explode(',',$requireUsergroups);
            $this->hasAuth = $this->modx->user->isMember($requireUsergroups);
        }
    }

    /**
     * Handle any POST actions to this page
     * @return void
     */
    public function handleActions() {
        /* handle remove post */
        $removeAction = $this->getProperty('removeAction','quip_remove');
        if (!empty($_REQUEST[$removeAction]) && $this->hasAuth && $this->isModerator) {
            $this->removeComment();
        }
        /* handle report spam */
        $reportAction = $this->getProperty('reportAction','quip_report');
        if (!empty($_REQUEST[$reportAction]) && $this->getProperty('allowReportAsSpam',true) && $this->hasAuth) {
            $this->reportCommentAsSpam();
        }
    }

    /**
     * Handle removing a comment
     * @return void
     */
    public function removeComment() {
        $errors = include_once $this->quip->config['processorsPath'].'web/comment/remove.php';
        if (empty($errors)) {
            $params = $this->modx->request->getParameters();
            unset($params[$removeAction],$params['quip_comment']);
            $url = $this->modx->makeUrl($this->modx->resource->get('id'),'',$params);
            $this->modx->sendRedirect($url);
        }
        $this->setPlaceholder('error',implode("<br />\n",$errors));
    }

    /**
     * Handle reporting of a comment as spam
     * @return void
     */
    public function reportCommentAsSpam() {
        $errors = include_once $this->quip->config['processorsPath'].'web/comment/report.php';
        if (empty($errors)) {
            $params = $this->modx->request->getParameters();
            unset($params[$reportAction],$params['quip_comment']);
            $params['reported'] = $_POST['id'];
            $url = $this->modx->makeUrl($this->modx->resource->get('id'),'',$params);
            $this->modx->sendRedirect($url);
        }
        $this->setPlaceholder('error',implode("<br />\n",$errors));
    }

    /**
     * Load any CSS for the page
     * @return void
     */
    public function loadCss() {
        /* if css, output */
        if ($this->getProperty('useCss',true)) {
            $this->modx->regClientCSS($this->quip->config['cssUrl'].'web.css');
        }
    }

    /**
     * Prepare a list of Comment ids for pagination
     * @return array
     */
    public function preparePaginationIds() {
        /* if pagination is on, get IDs of root comments so can limit properly */
        $this->ids = array();
        $limit = $this->getProperty('limit',0);
        if (!empty($limit)) {
            $c = $this->modx->newQuery('quipComment');
            $c->select($this->modx->getSelectColumns('quipComment','quipComment','',array('id')));
            $c->where(array(
                'quipComment.thread' => $this->thread->get('name'),
                'quipComment.deleted' => 0,
                'quipComment.parent' => 0,
            ));
            if (!$this->thread->checkPolicy('moderate')) {
                $c->where(array(
                    'quipComment.approved' => 1,
                    'OR:quipComment.author:=' => $this->modx->user->get('id'),
                ));
            }

            $c->sortby($this->getProperty('sortByAlias','quipComment').'.'.$this->getProperty('sortBy','rank'),$this->getProperty('sortDir','ASC'));
            $this->setPlaceholder('rootTotal',$this->modx->getCount('quipComment',$c));
            $c->limit($limit,$this->getProperty('start',0));
            $comments = $this->modx->getCollection('quipComment',$c);
            $this->ids = array();
            /** @var quipComment $comment */
            foreach ($comments as $comment) {
                $this->ids[] = $comment->get('id');
            }
            $this->ids = array_unique($this->ids);
        }
        return $this->ids;
    }

    /**
     * Get the comments for this Thread
     * @return array
     */
    public function getComments() {
        $parent = $this->getProperty('parent',0);
        $sortBy = $this->getProperty('sortBy','rank');
        $sortByAlias = $this->getProperty('sortByAlias','quipComment');
        $sortDir = $this->getProperty('sortDir','ASC');
        $result = quipComment::getThread($this->modx,$this->thread,$parent,$this->ids,$sortBy,$sortByAlias,$sortDir);
        
        $this->comments = $result['results'];
        $this->setPlaceholder('total',$result['total']);
        return $this->comments;
    }

    /**
     * @return array
     */
    public function prepareComments() {
        $alt = false;
        $idx = 0;
        $commentList = array();
        /** @var quipComment $comment */
        foreach ($this->comments as $comment) {
            $commentArray = $comment->toArray();
            $commentArray['children'] = '';
            $commentArray['alt'] = $alt ? $this->getProperty('altRowCss') : '';
            $commentArray['createdon'] = strftime($this->getProperty('dateFormat'),strtotime($comment->get('createdon')));
            $commentArray['url'] = $comment->makeUrl();
            $commentArray['idx'] = $idx;
            $commentArray['threaded'] = $this->getProperty('threaded',true);
            $commentArray['depth'] = $comment->get('depth');
            $commentArray['depth_margin'] = $this->getProperty('useMargins',false) ? (int)($this->getProperty('threadedPostMargin','15') * $comment->get('depth'))+7 : '';
            $commentArray['cls'] = $this->getProperty('rowCss').($comment->get('approved') ? '' : ' '.$this->getProperty('unapprovedCls','quip-unapproved'));
            $commentArray['olCls'] = $this->getProperty('olCss');
            if ($this->getProperty('useGravatar',true)) {
                $commentArray['md5email'] = md5($comment->get('email'));
                $commentArray['gravatarIcon'] = $this->getProperty('gravatarIcon');
                $commentArray['gravatarSize'] = $this->getProperty('gravatarSize');
                $urlsep = $this->modx->getOption('xhtml_urls',null,true) ? '&amp;' : '&';
                $commentArray['gravatarUrl'] = $this->getProperty('gravatarUrl').$commentArray['md5email'].'?s='.$commentArray['gravatarSize'].$urlsep.'d='.$commentArray['gravatarIcon'];
            } else {
                $commentArray['gravatarUrl'] = '';
            }

            /* check for auth */
            if ($this->hasAuth) {
                /* allow removing of comment if moderator or own comment */
                $commentArray['allowRemove'] = $this->getProperty('allowRemove',true);
                if ($commentArray['allowRemove']) {
                    if ($this->isModerator) {
                        /* Always allow remove for moderators */
                        $commentArray['allowRemove'] = true;
                    } else if ($comment->get('author') == $this->modx->user->get('id')) {
                        /* if not moderator but author of post, check for remove
                         * threshold, which prevents removing comments after X minutes
                         */
                        $removeThreshold = $this->getProperty('removeThreshold',3);
                        if (!empty($removeThreshold)) {
                            $diff = time() - strtotime($comment->get('createdon'));
                            if ($diff > ($removeThreshold * 60)) $commentArray['allowRemove'] = false;
                        }
                    }
                }

                $commentArray['reported'] = !empty($_GET['reported']) && $_GET['reported'] == $comment->get('id') ? 1 : '';
                if ($comment->get('author') == $this->modx->user->get('id') || $this->isModerator) {
                    $params = $this->modx->request->getParameters();
                    $params['quip_comment'] = $comment->get('id');
                    $params[$this->getProperty('removeAction')] = true;
                    $commentArray['removeUrl'] = $comment->makeUrl('',$params,null,false);
                    $commentArray['options'] = $this->quip->getChunk($this->getProperty('tplCommentOptions'),$commentArray);
                } else {
                    $commentArray['options'] = '';
                }

                if ($this->getProperty('allowReportAsSpam',true)) {
                    $params = $this->modx->request->getParameters();
                    $params['quip_comment'] = $comment->get('id');
                    $params[$this->getProperty('reportAction')] = true;
                    $commentArray['reportUrl'] = $comment->makeUrl('',$params,null,false);
                    $commentArray['report'] = $this->quip->getChunk($this->getProperty('tplReport'),$commentArray);
                }
            } else {
                $commentArray['report'] = '';
            }


            /* get author display name */
            $authorTpl = $this->getProperty('authorTpl','quipAuthorTpl');
            $nameField = $this->getProperty('nameField','username');
            $commentArray['authorName'] = '';
            if (empty($commentArray[$nameField])) {
                $commentArray['authorName'] = $this->quip->getChunk($authorTpl,array(
                    'name' => $this->getProperty('showAnonymousName',false)
                        ? $this->getProperty('anonymousName',$this->modx->lexicon('quip.anonymous'))
                        : $commentArray['name'],
                    'url' => '',
                ));
            } else {
                $commentArray['authorName'] = $this->quip->getChunk($authorTpl,array(
                    'name' => $commentArray[$nameField],
                    'url' => '',
                ));
            }

            if ($this->getProperty('showWebsite',true) && !empty($commentArray['website'])) {
                $commentArray['authorName'] = $this->quip->getChunk($authorTpl,array(
                    'name' => $commentArray[$nameField],
                    'url' => $commentArray['website'],
                ));
            }

            if ($this->getProperty('threaded') && $this->getProperty('stillOpen') && $comment->get('depth') < $this->getProperty('maxDepth') && $comment->get('approved')
                && !$this->getProperty('closed',false)) {
                if (!$this->getProperty('requireAuth',false) || $this->hasAuth) {
                    $params = $this->modx->request->getParameters();
                    $params['quip_thread'] = $comment->get('thread');
                    $params['quip_parent'] = $comment->get('id');
                    $commentArray['replyUrl'] = $this->modx->makeUrl($this->getProperty('replyResourceId'),'',$params);
                }
            } else {
                $commentArray['replyUrl'] = '';
            }
            $commentList[] = $commentArray;
            $alt = !$alt;
            $idx++;
            $this->setPlaceholder('pagetitle',$commentArray['pagetitle']);
            $this->setPlaceholder('resource',$commentArray['resource']);
            unset($commentArray);
        }
        return $commentList;
    }

    public function render(array $comments = array()) {
        $list = array();
        if ($this->getProperty('useMargins',false)) {
            foreach ($comments as $commentArray) {
                $list[] = $this->quip->getChunk($this->getProperty('commentTpl'),$commentArray);
            }
        } else {
            if ($this->modx->loadClass('QuipTreeParser',$this->quip->config['modelPath'].'quip/',true,true)) {
                $this->quip->treeParser = new QuipTreeParser($this->quip);

                $list[] = $this->quip->treeParser->parse($comments,$this->getProperty('tplComment','quipComment'));
            }
        }
        return implode("\n",$list);
    }
}
return 'QuipThreadController';