<form id="quip-add-comment" action="[[+url]]#quip-comment-preview-box" method="post">
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

    <div class="quip-fld">
        <label for="quip-comment-notify">[[%quip.notify_me]]: </label>
        <input type="checkbox" value="1" name="notify" id="quip-comment-notify" [[+notify:if=`[[+notify]]`:eq=`1`:then=`checked="checked"`]] />
        <br />
    </div>

    <div class="quip-fld">
    [[+quip.recaptcha_html]]
    </div>
    
    
    <p><span class="quip-allowed-tags">[[%quip.allowed_tags? &tags=`[[++quip.allowed_tags:htmlent]]`]]</span>[[%quip.comment_add_new]] </p>
    <textarea name="comment" id="quip-comment-box" rows="5">[[+comment]]</textarea>
    
    <button type="submit" name="quip-preview" value="1">[[%quip.preview]]</button>
    
    <br class="clear" />
</div>
</form>