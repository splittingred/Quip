<?php
/**
 * Quip
 *
 * Copyright 2010 by Shaun McCormick <shaun@collabpad.com>
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
 * QuipCount
 *
 * Gets the total # of comments in a thread or by a user.
 *
 * @name QuipCount
 * @author Shaun McCormick <shaun@collabpad.com>
 * @package quip
 */
if (empty($scriptProperties['thread'])) { return ''; }
$quip = $modx->getService('quip','Quip',$modx->getOption('quip.core_path',null,$modx->getOption('core_path').'components/quip/').'model/quip/',$scriptProperties);
if (!($quip instanceof Quip)) return '';

$type = $modx->getOption('type',$scriptProperties,'thread');

$total = 0;
$c = $modx->newQuery('quipComment');

switch ($type) {
    case 'user':
        if (empty($scriptProperties['user'])) return 0;
        
        $c->innerJoin('modUser','Author');
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
    default:
        if (empty($scriptProperties['thread'])) return 0;

        $c->where(array(
            'thread' => $scriptProperties['thread'],
        ));
        break;
}

$c->where(array(
    'quipComment.approved' => true,
    'quipComment.deleted' => false,
));
return $modx->getCount('quipComment',$c);