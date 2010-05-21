<?php
/**
 * @package quip
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/quipthread.class.php');
class quipThread_mysql extends quipThread {}
?>