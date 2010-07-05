<?php
/**
 * Quip
 *
 * Copyright 2010 by Shaun McCormick <shaun@modxcms.com>
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
/**
 * QuipLatestComments
 *
 * Gets latest comments in a thread or by a user.
 *
 * @name QuipLatestComments
 * @author Shaun McCormick <shaun@modxcms.com>
 * @package quip
 */
$quip = $modx->getService('quip','Quip',$modx->getOption('quip.core_path',null,$modx->getOption('core_path').'components/quip/').'model/quip/',$scriptProperties);
if (!($quip instanceof Quip)) return '';

/* setup default properties */
$type = $modx->getOption('type',$scriptProperties,'all');
$tpl = $modx->getOption('tpl',$scriptProperties,'quipLatestComment');
$limit = $modx->getOption('limit',$scriptProperties,5);
$start = $modx->getOption('start',$scriptProperties,0);
$sortBy = $modx->getOption('sortBy',$scriptProperties,'createdon');
$sortByAlias = $modx->getOption('sortByAlias',$scriptProperties,'quipComment');
$sortDir = $modx->getOption('sortDir',$scriptProperties,'DESC');

$rowCss = $modx->getOption('rowCss',$scriptProperties,'quip-latest-comment');
$altRowCss = $modx->getOption('altRowCss',$scriptProperties,'quip-latest-comment-alt');
$dateFormat = $modx->getOption('dateFormat',$scriptProperties,'%b %d, %Y at %I:%M %p');
$stripTags = $modx->getOption('stripTags',$scriptProperties,true);
$bodyLimit = $modx->getOption('bodyLimit',$scriptProperties,30);

/* build query by type */
$output = '';
$c = $modx->newQuery('quipComment');
$c->leftJoin('modUser','Author');
$c->where(array(
    'quipComment.approved' => true,
));
switch ($type) {
    case 'user':
        if (empty($scriptProperties['user'])) return '';
        if (is_numeric($scriptProperties['user'])) {
            $c->where(array(
                'Author.id' => $scriptProperties['user'],
            ));
        } else {
            $c->where(array(
                'Author.username' => $scriptProperties['user'],
            ));
        }
        break;
    case 'thread':
        if (empty($scriptProperties['thread'])) return '';
        $c = $modx->newQuery('quipComment');
        $c->where(array(
            'quipComment.thread' => $scriptProperties['thread'],
        ));
        break;
    case 'all':
    default:
        break;
}
$c->where(array(
    'quipComment.deleted' => false,
));
$c->sortby('`'.$sortByAlias.'`.`'.$sortBy.'`',$sortDir);
$c->limit($limit,$start);
$comments = $modx->getCollection('quipComment',$c);

/* iterate */
$alt = false;
foreach ($comments as $comment) {
    $commentArray = $comment->toArray();
    $commentArray['bodyLimit'] = $bodyLimit;
    $commentArray['cls'] = $rowCss;
    if ($altRowCss && $alt) $commentArray['alt'] = ' '.$altRowCss;
    $commentArray['url'] = $comment->makeUrl();

    if (!empty($stripTags)) { $commentArray['body'] = strip_tags($commentArray['body']); }
    
    $commentArray['ago'] = $quip->getTimeAgo($commentArray['createdon']);
    
    $output .= $quip->getChunk($tpl,$commentArray);
    $alt = !$alt;
}

return $output;

