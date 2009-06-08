<?php
/**
 * @package quip
 * @subpackage controllers
 */
require_once dirname(dirname(__FILE__)).'/model/quip/quip.class.php';
$quip = new Quip($modx);
return $quip->initialize('mgr');