<?php
/**
 * Quip
 *
 * Copyright 2010-11 by Shaun McCormick <shaun@modx.com>
 *
 * This file is part of Quip.
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
 * @package quip
 */
class quipComment extends xPDOSimpleObject {
    /** @var modX|xPDO $xpdo */
    public $xpdo;
    /** @var boolean $isModerator */
    public $isModerator;
    /** @var boolean $hasAuth */
    public $hasAuth;

    /**
     * Gets the current thread
     * 
     * @static
     * @param modX $modx
     * @param quipThread $thread
     * @param int $parent
     * @param string $ids
     * @param string $sortBy
     * @param string $sortByAlias
     * @param string $sortDir
     * @return array
     */
    public static function getThread(modX $modx,quipThread $thread,$parent = 0,$ids = '',$sortBy = 'rank',$sortByAlias = 'quipComment',$sortDir = 'ASC') {
        $c = $modx->newQuery('quipComment');
        $c->innerJoin('quipThread','Thread');
        $c->leftJoin('quipCommentClosure','Descendants');
        $c->leftJoin('quipCommentClosure','RootDescendant','RootDescendant.descendant = quipComment.id AND RootDescendant.ancestor = 0');
        $c->leftJoin('quipCommentClosure','Ancestors');
        $c->leftJoin('modUser','Author');
        $c->leftJoin('modResource','Resource');
        $c->where(array(
            'quipComment.thread' => $thread->get('name'),
            'quipComment.deleted' => false,
        ));
        if (!$thread->checkPolicy('moderate')) {
            $c->andCondition(array(
                'quipComment.approved' => true,
            ),null,2);
        }
        if (!empty($parent)) {
            $c->where(array(
                'Descendants.ancestor' => $parent,
            ));
        }
        $total = $modx->getCount('quipComment',$c);
        if (!empty($ids)) {
            $c->where(array(
                'Descendants.ancestor:IN' => $ids
            ));
        }
        $c->select($modx->getSelectColumns('quipComment','quipComment'));
        $c->select(array(
            'Thread.resource',
            'Thread.idprefix',
            'Thread.existing_params',
            'RootDescendant.depth',
            'Author.username',
            'Resource.pagetitle',
            'Resource.context_key',
        ));
        $c->sortby($modx->escape($sortByAlias).'.'.$modx->escape($sortBy),$sortDir);
        $comments = $modx->getCollection('quipComment',$c);
        return array(
            'results' => $comments,
            'total' => $total,
        );
    }

    /**
     * Make a custom URL For this comment.
     *
     * @param int $resource Optional. The ID of the resource to generate the comment for. Defaults to the Thread's resource.
     * @param array $params Optional. An array of REQUEST parameters to add to the URL.
     * @param array $options Optional. An array of options, which can include 'scheme' and 'idprefix'.
     * @param boolean $addAnchor Whether or not to add the idprefix+id as an anchor tag to the URL
     * @return string The generated URL
     */
    public function makeUrl($resource = 0,$params = array(),$options = array(),$addAnchor = true) {
        if (empty($resource)) $resource = $this->get('resource');
        if (empty($params)) $params = $this->get('existing_params');
        if (empty($params)) $params = array();
        if (empty($options['context_key'])) {
            $options['context_key'] = $this->get('context_key');
            if (empty($options['context_key'])) {
                $modresource = $this->xpdo->getObject('modResource', $resource);
                $options['context_key'] = $modresource->get('context_key');
            }
        }

        $scheme= $this->xpdo->context->getOption('scheme','',$options);
        $idprefix = $this->xpdo->context->getOption('idprefix',$this->get('idprefix'),$options);
        return $this->xpdo->makeUrl($resource,$options['context_key'],$params,$scheme).($addAnchor ? '#'.$idprefix.$this->get('id') : '');
    }

    /**
     * Grabs all descendants of this post.
     *
     * @access public
     * @param int $depth If set, will limit to specified depth
     * @return array A collection of quipComment objects.
     */
    public function getDescendants($depth = 0) {
        $c = $this->xpdo->newQuery('quipComment');
        $c->select($this->xpdo->getSelectColumns('quipComment','quipComment'));
        $c->select(array(
            'Descendants.depth',
        ));
        $c->innerJoin('quipCommentClosure','Descendants');
        $c->innerJoin('quipCommentClosure','Ancestors');
        $c->where(array(
            'Descendants.ancestor' => $this->get('id'),
        ));
        if ($depth) {
            $c->where(array(
                'Descendants.depth:<=' => $depth,
            ));
        }
        $c->sortby('quipComment.rank','ASC');
        return $this->xpdo->getCollection('quipComment',$c);
    }

    /**
     * Overrides xPDOObject::save to handle closure table edits.
     *
     * @param boolean $cacheFlag
     * @return boolean
     */
    public function save($cacheFlag = null) {
        $new = $this->isNew();

        if ($new) {
            if (!$this->get('createdon')) {
                $this->set('createdon', strftime('%Y-%m-%d %H:%M:%S'));
            }
            $ip = $this->get('ip');
            if (empty($ip) && !empty($_SERVER['REMOTE_ADDR'])) {
                $this->set('ip',$_SERVER['REMOTE_ADDR']);
            }
        }

        $saved = parent :: save($cacheFlag);

        if ($saved && $new) {
            $id = $this->get('id');
            $parent = $this->get('parent');

            /* create self closure */
            $cl = $this->xpdo->newObject('quipCommentClosure');
            $cl->set('ancestor',$id);
            $cl->set('descendant',$id);
            if ($cl->save() === false) {
                $this->remove();
                return false;
            }

            /* create closures and calculate rank */
            $c = $this->xpdo->newQuery('quipCommentClosure');
            $c->where(array(
                'descendant' => $parent,
                'ancestor:!=' => 0,
            ));
            $c->sortby('depth','DESC');
            $gparents = $this->xpdo->getCollection('quipCommentClosure',$c);
            $cgps = count($gparents);
            $gps = array();
            $i = $cgps;
            /** @var quipCommentClosure $gparent */
            foreach ($gparents as $gparent) {
                $gps[] = str_pad($gparent->get('ancestor'),10,'0',STR_PAD_LEFT);
                /** @var quipCommentClosure $obj */
                $obj = $this->xpdo->newObject('quipCommentClosure');
                $obj->set('ancestor',$gparent->get('ancestor'));
                $obj->set('descendant',$id);
                $obj->set('depth',$i);
                $obj->save();
                $i--;
            }
            $gps[] = str_pad($id,10,'0',STR_PAD_LEFT); /* add self closure too */

            /* add root closure */
            /** @var quipCommentClosure $cl */
            $cl = $this->xpdo->newObject('quipCommentClosure');
            $cl->set('ancestor',0);
            $cl->set('descendant',$id);
            $cl->set('depth',$cgps);
            $cl->save();

            /* set rank */
            $rank = implode('-',$gps);
            $this->set('rank',$rank);
            $this->save();
        }
        return $saved;
    }

    /**
     * Load the modLexicon service
     *
     * @return boolean
     */
    protected function _loadLexicon() {
        if (!$this->xpdo->lexicon) {
            $this->xpdo->lexicon = $this->xpdo->getService('lexicon','modLexicon');
            if (empty($this->xpdo->lexicon)) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[Quip] Could not load MODx lexicon.');
                return false;
            }
        }
        return true;
    }

    /**
     * Send an email
     *
     * @param string $subject The subject of the email
     * @param string $body The body of the email to send
     * @param string $to The email address to send to
     * @return boolean
     */
    protected function sendEmail($subject,$body,$to) {
        if (!$this->_loadLexicon()) return false;
        $this->xpdo->lexicon->load('quip:emails');
        
        $this->xpdo->getService('mail', 'mail.modPHPMailer');
        if (!$this->xpdo->mail) return false;
        
        $emailFrom = $this->xpdo->context->getOption('quip.emailsFrom',$this->xpdo->context->getOption('emailsender'));
        $emailReplyTo = $this->xpdo->context->getOption('quip.emailsReplyTo',$this->xpdo->context->getOption('emailsender'));

        /* allow multiple to addresses */
        if (!is_array($to)) {
            $to = explode(',',$to);
        }

        $success = false;
        foreach ($to as $emailAddress) {
            if (empty($emailAddress) || strpos($emailAddress,'@') == false) continue;
            
            $this->xpdo->mail->set(modMail::MAIL_BODY,$body);
            $this->xpdo->mail->set(modMail::MAIL_FROM,$emailFrom);
            $this->xpdo->mail->set(modMail::MAIL_FROM_NAME,$this->xpdo->context->getOption('quip.emails_from_name','Quip'));
            $this->xpdo->mail->set(modMail::MAIL_SENDER,$emailFrom);
            $this->xpdo->mail->set(modMail::MAIL_SUBJECT,$subject);
            $this->xpdo->mail->address('to',$emailAddress);
            $this->xpdo->mail->address('reply-to',$emailReplyTo);
            $this->xpdo->mail->setHTML(true);
            $success = $this->xpdo->mail->send();
            $this->xpdo->mail->reset();
        }
        
        return $success;
    }

    /**
     * Approves comment and sends out notification to poster and watchers
     *
     * @param array $options
     * @return boolean True if successful
     */
    public function approve(array $options = array()) {
        if (!$this->_loadLexicon()) return false;
        $this->xpdo->lexicon->load('quip:emails');

        $this->set('approved',true);
        $this->set('approvedon',strftime('%Y-%m-%d %H:%M:%S'));
        $this->set('approvedby',$this->xpdo->user->get('id'));

        /* first attempt to save/approve */
        if ($this->save() === false) {
            return false;
        }
        
        /* send email to poster saying their comment was approved */
        $properties = $this->toArray();
        $properties['url'] = $this->makeUrl('',array(),array('scheme' => 'full'));
        $body = $this->xpdo->lexicon('quip.email_comment_approved',$properties);
        $subject = $this->xpdo->lexicon('quip.email_comment_approved_subject');
        $this->sendEmail($subject,$body,$this->get('email'));

        /** @var quipThread $thread */
        $thread = $this->getOne('Thread');
        return $thread ? $thread->notify($this) : true;
    }

    /**
     * Reject a comment
     * 
     * @param array $options
     * @return boolean True if successful
     */
    public function reject(array $options = array()) {
        $this->set('deleted',true);
        $this->set('deletedon',strftime('%Y-%m-%d %H:%M:%S'));
        $this->set('deletedby',$this->xpdo->user->get('id'));

        return $this->save();
    }

    /**
     * Unapprove a comment
     * @param array $options
     * @return bool
     */
    public function unapprove(array $options = array()) {
        $this->set('approved',false);
        $this->set('approvedon','0000-00-00 00:00:00');
        $this->set('approvedby',0);
        return $this->save();
    }

    /**
     * "Delete" a comment
     * @param array $options
     * @return boolean
     */
    public function delete(array $options = array()) {
        $this->set('deleted',true);
        $this->set('deletedon',strftime('%Y-%m-%d %H:%M:%S'));
        $this->set('deletedby',$this->xpdo->user->get('id'));
        return $this->save();
    }

    /**
     * "Undelete" a comment
     * @param array $options
     * @return boolean
     */
    public function undelete(array $options = array()) {
        $this->set('deleted',false);
        $this->set('deletedon','0000-00-00 00:00:00');
        $this->set('deletedby',0);
        return $this->save();
    }

    /**
     * Sends notification email to moderators telling them the comment is awaiting approval.
     *
     * @return boolean True if successful
     */
    public function notifyModerators() {
        if (!$this->_loadLexicon()) return false;
        $this->xpdo->lexicon->load('quip:emails');
        /** @var quipThread $thread */
        $thread = $this->getOne('Thread');
        if (!$thread) return false;
        
        $properties = $this->toArray();
        $properties['url'] = $this->makeUrl('',array(),array('scheme' => 'full'));

        /**
         * Get the Quip mgr action
         * @var modAction $action
         */
        $action = $this->xpdo->getObject('modAction',array(
            'controller' => 'index',
            'namespace' => 'quip',
        ));
        if ($action) {
            $managerUrl = MODX_URL_SCHEME.MODX_HTTP_HOST.MODX_MANAGER_URL;
            $properties['approveUrl'] = $managerUrl.'?a='.$action->get('id').'&quip_unapproved=1&quip_approve='.$this->get('id');
            $properties['rejectUrl'] = $managerUrl.'?a='.$action->get('id').'&quip_unapproved=1&quip_reject='.$this->get('id');
            $properties['unapprovedUrl'] = $managerUrl.'?a='.$action->get('id').'&quip_unapproved=1';
        }

        $body = $this->xpdo->lexicon('quip.email_moderate',$properties);
        $subject = $this->xpdo->lexicon('quip.email_moderate_subject');

        $success = true;
        $moderators = $thread->getModeratorEmails();
        if (!empty($moderators)) {
            $success = $this->sendEmail($subject,$body,$moderators);
        }

        return $success;
    }

    /**
     * Prepare the comment for rendering
     * 
     * @param array $properties
     * @param int $idx
     * @return array
     */
    public function prepare(array $properties = array(),$idx) {
        $alt = $idx % 2;
        $commentArray = $this->toArray();
        $commentArray['children'] = '';
        $commentArray['alt'] = $alt ? $this->getOption('altRowCss',$properties) : '';
        $commentArray['createdon'] = strftime($this->getOption('dateFormat',$properties),strtotime($this->get('createdon')));
        $commentArray['url'] = $this->makeUrl();
        $commentArray['idx'] = $idx;
        $commentArray['threaded'] = $this->getOption('threaded',$properties,true);
        $commentArray['depth'] = $this->get('depth');
        $commentArray['depth_margin'] = $this->getOption('useMargins',$properties,false) ? (int)($this->getOption('threadedPostMargin',$properties,'15') * $this->get('depth'))+7 : '';
        $commentArray['cls'] = $this->getOption('rowCss',$properties,'').($this->get('approved') ? '' : ' '.$this->getOption('unapprovedCls',$properties,'quip-unapproved'));
        $commentArray['olCls'] = $this->getOption('olCss',$properties,'');
        if ($this->getOption('useGravatar',$properties,true)) {
            $commentArray['md5email'] = md5($this->get('email'));
            $commentArray['gravatarIcon'] = $this->getOption('gravatarIcon',$properties,'mm');
            $commentArray['gravatarSize'] = $this->getOption('gravatarSize',$properties,60);
            $urlsep = $this->xpdo->context->getOption('xhtml_urls',true) ? '&amp;' : '&';
            $commentArray['gravatarUrl'] = $this->getOption('gravatarUrl',$properties).$commentArray['md5email'].'?s='.$commentArray['gravatarSize'].$urlsep.'d='.$commentArray['gravatarIcon'];
        } else {
            $commentArray['gravatarUrl'] = '';
        }

        /* check for auth */
        if ($this->hasAuth) {
            /* allow removing of comment if moderator or own comment */
            $commentArray['allowRemove'] = $this->getOption('allowRemove',$properties,true);
            if ($commentArray['allowRemove']) {
                if ($this->isModerator) {
                    /* Always allow remove for moderators */
                    $commentArray['allowRemove'] = true;
                } else if ($this->get('author') == $this->xpdo->user->get('id')) {
                    /* if not moderator but author of post, check for remove
                     * threshold, which prevents removing comments after X minutes
                     */
                    $removeThreshold = $this->getOption('removeThreshold',$properties,3);
                    if (!empty($removeThreshold)) {
                        $diff = time() - strtotime($this->get('createdon'));
                        if ($diff > ($removeThreshold * 60)) $commentArray['allowRemove'] = false;
                    }
                }
            }

            $commentArray['reported'] = !empty($_GET['reported']) && $_GET['reported'] == $this->get('id') ? 1 : '';
            if ($this->get('author') == $this->xpdo->user->get('id') || $this->isModerator) {
                $params = $this->xpdo->request->getParameters();
                $params['quip_comment'] = $this->get('id');
                $params[$this->getOption('removeAction',$properties,'quip-remove')] = true;
                $commentArray['removeUrl'] = $this->makeUrl('',$params,null,false);
                $commentArray['options'] = $this->xpdo->quip->getChunk($this->getOption('tplCommentOptions',$properties),$commentArray);
            } else {
                $commentArray['options'] = '';
            }

            if ($this->getOption('allowReportAsSpam',$properties,true)) {
                $params = $this->xpdo->request->getParameters();
                $params['quip_comment'] = $this->get('id');
                $params[$this->getOption('reportAction',$properties,'quip-report')] = true;
                $commentArray['reportUrl'] = $this->makeUrl('',$params,null,false);
                $commentArray['report'] = $this->xpdo->quip->getChunk($this->getOption('tplReport',$properties),$commentArray);
            }
        } else {
            $commentArray['report'] = '';
        }


        /* get author display name */
        $authorTpl = $this->getOption('authorTpl',$properties,'quipAuthorTpl');
        $nameField = $this->getOption('nameField',$properties,'username');
        $commentArray['authorName'] = '';
        if (empty($commentArray[$nameField])) {
            $commentArray['authorName'] = $this->xpdo->quip->getChunk($authorTpl,array(
                'name' => $this->getOption('showAnonymousName',false)
                    ? $this->getOption('anonymousName',$this->xpdo->lexicon('quip.anonymous'))
                    : $commentArray['name'],
                'url' => '',
            ));
        } else {
            $commentArray['authorName'] = $this->xpdo->quip->getChunk($authorTpl,array(
                'name' => $commentArray[$nameField],
                'url' => '',
            ));
        }

        if ($this->getOption('showWebsite',$properties,true) && !empty($commentArray['website'])) {
            $commentArray['authorName'] = $this->xpdo->quip->getChunk($authorTpl,array(
                'name' => $commentArray[$nameField],
                'url' => $commentArray['website'],
            ));
        }

        if ($this->getOption('threaded',$properties,true) && $this->getOption('stillOpen',$properties,true)
            && $this->get('depth') < $this->getOption('maxDepth',$properties,10) && $this->get('approved')
            && !$this->getOption('closed',$properties,false)) {

            if (!$this->getOption('requireAuth',$properties,false) || $this->hasAuth) {
                $params = $this->xpdo->request->getParameters();
                $params['quip_thread'] = $this->get('thread');
                $params['quip_parent'] = $this->get('id');
                $commentArray['replyUrl'] = $this->xpdo->makeUrl($this->getOption('replyResourceId',$properties,1),'',$params);
            }
        } else {
            $commentArray['replyUrl'] = '';
        }
        return $commentArray;
    }
}