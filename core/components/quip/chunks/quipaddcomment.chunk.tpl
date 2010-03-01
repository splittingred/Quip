<form id="quip-add-comment" action="[[+self]]" method="post">
<div class="quip-comment quip-add-comment">
    <p><span class="quip-allowed-tags">[[%quip.allowed_tags? &tags=`[[++quip.allowed_tags:htmlent]]`]]</span>[[%quip.comment_add_new]] </p>
    
    <span class="quip-error">[[+error]]</span>
    
    <textarea name="comment" id="quip-comment-box" rows="5">[[+comment]]</textarea>
    
    <button type="submit" name="quip-preview" value="1">[[%quip.preview]]</button>
    
    <br class="clear" />
</div>
</form>