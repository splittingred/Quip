<?php
/**
 * @package quip
 * @subpackage controllers
 */
$modx->regClientStartupScript($quip->config['js_url'].'widgets/comments.grid.js');
$modx->regClientStartupScript($quip->config['js_url'].'widgets/thread.panel.js');
$modx->regClientStartupScript($quip->config['js_url'].'sections/thread.js');
$output = '<div id="quip-panel-thread"></div>';

return $output;
