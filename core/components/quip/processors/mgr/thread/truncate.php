<?php
/**
 * Completely truncate a thread of comments.
 *
 * @package quip
 * @subpackage processors
 */

$c = $modx->newQuery('quipComment');
$c->where(array(
    'thread' => $_REQUEST['thread'],
));
$comments = $modx->getCollection('quipComment',$c);

foreach ($comments as $comment) {
    $comment->remove();
}

return $modx->error->success();