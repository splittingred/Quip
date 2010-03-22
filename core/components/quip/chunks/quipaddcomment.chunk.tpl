<form id="quip-add-comment" action="[[+self]]" method="post">
<input type="hidden" name="nospam" value="" />
<input type="hidden" name="thread" value="[[+thread]]" />

<div class="quip-comment quip-add-comment">
    <span class="quip-error">[[+error]]</span>
    
     <div class="quip-fld">
        <label for="quip-comment-name">[[%quip.name? &namespace=`quip` &topic=`default`]]:</label>
        <input type="text" name="name" id="quip-comment-name" value="[[+name]]" />
        <br />
    </div>
    
    <div class="quip-fld">
        <label for="quip-comment-email">[[%quip.email]]: </label>
        <input type="text" name="email" id="quip-comment-email" value="[[+email]]" />
        <br />
    </div>
    
    <div class="quip-fld">
        <label for="quip-comment-website">[[%quip.website]]: </label>
        <input type="text" name="website" id="quip-comment-website" value="[[+website]]" />
        <br />
    </div>
    
    
    <p><span class="quip-allowed-tags">[[%quip.allowed_tags? &tags=`[[++quip.allowed_tags:htmlent]]`]]</span>[[%quip.comment_add_new]] </p>
    <textarea name="comment" id="quip-comment-box" rows="5">[[+comment]]</textarea>
    
    <button type="submit" name="quip-preview" value="1">[[%quip.preview]]</button>
    
    <br class="clear" />
</div>
</form>