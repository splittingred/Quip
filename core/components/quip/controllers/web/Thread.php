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
 * Renders a given Quip thread
 * 
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

            'removeAction' => 'quip-remove',
            'reportAction' => 'quip-report',

            'parent' => 0,
            'thread' => '',

            'unsubscribeSecretHash' => 'One sees great things from the valley, only small things from the peak.',
        ));

        if (!empty($_REQUEST['quip_limit'])) {
            $this->setProperty('limit',$_REQUEST['quip_limit']);
        }
        if (!empty($_REQUEST['quip_start'])) {
            $this->setProperty('start',$_REQUEST['quip_start']);
        }
        if (!empty($_REQUEST['quip_parent'])) {
            $this->setProperty('parent',$_REQUEST['quip_parent']);
        }
        if (!empty($_REQUEST['quip_thread'])) {
            $this->setProperty('thread',$_REQUEST['quip_thread']);
        }
        if (!empty($_REQUEST['quip_uhsh'])) {
            $this->setProperty('quip_uhsh',$_REQUEST['quip_uhsh']);
        }
        if (!empty($_REQUEST['quip_unsub'])) {
            $this->setProperty('quip_unsub',$_REQUEST['quip_unsub']);
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
        if (!$this->thread) {
            $this->thread = $this->modx->newObject('quipThread');
            $this->thread->fromArray(array(
                'name' => $threadName,
                'createdon' => strftime('%Y-%m-%d %H:%M:%S',time()),
                'moderated' => $this->getProperty('moderate',0,'isset'),
                'resource' => $this->modx->resource->get('id'),
                'idprefix' => $this->getProperty('idprefix','qcom'),
            ),'',true,true);
            $this->thread->save();
            $this->thread->sync($this->getProperties());
        }
        if ($this->thread) {
            $closeAfter = (int)$this->getProperty('closeAfter',14,'isset');
            $open = $this->thread->checkIfStillOpen($closeAfter) && !$this->getProperty('closed',false,'isset');
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

        $this->checkForUnsubscription();

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
     * Check for any unsubscriptions to this thread
     *
     * @return boolean
     */
    public function checkForUnsubscription() {
        $unsubscribed = false;
        $email = urldecode($this->getProperty('quip_unsub'));
        $hash = urldecode($this->getProperty('quip_uhsh'));
        if (!empty($email) && !empty($hash)) {
            $unsubscribeSecretHash = $this->getProperty('unsubscribeSecretHash');
            /** @var quipCommentNotify $notification */
            $notification = $this->modx->getObject('quipCommentNotify',array(
                'thread' => $this->thread->get('name'),
                'email' => $email,
            ));
            if ($notification) {
                $expectedHash = md5('quip.'.$unsubscribeSecretHash.$email.$notification->get('createdon'));
                if (strcmp($expectedHash,$hash) == 0) {
                    if ($notification->remove()) {
                        $this->modx->setPlaceholder('successMsg',$this->modx->lexicon('quip.unsubscribed'));
                        $unsubscribed = true;
                    }
                }
            }
        }
        return $unsubscribed;
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
        $removeAction = $this->getProperty('removeAction','quip-remove');
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
        $errors = $this->runProcessor('web/comment/remove',$_POST);
        if (empty($errors)) {
            $params = $this->modx->request->getParameters();
            unset($params[$this->getProperty('removeAction','quip-remove')],$params['quip_comment']);
            $url = $this->modx->makeUrl($this->modx->resource->get('id'),'',$params,'full');
            $this->modx->sendRedirect($url);
        }
        $this->setPlaceholder('error',implode("<br />\n",$errors));
    }

    /**
     * Handle reporting of a comment as spam
     * @return void
     */
    public function reportCommentAsSpam() {
        $errors = $this->runProcessor('web/comment/report',$_POST);
        if (empty($errors)) {
            $params = $this->modx->request->getParameters();
            unset($params[$this->getProperty('reportAction','quip-report')],$params['quip_comment']);
            $params['reported'] = $_REQUEST['quip_comment'];
            $url = $this->modx->makeUrl($this->modx->resource->get('id'),'',$params,'full');
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
        if ($this->getProperty('useCss',true,'isset')) {
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
                'quipComment.deleted' => false,
                'quipComment.parent' => 0,
            ));
            if (!$this->thread->checkPolicy('moderate')) {
                $c->where(array(
                    'quipComment.approved' => true,
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
        $this->modx->loadClass('quipComment');
        $result = quipComment::getThread($this->modx,$this->thread,$parent,$this->ids,$sortBy,$sortByAlias,$sortDir);
        
        $this->comments = $result['results'];
        $this->setPlaceholder('total',$result['total']);
        return $this->comments;
    }

    /**
     * @return array
     */
    public function prepareComments() {
        $idx = 0;
        $commentList = array();
        /** @var quipComment $comment */
        foreach ($this->comments as $comment) {
            $comment->hasAuth = $this->hasAuth;
            $comment->isModerator = $this->isModerator;
            $commentArray = $comment->prepare($this->getProperties(),$idx);
            $idx++;
            $this->setPlaceholder('pagetitle',$commentArray['pagetitle']);
            $this->setPlaceholder('resource',$commentArray['resource']);
            $commentList[] = $commentArray;
            continue;

        }
        return $commentList;
    }

    /**
     * @param array $comments
     * @return string
     */
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