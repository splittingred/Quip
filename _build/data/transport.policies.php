<?php
/**
 * Default Quip Access Policies
 *
 * @package quip
 * @subpackage build
 */
function bld_policyFormatData($permissions) {
    $data = array();
    foreach ($permissions as $permission) {
        $data[$permission->get('name')] = true;
    }
    return $data;
}
$policies = array();
$policies[1]= $xpdo->newObject('modAccessPolicy');
$policies[1]->fromArray(array (
  'id' => 1,
  'name' => 'QuipModeratorPolicy',
  'description' => 'A policy for moderating Quip comments.',
  'parent' => 0,
  'class' => '',
  'lexicon' => 'quip:permissions',
), '', true, true);
$permissions = include dirname(__FILE__).'/permissions/moderator.policy.php';
$policies[1]->addMany($permissions);
$policies[1]->set('data',bld_policyFormatData($permissions));
unset($permissions);

return $policies;