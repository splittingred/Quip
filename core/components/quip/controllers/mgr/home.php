<?php
/**
 * @package quip
 * @subpackage controllers
 */
$modx->regClientStartupScript($quip->config['js_url'].'widgets/threads.panel.js');
$modx->regClientStartupScript($quip->config['js_url'].'sections/home.js');
$output = '<div id="quip-panel-home"></div>';

return $output;
