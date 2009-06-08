<?php
/**
 * @package quip
 * @subpackage controllers
 */
$modx->regClientCSS($quip->config['css_url'].'mgr.css');
$modx->regClientStartupScript($quip->config['js_url'].'quip.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
Ext.onReady(function() {
    Quip.config = '.$modx->toJSON($quip->config).';
    Quip.config.connector_url = "'.$quip->config['connector_url'].'";
    Quip.request = '.$modx->toJSON($_GET).';
});
</script>');

return '';