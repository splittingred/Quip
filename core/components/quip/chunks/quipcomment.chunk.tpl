<div class="quip-comment [[+alt]]" id="[[+idprefix]][[+id]]" [[+threaded:notempty=`style="padding-left: [[+depth_margin]]px"`]]>
<form action="[[+url]]" method="post">
    <input type="hidden" name="thread" value="[[+thread]]" />
    <input type="hidden" name="id" value="[[+id]]" />
    <div class="quip-comment-rightstuff">
        <span class="quip-comment-createdon">[[+createdon]]</span> : <a href="[[+url]]">#</a><br />
        [[+report]]
        <span class="quip-comment-options">
            [[+options]]
        </span>
    </div>
    <span class="quip-comment-author">[[+authorName]]:</span><br />
    
    <p class="quip-comment-body">[[+body]]</p>

    [[+replyUrl:notempty=`<p class="quip-reply-link"><a href="[[+replyUrl]]">[[%quip.reply? &namespace=`quip` &topic=`default`]]</a></p>`]]
</form>
</div>