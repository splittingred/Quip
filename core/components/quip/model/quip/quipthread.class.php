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
 * @package quip
 */
class quipThread extends xPDOObject {
    /** @var modX $xpdo */
    public $xpdo;
    /**
     * Checks whether or not the user has access to the specified permission.
     * 
     * @param string $permission
     * @return boolean True if user has permission
     */
    public function checkPolicy($permission) {
        $access = true;

        /* first check moderator access */
        if ($this->get('moderated')) {
            $moderatorGroups = $this->trimArray($this->get('moderator_group'));
            $moderators = $this->trimArray($this->get('moderators'));
            $inModeratorGroup = !empty($moderatorGroups) && !empty($this->xpdo->user) ? $this->xpdo->user->isMember($moderatorGroups) : false;
            $access = $inModeratorGroup || in_array($this->xpdo->user->get('username'),$moderators);
        } else {
            $access = $this->xpdo->user->isMember('Administrator');
        }

        /* now check global access */
        switch ($permission) {
            case 'view':
                $access = $this->xpdo->hasPermission('quip.thread_view');
                break;
            case 'truncate':
                $access = $this->xpdo->hasPermission('quip.thread_truncate');
                break;
            case 'remove':
                $access = $this->xpdo->hasPermission('quip.thread_remove');
                break;
            case 'comment_approve':
                $access = $this->xpdo->hasPermission('quip.comment_approve');
                break;
            case 'comment_remove':
                $access = $this->xpdo->hasPermission('quip.comment_approve');
                break;
            case 'comment_update':
                $access = $this->xpdo->hasPermission('quip.comment_approve');
                break;
        }

        return $access;
    }

    /**
     * Trims an array's values to remove whitespaces. If value passed is a string, explodes it first.
     *
     * @param array $array
     * @param string $delimiter
     * @return string
     */
    protected function trimArray($array,$delimiter = ',') {
        if (!is_array($array)) {
            $array = explode($delimiter,$array);
        }
        $ret = array();
        foreach ($array as $i) {
            $ret[] = trim($i);
        }
        return $ret;
    }
    
    /**
     * Make the URL of the Quip thread for easy reference
     *
     * @param int $resource The ID of the resource to make from
     * @param array $params Any params to add to the URL
     * @param array $options An array of options for URL building
     * @return string The created URL
     */
    public function makeUrl($resource = 0,$params = array(),array $options = array()) {
        if (empty($resource)) $resource = $this->get('resource');
        if (empty($params)) $params = $this->get('existing_params');
        if (empty($params)) $params = array();
        if (empty($options['context_key'])) {
            $options['context_key'] = $this->get('context_key');
            if (empty($options['context_key'])) {
                $options['context_key'] = $this->xpdo->context->get('key');
            }
        }

        $scheme= $this->xpdo->context->getOption('scheme','',$options);
        return $this->xpdo->makeUrl($resource,$options['context_key'],$params,$scheme);
    }

    /**
     * Sync the thread object
     *
     * @param array $scriptProperties
     * @return bool True if changed
     */
    public function sync(array $scriptProperties = array()) {
        $changed = false;
        $scriptProperties['idPrefix'] = $this->xpdo->getOption('idPrefix',$scriptProperties,'qcom');

        /* change idPrefix if set */
        if (!empty($scriptProperties['idPrefix']) && $this->get('idprefix') != $scriptProperties['idPrefix']) {
            $this->set('idprefix',$scriptProperties['idPrefix']);
            $changed = true;
        }
        /* change moderate if diff */
        if (isset($scriptProperties['moderate']) && $this->get('moderated') != $scriptProperties['moderate']) {
            $this->set('moderated',$scriptProperties['moderate']);
            $changed = true;
        }
        /* change moderators if diff */
        if (!empty($scriptProperties['moderators']) && $this->get('moderators') != $scriptProperties['moderators']) {
            $this->set('moderators',$scriptProperties['moderators']);
            $changed = true;
        }
        /* change moderatorGroup if diff */
        if (!empty($scriptProperties['moderatorGroup']) && $this->get('moderator_group') != $scriptProperties['moderatorGroup']) {
            $this->set('moderator_group',$scriptProperties['moderatorGroup']);
            $changed = true;
        }
        /* change notify_emails if diff */
        if (!empty($scriptProperties['notifyEmails']) && $this->get('notify_emails') != $scriptProperties['notifyEmails']) {
            $this->set('notify_emails',$scriptProperties['notifyEmails']);
            $changed = true;
        }

        if ($changed) {
            $this->save();
        }
        return $changed;
    }

    /**
     * Ensure that the Lexicon is loaded for the modX instance
     * 
     * @return bool True if loaded
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
     * Gets an array of emails for all moderators of this thread
     * 
     * @return array
     */
    public function getModeratorEmails() {
        $moderatorNames = $this->get('moderators');
        $moderatorNames = explode(',',$moderatorNames);
        $moderators = array();
        foreach ($moderatorNames as $name) {
            $c = $this->xpdo->newQuery('modUser');
            $c->innerJoin('modUserProfile','Profile');
            $c->select(array('modUser.id','Profile.email'));
            $c->where(array('username' => $name));
            $user = $this->xpdo->getObject('modUser',$c);
            if ($user) {
                $moderators[] = $user->get('email');
            }
        }

        /* now get usergroup moderators */
        $moderatorGroup = $this->get('moderator_group');
        $c = $this->xpdo->newQuery('modUserProfile');
        $c->innerJoin('modUser','User');
        $c->innerJoin('modUserGroupMember','UserGroupMembers','User.id = UserGroupMembers.member');
        $c->innerJoin('modUserGroup','UserGroup','UserGroup.id = UserGroupMembers.user_group');
        $c->where(array(
            'UserGroup.name' => $moderatorGroup,
        ));
        $members = $this->xpdo->getCollection('modUserProfile',$c);
        foreach ($members as $member) {
            $email = $member->get('email');
            if (!empty($email)) array_push($moderators,$email);
        }
        $moderators = array_unique($moderators);

        return $moderators;
    }

    /**
     * Sends notification to all watchers of this thread saying a new post has been made.
     *
     * @param quipComment $comment A reference to the actual comment
     * @return boolean True if successful
     */
    public function notify(quipComment &$comment) {
        if (!$this->_loadLexicon()) return false;
        $this->xpdo->lexicon->load('quip:emails');
        
        /* get the poster's email address */
        $posterEmail = false;
        $user = $comment->getOne('Author');
        if ($user) {
            $profile = $user->getOne('Profile');
            if ($profile) {
                $posterEmail = $profile->get('email');
            }
        }

        /* get email body/subject */
        $properties = $comment->toArray();
        $properties['url'] = $comment->makeUrl('',array(),array('scheme' => 'full'));
        $body = $this->xpdo->lexicon('quip.email_notify',$properties);
        $subject = $this->xpdo->lexicon('quip.email_notify_subject');

        /* send notifications */
        $success = true;
        $notifyEmails = $this->get('notify_emails');
        $emails = explode(',',$notifyEmails);

        /* send notifications to notify_emails subjects */
        if (!empty($emails)) {
            $this->sendEmail($subject,$body,$emails);
        }

        /* now send to notified users */
        $notifiees = $this->getMany('Notifications');
        /** @var quipCommentNotify $notification */
        foreach ($notifiees as $notification) {
            $email = $notification->get('email');
            /* remove invalid emails */
            if (empty($email) || strpos($email,'@') == false) {
                $notification->remove();
                continue;
            }
            /* don't notify the poster, since they posted the comment. */
            if ($posterEmail == $email) {
                continue;
            }

            $notification->send($comment,$properties);
        }

        return $success;
    }

    /**
     * Sends an email for this thread
     * @param string $subject
     * @param string $body
     * @param string $to
     * @return bool
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
     * Checks to see if the thread has been auto-closed after a specified number of days.
     *
     * @param integer $closeAfter The number of days to close the thread after.
     * @return boolean True if still open
     */
    public function checkIfStillOpen($closeAfter = 14) {
        if (empty($closeAfter)) return true;
        return ((time() - strtotime($this->get('createdon'))) / 60 / 60 / 24) <= $closeAfter;
    }

    /**
     * Truncates a thread.
     * @return boolean
     */
    public function truncate() {
        if (!$this->checkPolicy('truncate')) return false;
        
        $c = $this->xpdo->newQuery('quipComment');
        $c->where(array(
            'thread' => $this->get('name'),
        ));
        $comments = $this->xpdo->getCollection('quipComment',$c);

        $truncated = true;
        /** @var quipComment $comment */
        foreach ($comments as $comment) {
            $comment->set('deleted',true);
            $comment->set('deletedon',strftime('%Y-%m-%d %H:%M:%S'));
            if ($this->xpdo instanceof modX) {
                $comment->set('deletedby',$this->xpdo->user->get('id'));
            }
            $truncated = $comment->save();
        }
        return $truncated;
    }
}