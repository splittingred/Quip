Quip.panel.Threads = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,items: [{
            html: '<h2>'+_('quip')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,bodyStyle: 'padding: 10px'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,stateful: true
            ,stateId: 'quip-home-tabpanel'
            ,stateEvents: ['tabchange']
            ,getState:function() {
                return {activeTab:this.items.indexOf(this.getActiveTab())};
            }
            ,items: [{
                title: _('quip.threads')
                ,defaults: { autoHeight: true }
                ,items: [{
                    html: '<p>'+_('quip.intro_msg')+'</p>'
                    ,border: false
                    ,bodyStyle: 'padding: 10px'
                },{
                    xtype: 'quip-grid-thread'
                    ,preventRender: true
                }]
            },{
                title: _('quip.unapproved_comments')
                ,defaults: { autoHeight: true }
                ,items: [{
                    html: '<p>'+_('quip.unapproved_comments_msg')+'</p>'
                    ,border: false
                    ,bodyStyle: 'padding: 10px'
                },{
                    xtype: 'quip-grid-comments'
                    ,preventRender: true
                    ,baseParams: {
                        action: 'mgr/comment/getUnapproved'
                    }
                }]
            }]
        }]
    });
    Quip.panel.Threads.superclass.constructor.call(this,config);
};
Ext.extend(Quip.panel.Threads,MODx.Panel);
Ext.reg('quip-panel-threads',Quip.panel.Threads);

Quip.grid.Thread = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: Quip.config.connector_url
        ,baseParams: { action: 'mgr/thread/getList' }
        ,fields: ['name','comments','unapproved_comments','pagetitle','url','menu']
        ,paging: true
        ,autosave: false
        ,remoteSort: true
        ,primaryKey: 'name'
        ,columns: [{
            header: _('quip.thread')
            ,dataIndex: 'name'
            ,sortable: true
            ,width: 300
        },{
            header: _('quip.approved')
            ,dataIndex: 'comments'
            ,sortable: false
            ,width: 80
        },{
            header: _('quip.unapproved')
            ,dataIndex: 'unapproved_comments'
            ,sortable: false
            ,width: 80
        },{
            header: _('quip.view')
            ,dataIndex: 'url'
            ,sortable: false
            ,width: 120
            ,renderer: this._renderUrl
        }]
    });
    Quip.grid.Thread.superclass.constructor.call(this,config)
};
Ext.extend(Quip.grid.Thread,MODx.grid.Grid,{
    _renderUrl: function(v,md,rec) {
        return '<a href="'+v+'" target="_blank">'+rec.data.pagetitle+'</a>';
    }
    ,manageThread: function() {
        location.href = '?a='+MODx.request.a+'&action=thread&thread='+this.menu.record.name;
    }
    ,truncateThread: function() {        
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('quip.thread_truncate_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/thread/truncate'
                ,thread: this.menu.record.name
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('quip-grid-thread',Quip.grid.Thread);