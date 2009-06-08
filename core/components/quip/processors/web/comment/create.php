<?php
/**
 * Create a comment
 *
 * @package quip
 * @subpackage processors
 */
if (!isset($_POST['body']) || $_POST['body'] == '') {
    return $modx->error->failure($modx->lexicon('quip.message_err_ns'));
}
if (!isset($_POST['thread']) || $_POST['thread'] == '') {
    return $modx->error->failure($modx->lexicon('quip.thread_err_ns'));
}

/* sanity checks - strip out iframe/javascript */
$body = $_POST['body'];
$body = preg_replace("/<script(.*)<\/script>/i",'',$body);
$body = preg_replace("/<iframe(.*)<\/iframe>/i",'',$body);
$body = preg_replace("/<iframe(.*)\/>/i",'',$body);

$comment = $modx->newObject('quipComment');
$comment->set('body',$body);
$comment->set('thread',$_POST['thread']);
$comment->set('createdon',strftime('%Y-%m-%d %H:%M:%S'));
$comment->set('username',$modx->user->get('username'));
$comment->set('author',$modx->user->get('id'));

if ($comment->save() == false) {
    return $modx->error->failure($modx->lexicon('quip.comment_err_save'));
}

$cp = $comment->toArray('quip.com.',true);
$dateFormat = $this->modx->getOption('dateFormat',$this->quip->config,'%b %d, %Y at %I:%M %p');
$cp['quip.com.createdon'] = strftime($dateFormat,strtotime($comment->get('createdon')));

if ($comment->get('author') == $modx->user->get('id')) {
    $cp['quip.com.options'] = $modx->quip->getChunk('quipCommentOptions',array(
        'quip.comopt.id' => $comment->get('id'),
    ));
} else {
    $cp['quip.com.options'] = '';
}

$cp['quip.com.report'] = $modx->quip->getChunk('quipReport',array(
    'quip.comrep.id' => $comment->get('id'),
));


$body = $modx->quip->getChunk('quipComment',$cp);
return $modx->error->success($body);