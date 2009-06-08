<?php
/**
 * Quip
 *
 * Copyright 2009 by Shaun McCormick <shaun@collabpad.com>
 *
 * This file is part of Quip, a simpel commenting component for MODx Revolution.
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
require_once MODX_CORE_PATH . 'model/modx/modrequest.class.php';
/**
 * Runs the comment front-end system
 *
 * @package quip
 */
class QuipViewRequest extends modRequest {
    public $quip = null;

    /**
     * Constructor for the QuipViewRequest class.
     *
     * Calls modRequest::__construct as well.
     *
     * {@inheritdoc}
     *
     * @param Quip &$quip Reference to the Quip instance.
     * @return QuipViewRequest A unique class instance of this class.
     */
    function __construct(Quip &$quip) {
        parent :: __construct($quip->modx);
        $this->quip =& $quip;
    }

    /**
     * Handles the request and loads the FE.
     *
     * @access public
     * @return string The rendered content.
     */
    public function handle() {
        $output = '';

        if (empty($this->quip->config['thread'])) {
            return '';
        }

        $this->modx->regClientCSS($this->quip->config['css_url'].'web.css');
        $this->modx->regClientStartupScript($this->quip->config['js_url'].'web/quip.js');
        $this->modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        Quip.config = {
            connector: "'.$this->quip->config['connector_url'].'"
            ,resource: "'.$this->modx->resource->get('id').'"
            ,ctx: "'.$this->modx->context->get('key').'"
        };
        </script>');


        $c = $this->modx->newQuery('quipComment');
        $c->innerJoin('modUser','Author');
        $c->where(array(
            'quipComment.thread' => $this->quip->config['thread'],
        ));
        $c->select('quipComment.*,Author.username AS username');
        $c->sortby('quipComment.createdon','DESC');

        $comments = $this->modx->getCollection('quipComment',$c);
        $commentsChunk = '';
        $alt = false;
        foreach ($comments as $comment) {
            $cp = $comment->toArray('quip.com.',true);
            if ($alt) { $cp['quip.com.alt'] = 'quip-comment-alt'; }
            $dateFormat = $this->modx->getOption('dateFormat',$this->quip->config,'%b %d, %Y at %I:%M %p');
            $cp['quip.com.createdon'] = strftime($dateFormat,strtotime($comment->get('createdon')));

            if ($this->modx->user->isAuthenticated() || $this->modx->getOption('debug',$this->quip->config,false)) {
                if ($comment->get('author') == $this->modx->user->get('id')) {
                    $cp['quip.com.options'] = $this->quip->getChunk('quipCommentOptions',array(
                        'quip.comopt.id' => $comment->get('id'),
                    ));
                } else {
                    $cp['quip.com.options'] = '';
                }

                $cp['quip.com.report'] = $this->quip->getChunk('quipReport',array(
                    'quip.comrep.id' => $comment->get('id'),
                ));
            } else {
                $cp['quip.com.report'] = '';
            }

            $commentsChunk .= $this->quip->getChunk('quipComment',$cp);
            $alt = !$alt;
        }

        $addCommentChunk = '';
        if (($this->modx->user->isAuthenticated() || $this->modx->getOption('debug',$this->quip->config,false))
            && !$this->modx->getOption('closed',$this->quip->config,false)) {
            $selfurl = '';
            $addCommentChunk = $this->quip->getChunk('quipAddComment',array(
                'quip.self' => $selfurl,
                'quip.username' => $this->modx->user->get('username'),
                'quip.thread' => $this->quip->config['thread'],
            ));
        } else {
            $addCommentChunk = $this->quip->getChunk('quipLoginToComment',array());
        }

        $output = $this->quip->getChunk('quipComments',array(
            'quip.comments' => $commentsChunk,
            'quip.addcomment' => $addCommentChunk,
        ));

        return $output;
    }

}