Quip.panel.Thread = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'quip-panel-thread'
        ,url: Quip.config.connector_url
        ,baseParams: {}
        ,items: [{
            html: '<h2>'+_('quip.thread')+': '+config.thread+'</h2>'
            ,border: false
            ,id: 'rm-package-name'
            ,cls: 'modx-page-header'
        },{
            layout: 'form'
            ,defaults: {
                style: 'padding: 15px 10px 5px;'
            }
            ,border: true
            ,items: [{
                html: '<p>'+_('quip.thread_msg')+'</p>'
                ,border: false
            },{
                layout: 'form'
                ,labelWidth: 150
                ,border: false
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'name'
                },{
                    xtype: 'statictextfield'
                    ,fieldLabel: _('quip.moderated')
                    ,description: _('quip.moderated_desc')
                    ,name: 'moderated'
                    ,width: 300
                    ,allowBlank: true
                },{
                    xtype: 'statictextfield'
                    ,fieldLabel: _('quip.moderators')
                    ,description: _('quip.moderators_desc')
                    ,name: 'moderators'
                    ,width: 300
                    ,allowBlank: true
                },{
                    xtype: 'statictextfield'
                    ,fieldLabel: _('quip.moderator_group')
                    ,description: _('quip.moderator_group_desc')
                    ,name: 'moderator_group'
                    ,width: 300
                    ,allowBlank: true
                }]
            },{
                xtype: 'quip-grid-comments'
                ,cls: 'quip-thread-grid'
                ,thread: config.thread
                ,preventRender: true
                ,width: '98%'
                ,bodyStyle: 'padding: 0'
            }]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
            ,'success': {fn:this.success,scope:this}
        }
    });
    Quip.panel.Thread.superclass.constructor.call(this,config);
};
Ext.extend(Quip.panel.Thread,MODx.FormPanel,{
    setup: function() {
        if (!this.config.thread) return;
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'mgr/thread/get'
                ,thread: this.config.thread
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    /*var gf = Ext.getCmp('quip-grid-comments');
                    if (r.object.comments.length != 0 && gf) {
                        gf.getStore().loadData(r.object.comments);
                    }
                    */
                },scope: this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        Ext.apply(o.form.baseParams,{
            //comments: Ext.getCmp('quip-grid-comments').encode()
        });
    }
    ,success: function(o) {
        Ext.getCmp('quip-btn-save').setDisabled(false);
    }
});
Ext.reg('quip-panel-thread',Quip.panel.Thread);