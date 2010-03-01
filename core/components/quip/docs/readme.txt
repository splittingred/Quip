--------------------
Snippet: Quip
--------------------
Version: 0.1
Since: June 8th, 2010
Author: Shaun McCormick <shaun@collabpad.com>
License: GNU GPLv2 (or later at your option)

This component is a simple commenting system. It allows you to easily
put comments anywhere on your site. It allows you to also manage them
via the backend management interface.

To comment, users must be logged in to the context they are in. Quip
does not provide the login tools; you must set that up on your own.

Parameters:

- &thread (string) The name of the thread to start.
- &closed (boolean) If set to 1, no comments will be allowed.  
- &dateFormat (string) The default date format to display on dates. 
    Defaults to %b %d, %Y at %I:%M %p
    
Example:
Load a comment thread on each page.
[[Quip? &thread=`page[[*id]]`]]


Also, Quip allows users to report comments as Spam. This will send
an email to the email address specified in the System Setting
"quip.emailsTo". It sends it from the address in the Setting
"quip.emailsFrom".


Thanks for using Quip!
Shaun McCormick
shaun@collabpad.com