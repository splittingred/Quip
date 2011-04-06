<?php
/**
 * Quip
 *
 * Copyright 2010-11 by Shaun McCormick <shaun@modx.com>
 *
 * This file is part of Quip, a simple commenting component for MODx Revolution.
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
 * Default snippet properties for Quip
 *
 * @package quip
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'requireAuth',
        'desc' => 'quip.prop_reply_requireauth_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'requireUsergroups',
        'desc' => 'quip.prop_reply_requireusergroups_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'requirePreview',
        'desc' => 'quip.prop_reply_requirepreview_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'closed',
        'desc' => 'quip.prop_reply_closed_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'closeAfter',
        'desc' => 'quip.prop_reply_closeafter_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 14,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'moderate',
        'desc' => 'quip.prop_reply_moderate_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'moderateAnonymousOnly',
        'desc' => 'quip.prop_reply_moderateanonymousonly_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'moderateFirstPostOnly',
        'desc' => 'quip.prop_reply_moderatefirstpostonly_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'moderators',
        'desc' => 'quip.prop_reply_moderators_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'moderatorGroup',
        'desc' => 'quip.prop_reply_moderatorgroup_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'Administrator',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'dontModerateManagerUsers',
        'desc' => 'quip.prop_reply_dontmoderatemanagerusers_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'quip:properties',
    ),    
    array(
        'name' => 'dateFormat',
        'desc' => 'quip.prop_reply_dateformat_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '%b %d, %Y at %I:%M %p',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'useCss',
        'desc' => 'quip.prop_reply_usecss_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'notifyEmails',
        'desc' => 'quip.prop_reply_notifyemails_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'recaptcha',
        'desc' => 'quip.prop_reply_recaptcha_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'recaptchaTheme',
        'desc' => 'quip.prop_reply_recaptchatheme_desc',
        'type' => 'list',
        'options' => array(
            array('text' => 'quip.opt_red','value' => 'red'),
            array('text' => 'quip.opt_white','value' => 'white'),
            array('text' => 'quip.opt_clean','value' => 'clean'),
            array('text' => 'quip.opt_blackglass','value' => 'blackglass'),
        ),
        'value' => 'clean',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'disableRecaptchaWhenLoggedIn',
        'desc' => 'quip.prop_reply_disablerecaptchawhenloggedin_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'autoConvertLinks',
        'desc' => 'quip.prop_reply_autoconvertlinks_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'extraAutoLinksAttributes',
        'desc' => 'quip.prop_reply_extraautolinksattributes_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'useGravatar',
        'desc' => 'quip.prop_reply_usegravatar_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'gravatarIcon',
        'desc' => 'quip.prop_reply_gravataricon_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'identicon',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'gravatarSize',
        'desc' => 'quip.prop_reply_gravatarsize_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 50,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'tplAddComment',
        'desc' => 'quip.prop_reply_tpladdcomment_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quipAddComment',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'tplLoginToComment',
        'desc' => 'quip.prop_reply_tpllogintocomment_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quipLoginToComment',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'tplPreview',
        'desc' => 'quip.prop_reply_tplpreview_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quipPreviewComment',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'tplReport',
        'desc' => 'quip.prop_reply_tplreport_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quipReport',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'postAction',
        'desc' => 'quip.prop_reply_postaction_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quip-post',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'previewAction',
        'desc' => 'quip.prop_reply_previewaction_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'quip-preview',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'idPrefix',
        'desc' => 'quip.prop_reply_idprefix_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'qcom',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'debug',
        'desc' => 'quip.prop_reply_debug_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'debugUser',
        'desc' => 'quip.prop_reply_debuguser_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'quip:properties',
    ),
    array(
        'name' => 'debugUserId',
        'desc' => 'quip.prop_reply_debuguserid_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'quip:properties',
    ),
);
return $properties;