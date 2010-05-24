<div class="quip-comment quip-preview" id="quip-comment-preview-box-[[+idprefix]]">
<form action="[[+url]]" method="post">
    <input type="hidden" name="thread" value="[[+thread]]" />
    <input type="hidden" name="parent" value="[[+parent]]" />
    <input type="hidden" name="author" value="[[+author]]" />
    <input type="hidden" name="comment" value="[[+body]]" />
    <input type="hidden" name="name" value="[[+name]]" />
    <input type="hidden" name="email" value="[[+email]]" />
    <input type="hidden" name="website" value="[[+website]]" />
    <input type="hidden" name="notify" value="[[+notify]]" />

    <div class="quip-comment-right">
        [[+md5email:notempty=`<img src="http://www.gravatar.com/avatar/[[+md5email]]?s=[[+gravatarSize]]&d=[[+gravatarIcon]]" class="quip-avatar" />`]]
    </div>

    <p class="quip-comment-meta">
        <span class="quip-comment-author">[[+name]]:</span><br />
        <span class="quip-comment-createdon">[[+createdon]]</span>
    </p>
    
    <div class="quip-comment-body"><p>[[+comment]]</p></div>
    
    <button type="submit" name="quip-close" value="1">[[%quip.close]]</button>
    <button type="submit" name="quip-post" value="1">[[%quip.post]]</button>
    <br class="clear" />
</form>
</div>