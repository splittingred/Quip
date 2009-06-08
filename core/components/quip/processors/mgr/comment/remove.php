<?php
/**
 * @package quip
 * @subpackage processors
 */
if (!isset($_POST['id']) || $_POST['id'] == '') {
    return $modx->error->failure($modx->lexicon('quip.comment_err_ns'));
}
$comment = $modx->getObject('quipComment',$_POST['id']);
if ($comment == null) {
    return $modx->error->failure($modx->lexicon('quip.comment_err_nf'));
}

if ($comment->remove() === false) {
    return $modx->error->failure($modx->lexicon('quip.comment_err_remove'));
}

return $modx->error->success();