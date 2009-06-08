<?php
/**
 * @package quip
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/quipcomment.class.php');
class quipComment_mysql extends quipComment {
    function quipComment_mysql(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}
?>