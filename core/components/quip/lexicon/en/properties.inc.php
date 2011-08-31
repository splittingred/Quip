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
 * Quip properties English language file
 *
 * @package quip
 * @subpackage lexicon
 */
/* options */
$_lang['quip.all'] = 'All';
$_lang['quip.ascending'] = 'Ascending';
$_lang['quip.author'] = 'Author';
$_lang['quip.comment'] = 'Comment';
$_lang['quip.descending'] = 'Descending';
$_lang['quip.family'] = 'Family';
$_lang['quip.opt_blackglass'] = 'Black Glass';
$_lang['quip.opt_clean'] = 'Clean';
$_lang['quip.opt_red'] = 'Red';
$_lang['quip.opt_white'] = 'White';
$_lang['quip.thread'] = 'Thread';
$_lang['quip.user'] = 'User';

/* Quip */
$_lang['quip.prop_allowremove_desc'] = 'Allow logged-in users to remove their own postings.';
$_lang['quip.prop_allowreportasspam_desc'] = 'Allow logged-in users to report comments as spam.';
$_lang['quip.prop_altrowcss_desc'] = 'The CSS class to put on alternating comments.';
$_lang['quip.prop_anonymousname_desc'] = 'The name to display for anonymous postings. Defaults to "Anonymous".';
$_lang['quip.prop_authortpl_desc'] = 'A name of a Chunk to use for the Author name in the comment.';
$_lang['quip.prop_closeafter_desc'] = 'Will automatically close the thread to new comments after this number of days. Set to 0 to leave infinitely open.';
$_lang['quip.prop_closed_desc'] = 'If set to true, the thread will not accept new comments.';
$_lang['quip.prop_currentpagecls_desc'] = 'A CSS class to put on the current pagination number.';
$_lang['quip.prop_dateformat_desc'] = 'The format of the dates displayed for a comment.';
$_lang['quip.prop_debug_desc'] = 'Set to true to turn on debug mode. Not recommended for production sites.';
$_lang['quip.prop_debuguser_desc'] = 'If debug is on, will set the username of $modx->user to this value.';
$_lang['quip.prop_debuguserid_desc'] = 'If debug is on, will set the id of $modx->user to this value.';
$_lang['quip.prop_gravataricon_desc'] = 'The default Gravatar icon to load if none is found for a user.';
$_lang['quip.prop_gravatarsize_desc'] = 'The size, in pixels, of the Gravatar.';
$_lang['quip.prop_idprefix_desc'] = 'If you want to use multiple Quip instances on a page, change this ID prefix.';
$_lang['quip.prop_limit_desc'] = 'The number of comments to limit per page. Setting this to a non-zero number will enable pagination.';
$_lang['quip.prop_namefield_desc'] = 'The field to use for the author name of each comment. Recommended values are "name" or "username".';
$_lang['quip.prop_maxdepth_desc'] = 'The maximum depth that replies can be made in a threaded comment thread.';
$_lang['quip.prop_olcss_desc'] = 'The CSS class to put in the parent ol tags for each comment.';
$_lang['quip.prop_pagecls_desc'] = 'A CSS class to put on a non-current pagination number link.';
$_lang['quip.prop_paginationcls_desc'] = 'A CSS class to put on the pagination OL wrapper.';
$_lang['quip.prop_parent_desc'] = 'The parent to start at when displaying the thread.';
$_lang['quip.prop_placeholderprefix_desc'] = 'The prefix for the global placeholders set by Quip.';
$_lang['quip.prop_requireauth_desc'] = 'If set to true, only logged-in users can post comments.';
$_lang['quip.prop_requireusergroups_desc'] = 'Optional. A comma-separated list of User Groups to restrict commenting to.';
$_lang['quip.prop_removeaction_desc'] = 'The name of the submit field to initiate a comment remove.';
$_lang['quip.prop_removethreshold_desc'] = 'If allowRemove is true, the number of minutes a user can remove their posting after they have posted it. Defaults to 3 minutes.';
$_lang['quip.prop_replyresourceid_desc'] = 'The ID of the Resource where the QuipReply snippet is held, for replying to threaded comments.';
$_lang['quip.prop_reportaction_desc'] = 'The name of the submit field to initiate a comment report as spam.';
$_lang['quip.prop_rowcss_desc'] = 'The CSS class to put on each comment\'s outer div container.';
$_lang['quip.prop_showanonymousname_desc'] = 'If true, will display the value of anonymousName property (defaults to "Anonymous") if the user is not logged in when posting.';
$_lang['quip.prop_start_desc'] = 'The default comment index to start on. Recommended to leave at 0.';
$_lang['quip.prop_sortby_desc'] = 'The field to sort by.';
$_lang['quip.prop_sortbyalias_desc'] = 'The alias of classes to use with sort by.';
$_lang['quip.prop_sortdir_desc'] = 'The direction to sort by.';
$_lang['quip.prop_thread_desc'] = 'The unique name of the thread.';
$_lang['quip.prop_threaded_desc'] = 'Whether or not this thread can have threaded comments.';
$_lang['quip.prop_threadedpostmargin_desc'] = 'The margin, in pixels, by which threaded comments are moved right for each depth level that they go.';
$_lang['quip.prop_toplaceholder_desc'] = 'If set, will output the content to the placeholder specified in this property, rather than outputting the content directly.';
$_lang['quip.prop_tplcomment_desc'] = 'A Chunk for the comment itself.';
$_lang['quip.prop_tplcommentoptions_desc'] = 'A Chunk for the options, such as delete, shown to an owner of a comment.';
$_lang['quip.prop_tplcomments_desc'] = 'A Chunk for the outer wrapper for comments.';
$_lang['quip.prop_tplpagination_desc'] = 'A Chunk for the pagination OL wrapper.';
$_lang['quip.prop_tplpaginationitem_desc'] = 'A Chunk for each non-current pagination number link.';
$_lang['quip.prop_tplpaginationcurrentitem_desc'] = 'A Chunk for the current pagination number link.';
$_lang['quip.prop_tplreport_desc'] = 'The link on a comment to report as spam. Can either be a chunk name or value. If set to a value, will override the chunk.';
$_lang['quip.prop_unapprovedcss_desc'] = 'The CSS class to put on unapproved comments.';
$_lang['quip.prop_usecss_desc'] = 'If true, Quip will provide a basic CSS template for the presentation.';
$_lang['quip.prop_usemargins_desc'] = 'If false, Quip will use ol/li items for each comment and threaded comment. If true, will use padded margins for each comment in threading.';
$_lang['quip.prop_usegravatar_desc'] = 'If true, will attempt to use Gravatar images for avatars.';

/* QuipReply */
$_lang['quip.prop_reply_autoconvertlinks_desc'] = 'If true, will automatically convert URLs to links.';
$_lang['quip.prop_reply_closeafter_desc'] = 'Will automatically close the thread to new comments after this number of days. Set to 0 to leave infinitely open.';
$_lang['quip.prop_reply_closed_desc'] = 'If set to true, the thread will not accept new comments.';
$_lang['quip.prop_reply_dateformat_desc'] = 'The format of the dates displayed for a comment.';
$_lang['quip.prop_reply_debug_desc'] = 'Set to true to turn on debug mode. Not recommended for production sites.';
$_lang['quip.prop_reply_debuguser_desc'] = 'If debug is on, will set the username of $modx->user to this value.';
$_lang['quip.prop_reply_debuguserid_desc'] = 'If debug is on, will set the id of $modx->user to this value.';
$_lang['quip.prop_reply_disablerecaptchawhenloggedin_desc'] = 'If the user is logged in, do not use reCaptcha.';
$_lang['quip.prop_reply_dontmoderatemanagerusers_desc'] = 'Never moderate users that are logged into the Revolution Manager.';
$_lang['quip.prop_reply_extraautolinksattributes_desc'] = 'Any extra HTML attributes to add to auto-converted links, if autoConvertLinks is set to 1.';
$_lang['quip.prop_reply_gravataricon_desc'] = 'The default Gravatar icon to load if none is found for a user.';
$_lang['quip.prop_reply_gravatarsize_desc'] = 'The size, in pixels, of the Gravatar.';
$_lang['quip.prop_reply_idprefix_desc'] = 'If you want to use multiple Quip instances on a page, change this ID prefix.';
$_lang['quip.prop_reply_moderate_desc'] = 'If set to true, all new posts to the thread will be moderated.';
$_lang['quip.prop_reply_moderateanonymousonly_desc'] = 'If set to true, only anonymous (non-logged-in) users will be moderated.';
$_lang['quip.prop_reply_moderatefirstpostonly_desc'] = 'If set to true, only the first post of the user will be moderated. All subsequent posts will be auto-approved. This only applies to logged-in users.';
$_lang['quip.prop_reply_moderatorgroup_desc'] = 'Any Users in this User Group will have moderator access.';
$_lang['quip.prop_reply_moderators_desc'] = 'A comma-separated list of moderator usernames for this thread.';
$_lang['quip.prop_reply_notifyemails_desc'] = 'A comma-separated list of email addresses to send a notification email to when a new post is made on this thread.';
$_lang['quip.prop_reply_postaction_desc'] = 'The name of the submit field to initiate a comment post.';
$_lang['quip.prop_reply_previewaction_desc'] = 'The name of the submit field to initiate a comment preview.';
$_lang['quip.prop_reply_recaptcha_desc'] = 'If true, will enable reCaptcha support.';
$_lang['quip.prop_reply_recaptchatheme_desc'] = 'If `recaptcha` is set to 1, this will select a theme for the reCaptcha widget.';
$_lang['quip.prop_reply_redirectto_desc'] = 'Optional. After posting a reply, redirect to the Resource with this ID.';
$_lang['quip.prop_reply_redirecttourl_desc'] = 'Optional. After posting a reply, redirect to this absolute URL.';
$_lang['quip.prop_reply_requireauth_desc'] = 'If set to true, only logged-in users can post comments.';
$_lang['quip.prop_reply_requirepreview_desc'] = 'If set to true, will require a user to preview their comment before posting.';
$_lang['quip.prop_reply_requireusergroups_desc'] = 'Optional. A comma-separated list of User Groups to restrict commenting to.';
$_lang['quip.prop_reply_tpladdcomment_desc'] = 'The add comment form. Can either be a chunk name or value. If set to a value, will override the chunk.';
$_lang['quip.prop_reply_tpllogintocomment_desc'] = 'The portion to show when the user is not logged in. Can either be a chunk name or value. If set to a value, will override the chunk.';
$_lang['quip.prop_reply_tplpreview_desc'] = 'The tpl for the preview text. Can either be a chunk name or value. If set to a value, will override the chunk.';
$_lang['quip.prop_reply_tplreport_desc'] = 'The link on a comment to report as spam. Can either be a chunk name or value. If set to a value, will override the chunk.';
$_lang['quip.prop_reply_usecss_desc'] = 'If true, Quip will provide a basic CSS template for the presentation.';
$_lang['quip.prop_reply_usegravatar_desc'] = 'If true, will attempt to use Gravatar images for avatars.';

/* QuipLatestComments */
$_lang['quip.prop_late_altrowcss_desc'] = 'The CSS class to put on alternating comments.';
$_lang['quip.prop_late_bodylimit_desc'] = 'The number of characters to limit the body field in the comment display to before adding an ellipsis.';
$_lang['quip.prop_late_contexts_desc'] = 'A comma-separated list of Contexts to pull comments from. If not set, will grab comments from all Contexts.';
$_lang['quip.prop_late_dateformat_desc'] = 'The format of the dates displayed for a comment.';
$_lang['quip.prop_late_family_desc'] = 'The family of threads to pull from. Only if type is set to Family.';
$_lang['quip.prop_late_limit_desc'] = 'The number of comments to pull.';
$_lang['quip.prop_late_placeholderprefix_desc'] = 'The prefix for the global placeholders set by QuipLatestComments.';
$_lang['quip.prop_late_rowcss_desc'] = 'The CSS class to put on each row.';
$_lang['quip.prop_late_sortby_desc'] = 'The field to sort by.';
$_lang['quip.prop_late_sortbyalias_desc'] = 'The alias of classes to use with sort by.';
$_lang['quip.prop_late_sortdir_desc'] = 'The direction to sort by.';
$_lang['quip.prop_late_start_desc'] = 'The start index of comments to pull from.';
$_lang['quip.prop_late_striptags_desc'] = 'If set to true, tags will be stripped from the body text.';
$_lang['quip.prop_late_thread_desc'] = 'The thread ID to pull from. Only if type is set to Thread.';
$_lang['quip.prop_late_toplaceholder_desc'] = 'If set, will output the content to the placeholder specified in this property, rather than outputting the content directly.';
$_lang['quip.prop_late_tpl_desc'] = 'The chunk tpl to use for each row.';
$_lang['quip.prop_late_type_desc'] = 'Whether to grab a list from all comments, per thread, per family of threads, or per user.';
$_lang['quip.prop_late_user_desc'] = 'The User ID or username to pull from. Only if type is set to User.';

/* QuipCount */
$_lang['quip.prop_count_thread_desc'] = 'The thread ID to pull from. Only if type contains `thread`.';
$_lang['quip.prop_count_toplaceholder_desc'] = 'If set, will output the content to the placeholder specified in this property, rather than outputting the content directly.';
$_lang['quip.prop_count_type_desc'] = 'If contains `thread`, will count the # of comments in a thread. If contains `user`, will grab # of total comments by a User. Supports a comma-delimited list of types.';
$_lang['quip.prop_count_user_desc'] = 'The User ID or username to pull from. Only if type contains `user`.';
$_lang['quip.prop_count_family_desc'] = 'The family of threads to pull from. Only if type contains `family`.';

/* QuipRss */
$_lang['quip.prop_rss_tpl_desc'] = 'The chunk tpl to use for each RSS item.';
$_lang['quip.prop_rss_containertpl_desc'] = 'The chunk tpl to use to wrap the RSS feed in.';
$_lang['quip.prop_rss_placeholderprefix_desc'] = 'The prefix for the global placeholders set by QuipRss.';