<?php
/**
 * @package quip
 */
class quipComment extends xPDOSimpleObject {
    function quipComment(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }
}