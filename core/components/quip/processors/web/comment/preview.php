<?php
/**
 * Preview a comment
 *
 * @package quip
 * @subpackage processors
 */
$errors = array();

if (empty($_POST['comment'])) $errors[] = $modx->lexicon('quip.message_err_ns');

$body = $_POST['comment'];
echo '<pre>'.$allowedTags.'</pre>';
$body = preg_replace("/<script(.*)<\/script>/i",'',$body);
$body = preg_replace("/<iframe(.*)<\/iframe>/i",'',$body);
$body = preg_replace("/<iframe(.*)\/>/i",'',$body);
$body = strip_tags($body,$allowedTags);


if (empty($errors)) {
    $preview = array_merge($_POST,array(
        'username' => $modx->user->get('username'),
        'comment' => $body,
    ));
    $placeholders['preview'] = $quip->getChunk($previewTpl,$preview);
} else {
    $placeholders['error'] = implode("<br />\n",$errors);
}

$placeholders['comment'] = $body;


return $errors;