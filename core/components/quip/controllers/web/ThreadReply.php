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
 * @subpackage controllers
 */
/**
 * Returns the number of comments a given thread/user/family has
 * 
 * @package quip
 * @subpackage controllers
 */
class QuipThreadReplyController extends QuipController {
    /** @var quipThread $thread */
    public $thread;

    /** @var boolean $hasAuth */
    public $hasAuth;
    /** @var boolean $isModerator */
    public $isModerator;
    /** @var boolean $hasPreview */
    public $hasPreview;

    /** @var string $parentThread */
    public $parentThread = '';

    /**
     * Initialize this controller, setting up default properties
     * @return void
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'thread' => '',

            'requireAuth' => false,
            'requireUsergroups' => false,
            'tplAddComment' => 'quipAddComment',
            'tplLoginToComment' => 'quipLoginToComment',
            'tplPreview' => 'quipPreviewComment',

            'closeAfter' => 14,
            'requirePreview' => false,
            'previewAction' => 'quip-preview',
            'postAction' => 'quip-post',
            'unsubscribeAction' => 'quip_unsubscribe',

            'allowedTags' => '<br><b><i>',
            'preHooks' => '',
            'postHooks' => '',
            'debug' => false,
        ));

        if (!empty($_REQUEST['quip_thread'])) {
            $this->setProperty('thread', strip_tags($_REQUEST['quip_thread']));
        }
    }

    /**
     * @return boolean|quipThread
     */
    public function getThread() {
        $threadName = strip_tags($this->getProperty('thread',''));
        if (empty($threadName)) return false;
        $this->thread = $this->modx->getObject('quipThread',array('name' => $threadName));
        if (empty($this->thread)) {
            $this->thread = $this->modx->newObject('quipThread');
            $this->thread->fromArray(array(
                'name' => $threadName,
                'createdon' => strftime('%Y-%m-%d %H:%M:%S',time()),
                'moderated' => $this->getProperty('moderate',0,'isset'),
                'resource' => $this->modx->resource->get('id'),
                'idprefix' => $this->getProperty('idprefix','qcom'),
            ),'',true,true);
            $this->thread->save();
        }

        /* sync properties with thread row values */
        $this->thread->sync($this->getProperties());
        $ps = $this->thread->get('quipreply_call_params');
        if (!empty($ps)) {
            $diff = array_diff_assoc($ps,$this->getProperties());
            if (empty($diff)) $diff = array_diff_assoc($this->getProperties(),$ps);
        }
        if (empty($_REQUEST['quip_thread']) && (!empty($diff) || empty($ps))) { /* only sync call params if not on threaded reply page */
            $this->thread->set('quipreply_call_params',$this->getProperties());
            $this->thread->save();
        }
        /* if in threaded reply page, get the original passing values to QuipReply in the thread's main page and use those */
        if (!empty($_REQUEST['quip_thread']) && is_array($ps) && !empty($ps)) {
            $scriptProperties = array_merge($this->getProperties(),$ps);
            $this->setProperties($scriptProperties);
        }
        unset($ps,$diff);
        return $this->thread;
    }

    public function checkPermissions() {
        /* get parent and auth */
        $requireAuth = $this->getProperty('requireAuth',false,'isset');
        $requireUsergroups = $this->getProperty('requireUsergroups',false,'isset');
        $this->parentThread = (integer) strip_tags($this->modx->getOption('quip_parent',$_REQUEST,$this->getProperty('parent',0)));
        $this->hasAuth = $this->modx->user->hasSessionContext($this->modx->context->get('key')) || $this->getProperty('debug',false,'isset') || empty($requireAuth);
        if (!empty($requireUsergroups)) {
            $requireUsergroups = explode(',',$this->getProperty('requireUsergroups',false,'isset'));
            $this->hasAuth = $this->modx->user->isMember($requireUsergroups);
        }
        $this->isModerator = $this->thread->checkPolicy('moderate');
    }

    public function process() {
        if (!$this->getThread()) return '';
        $this->checkPermissions();

        /* setup default placeholders */
        $p = $this->modx->request->getParameters();
        unset($p['reported'],$p['quip_approved']);
        $this->setPlaceholder('url',$this->modx->makeUrl($this->modx->resource->get('id'),'',$p));
        
        $this->setPlaceholder('parent',$this->parentThread);
        $this->setPlaceholder('thread',$this->thread->get('name'));
        $this->setPlaceholder('idprefix',$this->thread->get('idprefix'));
        
        /* handle POST */
        $this->hasPreview = false;
        if (!empty($_POST)) {
            $this->handlePost();
        }
        
        /* display moderated success message */
        $this->checkForModeration();
        
        $this->checkForUnSubscribe();
        
        /* if using recaptcha, load recaptcha html if user is not logged in */
        $this->loadReCaptcha();
        
        /* build reply form */
        $isOpen = $this->isOpen();
        if ($this->hasAuth && $isOpen) {
            $replyForm = $this->getReplyForm();
        } else if (!$isOpen) {
            $replyForm = $this->modx->lexicon('quip.thread_autoclosed');
        } else {
            $replyForm = $this->quip->getChunk($this->getProperty('tplLoginToComment','quipLoginToComment'),$this->getPlaceholders());
        }
        
        /* output or set to placeholder */
        $toPlaceholder = $this->getProperty('toPlaceholder',false);
        if ($toPlaceholder) {
            $this->modx->setPlaceholder($toPlaceholder,$replyForm);
            return '';
        }
        return $replyForm;

    }

    public function isOpen() {
        return $this->thread->checkIfStillOpen($this->getProperty('closeAfter',14,'isset')) && !$this->getProperty('closed',false,'isset');
    }

    public function getReplyForm() {
        $this->setPlaceholder('username',$this->modx->user->get('username'));
        $this->setPlaceholder('unsubscribe','');

        /* prefill fields */
        $profile = $this->modx->user->getOne('Profile');
        if ($profile) {
            $this->setPlaceholder('name',!empty($fields['name']) ? $fields['name'] : $profile->get('fullname'));
            $this->setPlaceholder('email',!empty($fields['email']) ? $fields['email'] : $profile->get('email'));
            $this->setPlaceholder('website',!empty($fields['website']) ? $fields['website'] : $profile->get('website'));
            $this->getUnSubscribeForm();
        }

        /* if requirePreview == false, auto-can post */
        if (!$this->getProperty('requirePreview',false,'isset')) {
            $this->setPlaceholder('can_post',true);
        }
        $this->setPlaceholders(array(
            'post_action' => $this->getProperty('postAction','quip-post'),
            'preview_action' => $this->getProperty('previewAction','quip-preview'),
            'allowed_tags' => $this->getProperty('allowedTags','<b><i><strong><em><br>'),
            'notifyChecked' => !empty($fields['notify']) ? ' checked="checked"' : '',
        ));
        return $this->quip->getChunk($this->getProperty('tplAddComment','quipAddComment'),$this->getPlaceholders());
    }

    /**
     * Load unsubscribe form for logged-in users only
     * @return boolean
     */
    public function getUnSubscribeForm() {
        if (!$this->modx->user->hasSessionContext($this->modx->context->get('key'))) return false;

        /** @var quipCommentNotify $notify */
        $notify = $this->modx->getObject('quipCommentNotify',array(
            'email' => $this->modx->user->Profile->get('email'),
            'thread' => $this->thread,
        ));
        if ($notify) {
            $this->setPlaceholder('notifyId',$notify->get('id'));
            $this->setPlaceholder('unsubscribe',$this->quip->getChunk('quipUnsubscribe',$this->getPlaceholders()));
            $params = $this->modx->request->getParameters();
            $params[$this->getProperty('unsubscribeAction','quip_unsubscribe')] = 1;
            $this->setPlaceholder('unsubscribeUrl',$this->modx->makeUrl($this->modx->resource->get('id'),'',$params));
        }
        return true;
    }

    public function handlePost() {
        $fields = array();
        $errors = array();
        foreach ($_POST as $k => $v) {
            $fields[$k] = str_replace(array('[',']'),array('&#91;','&#93;'),$v);
        }

        $fields['name'] = strip_tags($fields['name']);
        $fields['email'] = strip_tags($fields['email']);
        $fields['website'] = strip_tags($fields['website']);
        if (isset($fields['parent'])) $fields['parent'] = $this->modx->sanitizeString($fields['parent']);
        if (isset($fields['thread'])) $fields['thread'] = $this->modx->sanitizeString($fields['thread']);

        /* verify a message was posted */
        if (empty($fields['comment'])) $errors['comment'] = $this->modx->lexicon('quip.message_err_ns');
        if (empty($fields['name'])) $errors['name'] = $this->modx->lexicon('quip.name_err_ns');
        if (empty($fields['email'])) $errors['email'] = $this->modx->lexicon('quip.email_err_ns');

        if (!empty($_POST[$this->getProperty('postAction','quip-post')]) && empty($errors)) {
            /** @var quipComment $comment */
            $comment = $this->runProcessor('web/comment/create',$fields);

            if (is_object($comment) && $comment instanceof quipComment) {
                $params = $this->modx->request->getParameters();
                unset($params[$this->getProperty('postAction')],$params['quip_parent'],$params['quip_thread']);
                $params['quip_approved'] = $comment->get('approved') ? 1 : 0;
                
                /* redirect urls for custom FURL scheme  */
                $redirectToUrl = $this->getProperty('redirectToUrl','');
                $redirectTo = $this->getProperty('redirectTo','');
                if (!empty($redirectToUrl)) {
                    $url = $redirectToUrl.'?'.http_build_query($params);
                } else if (!empty($redirectTo)) {
                    $url = $this->modx->makeUrl($redirectTo,'',$params,'full');
                } else {
                    $url = $comment->makeUrl('',$params);
                }

                /* if not approved, remove # and replace with success message #
                 * since comment is not yet visible
                 */
                if (!$comment->get('approved')) {
                    $url = str_replace('#'.$this->thread->get('idprefix').$comment->get('id'),'#quip-success-'.$this->thread->get('idprefix'),$url);
                }
                $this->modx->sendRedirect($url);
            } else if (is_array($comment)) {
                $errors = array_merge($errors,$comment);
            }
            $fields[$this->getProperty('previewAction','quip-preview')] = true;
        }
        /* handle preview */
        else if (!empty($fields[$this->getProperty('previewAction','quip-preview')]) && empty($errors)) {
            $errors = $this->runProcessor('web/comment/preview',$fields);
        }
        if (!empty($errors)) {
            $placeholders['error'] = implode("<br />\n",$errors);
            foreach ($errors as $k => $v) {
                $placeholders['error.'.$k] = $v;
            }
            $this->setPlaceholders(array_merge($placeholders,$fields));
        }
    }

    /**
     * @return bool|string
     */
    public function loadReCaptcha() {
        $disableRecaptchaWhenLoggedIn = (boolean)$this->getProperty('disableRecaptchaWhenLoggedIn',true,'isset');
        $useRecaptcha = (boolean)$this->getProperty('recaptcha',false,'isset');
        $isLoggedIn = $this->modx->user->hasSessionContext($this->modx->context->get('key'));
        if ($useRecaptcha && !($disableRecaptchaWhenLoggedIn && $isLoggedIn) && !$this->hasPreview) {
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

    /**
     * Handle if the user is unsubscribing from this thread
     * @return boolean
     */
    public function checkForUnSubscribe() {
        $unSubscribed = false;
        if (!empty($_GET[$this->getProperty('unsubscribeAction')]) && $this->modx->user->hasSessionContext($this->modx->context->get('key'))) {
            $profile = $this->modx->user->getOne('Profile');
            if ($profile) {
                /** @var quipCommentNotify $notify */
                $notify = $this->modx->getObject('quipCommentNotify',array(
                    'email' => $profile->get('email'),
                    'thread' => $this->thread->get('name'),
                ));
                if ($notify && $notify->remove()) {
                    $unSubscribed = true;
                    $this->setPlaceholder('successMsg',$this->modx->lexicon('quip.unsubscribed'));
                }
            }
        }
        return $unSubscribed;
    }

    /**
     * Check to see if their comment was to be moderated; if so, display a message
     * @return void
     */
    public function checkForModeration() {
        if (isset($_GET['quip_approved']) && $_GET['quip_approved'] == 0) {
            $this->setPlaceholder('successMsg',$this->modx->lexicon('quip.comment_will_be_moderated'));
        }
    }
}
return 'QuipThreadReplyController';