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
 * Loads the header for mgr pages.
 *
 * @package quip
 * @subpackage controllers
 */
$modx->regClientCSS($quip->config['cssUrl'].'mgr.css');
$modx->regClientStartupScript($quip->config['jsUrl'].'quip.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
Ext.onReady(function() {
    Quip.config = '.$modx->toJSON($quip->config).';
    Quip.config.connector_url = "'.$quip->config['connectorUrl'].'";
    Quip.request = '.$modx->toJSON($_GET).';
});
</script>');

if (!empty($_REQUEST['quip_approve'])) {
    $comment = $modx->getObject('quipComment',$_REQUEST['quip_approve']);
    if ($comment && $comment->approve()) {
        $commentArray = $comment->toArray();
        $commentArray['createdon'] = strftime('%b %d, %Y',strtotime($comment->get('createdon')));
        $modx->regClientStartupHTMLBlock('<script type="text/javascript">
Ext.onReady(function() {
    MODx.msg.status({
        title: "'.$modx->lexicon('quip.comment_approved').'"
        ,message: "'.$modx->lexicon('quip.comment_approved_msg',$commentArray).'"
        ,delay: 5
    });
});
</script>');
    }
}
if (!empty($_REQUEST['quip_reject'])) {
    $comment = $modx->getObject('quipComment',$_REQUEST['quip_reject']);
    if ($comment && $comment->reject()) {
        $commentArray = $comment->toArray();
        $commentArray['createdon'] = strftime('%b %d, %Y',strtotime($comment->get('createdon')));
        $modx->regClientStartupHTMLBlock('<script type="text/javascript">
Ext.onReady(function() {
    MODx.msg.status({
        title: "'.$modx->lexicon('quip.comment_deleted').'"
        ,message: "'.$modx->lexicon('quip.comment_deleted_msg',$commentArray).'"
        ,delay: 5
    });
});
</script>');
    }
}
return '';