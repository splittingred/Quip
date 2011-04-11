[[+preview]]
<span class="quip-success" id="quip-success-[[+idprefix]]">[[+successMsg]]</span>

<form id="quip-add-comment-[[+idprefix]]" action="[[+url]]#quip-comment-preview-box-[[+idprefix]]" method="post">
<div class="quip-comment quip-add-comment">
    <input type="hidden" name="nospam" value="" />
    <input type="hidden" name="thread" value="[[+thread]]" />
    <input type="hidden" name="parent" value="[[+parent]]" />
    <input type="hidden" name="auth_nonce" value="[[+auth_nonce]]" />
    <input type="hidden" name="preview_mode" value="[[+preview_mode]]" />

     <div class="quip-fld">
        <label for="quip-comment-name-[[+idprefix]]">[[%quip.name? &namespace=`quip` &topic=`default`]]:<span class="quip-error">[[+error.name]]</span></label>
        <input type="text" name="name" id="quip-comment-name-[[+idprefix]]" value="[[+name]]" />
        <br />
    </div>
    
    <div class="quip-fld">
        <label for="quip-comment-email-[[+idprefix]]">[[%quip.email]]:<span class="quip-error">[[+error.email]]</span></label>
        <input type="text" name="email" id="quip-comment-email-[[+idprefix]]" value="[[+email]]" />
        <br />
    </div>
    
    <div class="quip-fld">
        <label for="quip-comment-website-[[+idprefix]]">[[%quip.website]]:<span class="quip-error">[[+error.website]]</span></label>
        <input type="text" name="website" id="quip-comment-website-[[+idprefix]]" value="[[+website]]" />
        <br />
    </div>

    <div class="quip-fld">
        [[+unsubscribe:default=`
        <label for="quip-comment-notify-[[+idprefix]]">[[%quip.notify_me]]:<span class="quip-error">[[+error.notify]]</span></label>
        <input type="checkbox" value="1" name="notify" id="quip-comment-notify-[[+idprefix]]" [[+notifyChecked]] />
        `]]
        <br />
    </div>

    <div class="quip-fld recaptcha">
    [[+quip.recaptcha_html]]
    <span class="quip-error">[[+error.recaptcha]]</span>
    </div>
    
    
    <p><span class="quip-allowed-tags">[[%quip.allowed_tags? &tags=`[[++quip.allowed_tags:htmlent]]`]]</span>[[%quip.comment_add_new]]<span class="quip-error">[[+error.comment]]</span></p>
    <textarea name="comment" id="quip-comment-box-[[+idprefix]]" rows="5">[[+comment]]</textarea>
    
    <button type="submit" name="[[+preview_action]]" value="1">[[%quip.preview]]</button>
    [[+can_post:is=`1`:then=`<button type="submit" name="[[+post_action]]" value="1">[[%quip.post]]</button>`]]
    
    <br class="clear" />
</div>
</form>