<div class="[[+cls]] [[+alt]]" id="[[+idprefix]][[+id]]" [[+threaded:notempty=`style="padding-left: [[+depth_margin]]px"`]]>
<form class="quip-comment-form" action="[[+url]]" method="post">
    <input type="hidden" name="thread" value="[[+thread]]" />
    <input type="hidden" name="id" value="[[+id]]" />

    <div class="quip-comment-right">
        [[+md5email:notempty=`<img src="http://www.gravatar.com/avatar/[[+md5email]]?s=[[+gravatarSize]]&d=[[+gravatarIcon]]" class="quip-avatar" />`]]
    </div>

    <p class="quip-comment-meta">
        <span class="quip-comment-author">[[+authorName]]:</span><br />
        <span class="quip-comment-createdon"><a href="[[+url]]">[[+createdon]]</a>
        [[+approved:if=`[[+approved]]`:is=`1`:then=``:else=`- <em>[[%quip.unapproved? &namespace=`quip` &topic=`default`]]</em>`]]
        </span>
    </p>

    <div class="quip-comment-body">
        <p>[[+body]]</p>

        [[+replyUrl:notempty=`<p><span class="quip-reply-link"><a href="[[+replyUrl]]">[[%quip.reply? &namespace=`quip` &topic=`default`]]</a></span></p>`]]
    </div>

    <div class="quip-comment-options">
        [[+report]]
        [[+options]]
    </div>
</form>
</div>