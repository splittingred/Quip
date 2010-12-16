<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
    <title>[[%quip.latest_comments_for? &topic=`default` &namespace=`quip`]] [[+quip.latest.pagetitle]]</title>
    <link>[[~[[*id]]? &scheme=`full`]]</link>
    <description><![CDATA[ [[%quip.latest_comments_for? &topic=`default` &namespace=`quip`]] [[+quip.latest.pagetitle]] ]]></description>
    <language>[[++cultureKey]]</language>
    <ttl>120</ttl>
    <atom:link href="[[~[[*id]]? &scheme=`full`]]" rel="self" type="application/rss+xml" />
[[+comments]]
</channel>
</rss>