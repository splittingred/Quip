
Quip.grid.Comments = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: Quip.config.connector_url
        ,baseParams: { 
            action: 'mgr/comment/getList'
            ,thread: config.thread
        }
        ,fields: ['id','author','username','body','createdon','menu']
        ,paging: true
        ,autosave: false
        ,remoteSort: true
        ,primaryKey: 'thread'
        ,autoExpandColumn: 'body'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 60
        },{
            header: _('quip.author')
            ,dataIndex: 'username'
            ,sortable: false
            ,width: 100
        },{
            header: _('quip.body')
            ,dataIndex: 'body'
            ,sortable: false
            ,width: 300
        },{
            header: _('quip.postedon')
            ,dataIndex: 'createdon'
            ,sortable: false
            ,editable: false
            ,width: 100
        }]
    });
    Quip.grid.Comments.superclass.constructor.call(this,config)
};
Ext.extend(Quip.grid.Comments,MODx.grid.Grid,{
    updateComment: function(btn,e) {        
        if (!this.updateCommentWindow) {
            this.updateCommentWindow = MODx.load({
                xtype: 'quip-window-comment-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        }
        this.updateCommentWindow.setValues(this.menu.record);
        this.updateCommentWindow.show(e.target);
        
    }
    ,removeComment: function() {        
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('quip.comment_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/comment/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.removeActiveRow,scope:this}
            }
        });
    }
});
Ext.reg('quip-grid-comments',Quip.grid.Comments);


Quip.window.UpdateComment = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('quip.comment_update')
        ,url: Quip.config.connector_url
        ,baseParams: {
            action: 'mgr/comment/update'
        }
        ,width: 600
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textarea'
            ,hideLabel: true
            ,name: 'body'
            ,width: 550
            ,grow: true
        }]
    });
    Quip.window.UpdateComment.superclass.constructor.call(this,config);
};
Ext.extend(Quip.window.UpdateComment,MODx.Window);
Ext.reg('quip-window-comment-update',Quip.window.UpdateComment);