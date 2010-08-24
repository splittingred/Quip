<?php
/**
 * Package in plugins
 * 
 * @package quip
 * @subpackage build
 */
$plugins = array();

/* create the plugin object */
$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('id',1);
$plugins[0]->set('name','QuipResourceCleaner');
$plugins[0]->set('description','Cleans up threads when a Resource is removed.');
$plugins[0]->set('plugincode', getSnippetContent($sources['plugins'] . 'plugin.quipresourcecleaner.php'));
$plugins[0]->set('category', 0);

$events = include $sources['events'].'events.quipresourcecleaner.php';
if (is_array($events) && !empty($events)) {
    $plugins[0]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' Plugin Events for QuipResourceCleaner.'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find plugin events for QuipResourceCleaner!');
}
unset($events);

return $plugins;