--------------------
Snippet: Quip
--------------------
Version: 2.2
First Released: May 26th, 2009
Author: Shaun McCormick <shaun+quip@modx.com>
License: GNU GPLv2 (or later at your option)

This component is a simple commenting system. It allows you to easily
put comments anywhere on your site. It allows you to also manage them
via the backend management interface.

Upgrading to 2.0.0:
=====================
If you overrode any chunks in QuipReply, you'll need to update them with the contents in:

&tplAddComment - core/components/quip/elements/chunks/quipaddcomment.chunk.tpl
&tplPreview - core/components/quip/elements/chunks/quippreviewcomment.chunk.tpl

Specifically the extra hidden fields, error message placeholders, and submit button. Also,
notice that preview mode is no longer the default.

Upgrading to 0.6.0:
=====================
Note, the markup has changed for each comment. Comments are now no longer spaced
by margins, but in proper ol/li tags. If you'd like to revert to the old markup,
simply set &useMargins=`1` in your Quip call.


Please read the official documentation at:
http://rtfm.modx.com/display/ADDON/Quip

Thanks for using Quip!
Shaun McCormick
shaun+quip@modx.com