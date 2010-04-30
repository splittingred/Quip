<?php
/**
 * @package quip
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/quipcommentnotify.class.php');
class quipCommentNotify_mysql extends quipCommentNotify {}
?>