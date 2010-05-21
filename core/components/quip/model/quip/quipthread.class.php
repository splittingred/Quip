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

        if ($changed) {
            $this->save();
        }
        return $changed;
    }

}