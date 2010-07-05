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
 * @package quip
 * @subpackage build
 */
$snippets = array();

$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'Quip',
    'description' => 'A simple commenting component.',
    'snippet' => getSnippetContent($sources['source_core'].'/snippets/snippet.quip.php'),
));
$properties = include $sources['data'].'properties/properties.quip.php';
$snippets[1]->setProperties($properties);

$snippets[2]= $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 2,
    'name' => 'QuipCount',
    'description' => 'An assistance snippet for getting thread/user comment counts.',
    'snippet' => getSnippetContent($sources['source_core'].'/snippets/snippet.quipcount.php'),
));
$properties = include $sources['data'].'properties/properties.quipcount.php';
$snippets[2]->setProperties($properties);

$snippets[3]= $modx->newObject('modSnippet');
$snippets[3]->fromArray(array(
    'id' => 3,
    'name' => 'QuipLatestComments',
    'description' => 'An assistance snippet for getting the latest comments for the whole site or a thread or user.',
    'snippet' => getSnippetContent($sources['source_core'].'/snippets/snippet.quiplatestcomments.php'),
));
$properties = include $sources['data'].'properties/properties.quiplatestcomments.php';
$snippets[3]->setProperties($properties);

$snippets[4]= $modx->newObject('modSnippet');
$snippets[4]->fromArray(array(
    'id' => 4,
    'name' => 'QuipReply',
    'description' => 'Displays a reply form for comments.',
    'snippet' => getSnippetContent($sources['source_core'].'/snippets/snippet.quipreply.php'),
));
$properties = include $sources['data'].'properties/properties.quipreply.php';
$snippets[4]->setProperties($properties);

return $snippets;