<?php
/**
 * Get a list of comments for a thread
 *
 * @package quip
 * @subpackage processors
 */
$limit = isset($_REQUEST['limit']);
$combo = isset($_REQUEST['combo']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'createdon';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'DESC';

$c = $modx->newQuery('quipComment');
$c->leftJoin('modUser','Author');
$c->where(array(
    'thread' => $_REQUEST['thread'],
));
$count = $modx->getCount('quipComment',$c);

$c->select('quipComment.*,
    Author.username AS username
');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($combo || $limit) {
    $c->limit($_REQUEST['limit'], $_REQUEST['start']);
}
$comments = $modx->getCollection('quipComment', $c);

$list = array();
foreach ($comments as $comment) {
    $la = $comment->toArray();
    $la['body'] = htmlentities($la['body']);

    $la['menu'] = array(
        array(
            'text' => $modx->lexicon('quip.comment_update'),
            'handler' => 'this.updateComment',
        ),
        '-',
        array(
            'text' => $modx->lexicon('quip.comment_remove'),
            'handler' => 'this.removeComment',
        ),
    );
    $list[]= $la;
}
return $this->outputArray($list,$count);