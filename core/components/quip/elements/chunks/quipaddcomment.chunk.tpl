[[+preview]]
<span class="quip-success" id="quip-success-[[+idprefix]]">[[+successMsg]]</span>

<form id="quip-add-comment-[[+idprefix]]" action="[[+url]]#quip-comment-preview-box-[[+idprefix]]" method="post">
<input type="hidden" name="nospam" value="" />
<input type="hidden" name="thread" value="[[+thread]]" />
<input type="hidden" name="parent" value="[[+parent]]" />

<div class="quip-comment quip-add-comment">
    <span class="quip-error">[[+error]]</span>
    
     <div class="quip-fld">
        <label for="quip-comment-name">[[%quip.name? &namespace=`quip` &topic=`default`]]:</label>
        <input type="text" name="name" id="quip-comment-name-[[+idprefix]]" value="[[+name]]" />
        <br />
    </div>
    
    <div class="quip-fld">
        <label for="quip-comment-email">[[%quip.email]]: </label>
        <input type="text" name="email" id="quip-comment-email-[[+idprefix]]" value="[[+email]]" />
        <br />
    </div>
    
    <div class="quip-fld">
        <label for="quip-comment-website">[[%quip.website]]: </label>
        <input type="text" name="website" id="quip-comment-website-[[+idprefix]]" value="[[+website]]" />
        <br />
    </div>

    <div class="quip-fld">
        <label for="quip-comment-notify">[[%quip.notify_me]]: </label>
        <input type="checkbox" value="1" name="notify" id="quip-comment-notify-[[+idprefix]]" [[+notify:if=`[[+notify]]`:eq=`1`:then=`checked="checked"`]] />
        <br />
    </div>

    <div class="quip-fld recaptcha">
    [[+quip.recaptcha_html]]
    </div>
    
    
    <p><span class="quip-allowed-tags">[[%quip.allowed_tags? &tags=`[[++quip.allowed_tags:htmlent]]`]]</span>[[%quip.comment_add_new]] </p>
    <textarea name="comment" id="quip-comment-box-[[+idprefix]]" rows="5">[[+comment]]</textarea>
    
    <button type="submit" name="quip-preview" value="1">[[%quip.preview]]</button>
    
    <br class="clear" />
</div>
</form>