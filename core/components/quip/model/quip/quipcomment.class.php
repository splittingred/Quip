<?php
/**
 * Quip
 *
 * Copyright 2010 by Shaun McCormick <shaun@modxcms.com>
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
    public function makeUrl($resource = 0,$params = array(),$options = array(),$addAnchor = true) {
        if (empty($resource)) $resource = $this->get('resource');
        if (empty($params)) $params = $this->get('existing_params');
        if (empty($params)) $params = array();

        $scheme= $this->xpdo->getOption('scheme',$options,'');
        $idprefix = $this->xpdo->getOption('idprefix',$options,$this->get('idprefix'));
        return $this->xpdo->makeUrl($resource,'',$params,$scheme).($addAnchor ? '#'.$idprefix.$this->get('id') : '');
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
        $c->select(array(
            'quipComment.*',
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
     * {@inheritDoc}
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
            $tableName = $this->xpdo->getTableName('quipCommentClosure');
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
            foreach ($gparents as $gparent) {
                $gps[] = str_pad($gparent->get('ancestor'),10,'0',STR_PAD_LEFT);
                $obj = $this->xpdo->newObject('quipCommentClosure');
                $obj->set('ancestor',$gparent->get('ancestor'));
                $obj->set('descendant',$id);
                $obj->set('depth',$i);
                $obj->save();
                $i--;
            }
            $gps[] = str_pad($id,10,'0',STR_PAD_LEFT); /* add self closure too */

            /* add root closure */
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

    protected function sendEmail($subject,$body,$to) {
        if (!$this->_loadLexicon()) return false;
        $this->xpdo->lexicon->load('quip:emails');
        
        $this->xpdo->getService('mail', 'mail.modPHPMailer');
        if (!$this->xpdo->mail) return false;
        
        $emailFrom = $this->xpdo->getOption('quip.emailsFrom',null,$this->xpdo->getOption('emailsender'));
        $emailReplyTo = $this->xpdo->getOption('quip.emailsReplyTo',null,$this->xpdo->getOption('emailsender'));

        /* allow multiple to addresses */
        if (!is_array($to)) {
            $to = explode(',',$to);
        }

        $success = false;
        foreach ($to as $emailAddress) {
            if (empty($emailAddress) || strpos($emailAddress,'@') == false) continue;
            
            $this->xpdo->mail->set(modMail::MAIL_BODY,$body);
            $this->xpdo->mail->set(modMail::MAIL_FROM,$emailFrom);
            $this->xpdo->mail->set(modMail::MAIL_FROM_NAME,'Quip');
            $this->xpdo->mail->set(modMail::MAIL_SENDER,'Quip');
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

        $thread = $this->getOne('Thread');
        return $thread ? $thread->notify($this) : true;
    }

    public function reject(array $options = array()) {
        $this->set('deleted',true);
        $this->set('deletedon',strftime('%Y-%m-%d %H:%M:%S'));
        $this->set('deletedby',$this->xpdo->user->get('id'));

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
        $thread = $this->getOne('Thread');
        if (!$thread) return false;
        
        $properties = $this->toArray();
        $properties['url'] = $this->makeUrl('',array(),array('scheme' => 'full'));

        /* get Quip action */
        $action = $this->xpdo->getObject('modAction',array(
            'controller' => 'index',
            'namespace' => 'quip',
        ));
        if ($action) {
            $managerUrl = MODX_URL_SCHEME.MODX_HTTP_HOST.$this->xpdo->getOption('manager_url',null,MODX_MANAGER_URL);
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

}