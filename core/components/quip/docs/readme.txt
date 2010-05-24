--------------------
Snippet: Quip
--------------------
Version: 0.5.0
Since: May 24th, 2010
Author: Shaun McCormick <shaun@modxcms.com>
License: GNU GPLv2 (or later at your option)

This component is a simple commenting system. It allows you to easily
put comments anywhere on your site. It allows you to also manage them
via the backend management interface.


Usage:
To load a comment thread on a page, with a reply form for bottom-level comments,
and point the threaded reply form to resource with ID 563:

[[!Quip? &thread=`page[[*id]]` &replyResourceId=`563`]]
<br />
[[!QuipReply]]

Then, in your reply page (id 563), add this:
<h2>Reply to Thread</h2>
[[!Quip]
<br />
[[!QuipReply]]

This reply page will act as a standard reply page for your comments. You don't
need to specify the thread, as your comments page will do that for you.


Also, Quip allows users to report comments as Spam. This will send
an email to the email address specified in the System Setting
"quip.emailsTo". It sends it from the address in the Setting
"quip.emailsFrom".


Thanks for using Quip!
Shaun McCormick
shaun@modxcms.com