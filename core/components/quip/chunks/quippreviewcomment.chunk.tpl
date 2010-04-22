<br />
<div class="quip-comment" id="quip-comment-preview-box">
<form action="[[+self]]" method="post">
    <input type="hidden" name="thread" value="[[+thread]]" />
    <input type="hidden" name="author" value="[[+author]]" />
    <input type="hidden" name="comment" value="[[+body]]" />
    <input type="hidden" name="name" value="[[+name]]" />
    <input type="hidden" name="email" value="[[+email]]" />
    <input type="hidden" name="website" value="[[+website]]" />
    <input type="hidden" name="notify" value="[[+notify]]" />
    
    <div class="quip-comment-rightstuff">
        <span class="quip-comment-createdon">[[+createdon]]</span><br />
    </div>
    <span class="quip-comment-author">[[%quip.username_said? &username=`[[+username]]`]]</span><br />
    
    <p class="quip-comment-body">[[+comment]]</p>
        
    <button type="submit" name="quip-close" value="1">[[%quip.close]]</button>
    <button type="submit" name="quip-post" value="1">[[%quip.post]]</button>
    <br class="clear" />
</form>
</div>