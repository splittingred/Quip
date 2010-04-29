<?php
/**
 * @package quip
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/quipcommentclosure.class.php');
class quipCommentClosure_mysql extends quipCommentClosure {}
?>