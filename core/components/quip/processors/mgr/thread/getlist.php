<?php
/**
 * Get a list of threads
 *
 * @package quip
 * @subpackage processors
 */
$limit = isset($_REQUEST['limit']);
$combo = isset($_REQUEST['combo']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('quipComment');
if ($combo || $limit) {
    $c->limit($_REQUEST['limit'], $_REQUEST['start']);
}
$c->groupby('thread');
$c->sortby('thread','ASC');
$count = $modx->getCount('quipComment',$c);
$c->select('quipComment.*,
    (SELECT COUNT(*) FROM '.$modx->getTableName('quipComment').'
     WHERE thread = quipComment.thread) AS comments
');
$threads = $modx->getCollection('quipComment', $c);

$list = array();
foreach ($threads as $thread) {
    $la = $thread->toArray();

    $la['menu'] = array(
        array(
            'text' => $modx->lexicon('quip.thread_manage'),
            'handler' => 'this.manageThread',
        ),
        '-',
        array(
            'text' => $modx->lexicon('quip.thread_truncate'),
            'handler' => 'this.truncateThread',
        )
    );
    $list[]= $la;
}
return $this->outputArray($list,$count);