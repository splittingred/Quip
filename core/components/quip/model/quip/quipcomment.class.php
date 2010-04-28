<?php
/**
 * Quip
 *
 * Copyright 2010 by Shaun McCormick <shaun@collabpad.com>
 *
 * This file is part of Quip.
 *
 * Quip is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Quip is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Quip; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package quip
 */
/**
 * @package quip
 */
class quipComment extends xPDOSimpleObject {
    public function makeUrl($resource = 0,array $params = array(),array $options = array()) {
        if (empty($resource)) $resource = $this->get('resource');
        if (empty($params)) $params = $this->get('existing_params');
        if (empty($params)) $params = array();

        $scheme= $this->xpdo->getOption('scheme',$options,'');
        $idprefix = $this->xpdo->getOption('idprefix',$options,$this->get('idprefix'));
        return $this->xpdo->makeUrl($resource,'',$params,$scheme).'#'.$idprefix.$this->get('id');
    }

}