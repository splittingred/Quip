<?php
/**
 * @package quip
 */
class quipThread extends xPDOObject {
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
            $access = $this->xpdo->user->isMember($moderatorGroups) || in_array($this->xpdo->user->get('username'),$moderators);
        }

        /* now check global access */
        switch ($permission) {
            case 'view':
                $access = $this->xpdo->hasPermission('quip.thread_view');
                break;
            case 'truncate':
                $access = $this->xpdo->hasPermission('quip.thread_truncate');
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
    
    
    public function makeUrl($resource = 0,$params = array(),array $options = array()) {
        if (empty($resource)) $resource = $this->get('resource');
        if (empty($params)) $params = $this->get('existing_params');
        if (empty($params)) $params = array();

        $scheme= $this->xpdo->getOption('scheme',$options,'');
        return $this->xpdo->makeUrl($resource,'',$params,$scheme);
    }

    
    public function sync(array $scriptProperties = array()) {
        $changed = false;
        $scriptProperties['idPrefix'] = $this->xpdo->getOption('idPrefix',$scriptProperties,'qcom');

        /* change idPrefix if set */
        if (!empty($scriptProperties['idPrefix']) && $this->get('idprefix') != $scriptProperties['idPrefix']) {
            $this->set('idprefix',$idPrefix);
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
        $moderators = $this->get('moderators');
        $moderators = explode(',',$moderators);

        /* now get usergroup moderators */
        $moderatorGroup = $this->get('moderator_group');
        $c = $this->xpdo->newQuery('modUserProfile');
        $c->innerJoin('modUser','User');
        $c->innerJoin('modUserGroupMember','UserGroupMembers','`User`.`id` = `UserGroupMembers`.`member`');
        $c->innerJoin('modUserGroup','UserGroup','`UserGroup`.`id` = `UserGroupMembers`.`user_group`');
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
     * @return boolean True if successful
     */
    public function notify(quipComment &$comment) {
        if (!$this->_loadLexicon()) return false;
        $this->xpdo->lexicon->load('quip:emails');

        $properties = $comment->toArray();
        $properties['url'] = $comment->makeUrl('',array(),array('scheme' => 'full'));
        $body = $this->xpdo->lexicon('quip.email_notify',$properties);
        $subject = $this->xpdo->lexicon('quip.email_notify_subject');

        /* send notifications */
        $success = true;
        $notifyEmails = $this->get('notify_emails');
        $emails = explode(',',$notifyEmails);

        $notifiees = $this->getMany('Notifications');
        foreach ($notifiees as $notified) {
            $email = $notified->get('email');
            if (empty($email) || strpos($email,'@') == false) {
                $notified->remove();
                continue;
            }
            array_push($emails,$email);
        }

        /* send notifications */
        if (!empty($emails)) {
            $this->sendEmail($subject,$body,$emails);
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
     * Checks to see if the thread has been auto-closed after a specified number of days.
     *
     * @param integer $closeAfter The number of days to close the thread after.
     * @return boolean True if still open
     */
    public function checkIfStillOpen($closeAfter = 14) {
        if (empty($closeAfter)) return true;
        return ((time() - strtotime($this->get('createdon'))) / 60 / 60 / 24) <= $closeAfter;
    }
}