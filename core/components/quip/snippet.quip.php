<?php
/**
 * Quip
 *
 * A simple comments component.
 *
 * @name Quip
 * @author Shaun McCormick <shaun@collabpad.com>
 * @package quip
 */
require_once $modx->getOption('core_path').'components/quip/model/quip/quip.class.php';

/** @var string $context The context to initialize Quip in. */
if (!isset($scriptProperties['context'])) $scriptProperties['context'] = 'web';

/* start up quip */
$quip = new Quip($modx,$scriptProperties);
return $quip->initialize($scriptProperties['context']);
