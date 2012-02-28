Quip.panel.Thread = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'quip-panel-thread'
        ,url: Quip.config.connector_url
        ,cls: 'container form-with-labels'
        ,baseParams: {}
        ,items: [{
            html: '<h2>'+_('quip.thread')+': '+config.thread+'</h2>'
            ,border: false
            ,id: 'rm-package-name'
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,stateful: true
            ,stateId: 'quip-home-tabpanel'
            ,stateEvents: ['tabchange']
            ,getState:function() {
                return {activeTab:MODx.request.quip_unapproved ? 1 : this.items.indexOf(this.getActiveTab())};
            }
            ,items: [{
                layout: 'form'
                ,title: _('quip.thread')
                ,items: [{
                    html: '<p>'+_('quip.thread_msg')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    layout: 'form'
                    ,bodyCssClass: 'main-wrapper'
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
                    ,cls: 'quip-thread-grid main-wrapper'
                    ,thread: config.thread
                    ,preventRender: true
                    ,width: '98%'
                }]
            },{
                title: _('quip.notifications')
                ,defaults: { autoHeight: true }
                ,items: [{
                    html: '<p>'+_('quip.notifications.intro_msg')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'quip-grid-notification'
                    ,cls: 'main-wrapper'
                    ,thread: config.thread
                    ,preventRender: true
                }]
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
                ,name: this.config.thread
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                },scope: this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        Ext.apply(o.form.baseParams,{
        });
    }
    ,success: function(o) {
        Ext.getCmp('quip-btn-save').setDisabled(false);
    }
});
Ext.reg('quip-panel-thread',Quip.panel.Thread);