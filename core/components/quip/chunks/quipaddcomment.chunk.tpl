<form id="quip-add-comment" action="[[+quip.self]]" method="post"> 

<div class="quip-comment" id="quip-comment-preview-box" style="display: none;">
    <span class="quip-comment-author">[[%quip.username_said? &username=`[[+quip.username]]`]]</span><br />

    <p class="quip-comment-body" id="quip-comment-preview"></p>
    
    <button type="button" onclick="Quip.closePreview(); return false;">[[%quip.close]]</button>
    <button type="button" onclick="Quip.postComment('[[+quip.thread]]','[[+quip.self]]'); return false;">[[%quip.post]]</button>
    <br class="clear" />
</div>

<div class="quip-comment quip-add-comment">
    <p>[[%quip.comment_add_new]]</p>
    
    <textarea name="comment" id="quip-comment-box"></textarea>
    
    <button type="button" onclick="Quip.previewComment(); return false;">[[%quip.preview]]</button>
    
    <br class="clear" />
</div>
</form>