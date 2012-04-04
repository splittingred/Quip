<?php
/**
 * Quip
 *
 * Copyright 2010-11 by Shaun McCormick <shaun+quip@modx.com>
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
require_once dirname(__FILE__) . '/model/quip/quip.class.php';
abstract class QuipManagerController extends modExtraManagerController {
    /** @var Quip $quip */
    public $quip;
    public function initialize() {
        $this->quip = new Quip($this->modx);

        $this->addCss($this->quip->config['cssUrl'].'mgr.css');
        $this->addJavascript($this->quip->config['jsUrl'].'quip.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            Quip.config = '.$this->modx->toJSON($this->quip->config).';
            Quip.config.connector_url = "'.$this->quip->config['connectorUrl'].'";
        });
        </script>');

        $this->checkForApproval();
        $this->checkForRejection();
        return parent::initialize();
    }
    public function getLanguageTopics() {
        return array('quip:default');
    }
    public function checkPermissions() { return true;}

    public function checkForApproval() {
        if (!empty($_REQUEST['quip_approve'])) {
            /** @var quipComment $comment */
            $comment = $this->modx->getObject('quipComment',$_REQUEST['quip_approve']);
            if ($comment && $comment->approve()) {
                $commentArray = $comment->toArray();
                $commentArray['createdon'] = strftime('%b %d, %Y',strtotime($comment->get('createdon')));
                $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
        Ext.onReady(function() {
            MODx.msg.status({
                title: "'.$this->modx->lexicon('quip.comment_approved').'"
                ,message: "'.$this->modx->lexicon('quip.comment_approved_msg',$commentArray).'"
                ,delay: 5
            });
        });
        </script>');
            }
        }
    }
    public function checkForRejection() {
        if (!empty($_REQUEST['quip_reject'])) {
            /** @var quipComment $comment */
            $comment = $this->modx->getObject('quipComment',$_REQUEST['quip_reject']);
            if ($comment && $comment->reject()) {
                $commentArray = $comment->toArray();
                $commentArray['createdon'] = strftime('%b %d, %Y',strtotime($comment->get('createdon')));
                $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
        Ext.onReady(function() {
            MODx.msg.status({
                title: "'.$this->modx->lexicon('quip.comment_deleted').'"
                ,message: "'.$this->modx->lexicon('quip.comment_deleted_msg',$commentArray).'"
                ,delay: 5
            });
        });
        </script>');
            }
        }
    }
}
/**
 * @package quip
 * @subpackage controllers
 */
class IndexManagerController extends QuipManagerController {
    public static function getDefaultController() { return 'home'; }
}