<?php
/**
 * Update a comment
 *
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

/* sanity checks - strip out iframe/javascript */
$body = $_POST['body'];
$body = preg_replace("/<script(.*)<\/script>/i",'',$body);
$body = preg_replace("/<iframe(.*)<\/iframe>/i",'',$body);
$body = preg_replace("/<iframe(.*)\/>/i",'',$body);

$comment->set('editedon',strftime('%Y-%m-%d %H:%M:%S'));
$comment->set('body',$body);

if ($comment->save() === false) {
    return $modx->error->failure($modx->lexicon('quip.comment_err_remove'));
}

return $modx->error->success();
