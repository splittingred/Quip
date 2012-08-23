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
 * Returns the latest X comments a given thread
 *
 * @package quip
 * @subpackage controllers
 */
class QuipLatestCommentsController extends QuipController {
    /**
     * Load default properties for this controller
     * @return void
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'type' => 'all',
            'tpl' => 'quipLatestComment',
            'limit' => 5,
            'start' => 0,
            'sortBy' => 'createdon',
            'sortByAlias' => 'quipComment',
            'sortDir' => 'DESC',

            'rowCss' => 'quip-latest-comment',
            'altRowCss' => 'quip-latest-comment-alt',
            'dateFormat' => '%b %d, %Y at %I:%M %p',
            'stripTags' => true,
            'bodyLimit' => 30,
            'contexts' => '',

            'outputSeparator' => "\n",
            'toPlaceholder' => false,
            'placeholderPrefix' => 'quip.latest',
        ));
    }

    /**
     * Get the latest comments and output
     * @return string
     */
    public function process() {
        $output = array();
        $alt = false;
        $rowCss = $this->getProperty('rowCss','quip-latest-comment');
        $altRowCss = $this->getProperty('altRowCss','quip-latest-comment-alt');
        $bodyLimit = $this->getProperty('bodyLimit',30);
        $tpl = $this->getProperty('tpl','quipLatestComment');

        /* Initialize page placeholders */
        $pagePlaceholders = array();
        $pagePlaceholders['resource'] = '';
        $pagePlaceholders['pagetitle'] = '';
        $placeholderPrefix = $this->getProperty('placeholderPrefix','quip.latest');

        /* Add the comments */
        $comments = $this->getComments();
        if (!empty($comments)) {
            $commentArray = array();
            /** @var quipComment $comment */
            foreach ($comments as $comment) {
                $commentArray = $comment->toArray();
                $commentArray['bodyLimit'] = $bodyLimit;
                $commentArray['cls'] = $rowCss;
                if ($altRowCss && $alt) $commentArray['alt'] = ' '.$altRowCss;
                $commentArray['url'] = $comment->makeUrl();

                if (!empty($stripTags)) {
                    $commentArray['body'] = strip_tags($commentArray['body']);
                }
                $commentArray['ago'] = $this->quip->getTimeAgo($commentArray['createdon']);
                $output[] = $this->quip->getChunk($tpl,$commentArray);
                $alt = !$alt;
            }

            $pagePlaceholders['resource'] = $commentArray['resource'];
            $pagePlaceholders['pagetitle'] = !empty($commentArray['pagetitle']) ? $commentArray['pagetitle'] : '';
        }

        $this->modx->toPlaceholders($pagePlaceholders,$placeholderPrefix);
        return $this->output($output);
    }

    /**
     * Output the rendered content
     * 
     * @param string $output
     * @return string
     */
    public function output($output) {
        $outputSeparator = $this->getProperty('outputSeparator',"\n");
        $output = implode($outputSeparator,$output);
        $toPlaceholder = $this->getProperty('toPlaceholder',false);
        if ($toPlaceholder) {
            $this->modx->setPlaceholder($toPlaceholder,$output);
            return '';
        }
        return $output;
    }

    /**
     * Get all the latest comments
     * @return array
     */
    public function getComments() {

        $c = $this->modx->newQuery('quipComment');
        $c->select($this->modx->getSelectColumns('quipComment','quipComment'));
        $c->select($this->modx->getSelectColumns('modResource','Resource','',array('pagetitle')));
        $c->leftJoin('modUser','Author');
        $c->leftJoin('modResource','Resource');
        $type = $this->getProperty('type','thread');
        switch ($type) {
            case 'user':
                $user = $this->getProperty('user',0);
                if (empty($user)) return array();
                if (intval($user) > 0) {
                    $c->where(array(
                        'Author.id' => $user,
                    ));
                } else {
                    $c->where(array(
                        'Author.username' => $user,
                    ));
                }
                break;
            case 'thread':
                $thread = $this->getProperty('thread','');
                if (empty($thread)) return array();
                $c = $this->modx->newQuery('quipComment');
                $c->where(array(
                    'quipComment.thread' => $thread,
                ));
                break;
            case 'family':
                $family = $this->getProperty('family','');
                if (empty($family)) return array();
                $c = $this->modx->newQuery('quipComment');
                $c->where(array(
                    'quipComment.thread:LIKE' => '%'.$family.'%',
                ));
                break;
            case 'all':
            default:
                break;
        }
        $contexts = $this->getProperty('contexts','');
        if (!empty($contexts)) {
            $c->where(array(
                'Resource.context_key:IN' => explode(',',$contexts),
            ));
        }
        $c->where(array(
            'quipComment.deleted' => false,
            'quipComment.approved' => true,
        ));
        $c->sortby($this->modx->escape($this->getProperty('sortByAlias','quipComment')).'.'.$this->modx->escape($this->getProperty('sortBy','createdon')),$this->getProperty('sortDir','DESC'));
        $c->limit($this->getProperty('limit',10),$this->getProperty('start',0));
        return $this->modx->getCollection('quipComment',$c);
    }
}
return 'QuipLatestCommentsController';