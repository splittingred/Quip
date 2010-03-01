<div class="quip-comment [[+alt]]" id="qcom[[+id]]">
<form action="[[+self]]" method="post">
    <input type="hidden" name="id" value="[[+id]]" />
    <div class="quip-comment-rightstuff">
        <span class="quip-comment-createdon">[[+createdon]]</span><br />
        [[+report]]
        <span class="quip-comment-options">
            [[+options]]
        </span>
    </div>
    <span class="quip-comment-author">[[%quip.username_said? &username=`[[+username]]`]]</span><br />
    
    <p class="quip-comment-body">[[+body]]</p>
</form>
</div>