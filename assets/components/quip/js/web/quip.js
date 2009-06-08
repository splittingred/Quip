$(function() {
    $('#quip-error').hide();
});
var Quip = {
    config: {}
    ,baseAjax: {
        type: 'POST'
        ,dataType: 'json'
        ,failure: function(r) {
            Quip._showError(r);
        }
    }
        
    ,previewComment: function() {
        var v = $('#quip-comment-box').val();
        
        v = v.replace(/<script(.*)<\/script>/i,'');
        v = v.replace(/<iframe(.*)<\/iframe>/i,'');
        v = v.replace(/<iframe(.*)\/>/i,'');
        $('#quip-comment-box').val(v);
        
        $('#quip-comment-preview').html(v);
        $('#quip-comment-preview-box').hide().slideDown(300);    
        return false;
    }
    
    ,closePreview: function() {
        $('#quip-comment-preview').html('');
        $('#quip-comment-preview-box').slideUp(200);
    }
    
    ,postComment: function(thread,url) {
        var p = $.extend({},Quip.baseAjax,{
            url: Quip.config.connector
            ,data: {
                action: 'web/comment/create'
                ,thread: thread
                ,url: url
                ,body: $('#quip-comment-box').val()
                ,resource: Quip.config.resource
                ,ctx: Quip.config.ctx
            }
            ,success: function(r) {
                if (r.success == false) { Quip._showError(r.message); return false; }
                
                $('#quip-comment-preview-box').hide();
                $('#quip-comment-box').val('');
                $('#quip-topofcomments').after(r.message);           
            }
        });
        $.ajax(p);       
    }
    
    ,removeComment: function(id) {
        var p = $.extend({},Quip.baseAjax,{
            url: Quip.config.connector
            ,data: {
                action: 'web/comment/remove'
                ,id: id
                ,ctx: Quip.config.ctx
            }
            ,success: function(r) {
                if (r.success == false) { Quip._showError(r.message); return false; }
                
                $('#qcom'+id).remove();
            }
        });
        $.ajax(p);
    }
    
    ,reportComment: function(id,url) {
        var p = $.extend({},Quip.baseAjax,{
            url: Quip.config.connector
            ,data: {
                action: 'web/comment/report'
                ,id: id
                ,url: url
                ,ctx: Quip.config.ctx
            }
            ,success: function(r) {
                if (r.success == false) { Quip._showError(r.message); return false; }
                
                $('#quipreport'+id).hide();
                $('#quipreported'+id).fadeIn(200);
            }
        });
        $.ajax(p);
    }
    
    ,_showError: function(msg,success) {
        var d = $('#quip-error');
        if (success) {
            d.removeClass('error');
            d.addClass('success');
        } else {
            d.removeClass('success');
            d.addClass('error');
        }
        $('#quip-error .content').html(msg);
        d.hide().fadeIn(300);
        setTimeout('Quip._closeError();',5000);
    }
    ,_closeError: function() {
        $('#quip-error').fadeOut(300);
    }
};