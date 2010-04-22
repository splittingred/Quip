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
            layout: 'form'
            ,defaults: {
                style: 'padding: 15px 10px 5px;'
            }
            ,items: [{
                html: '<p>'+_('quip.intro_msg')+'</p>'
                ,border: false
            },{
                xtype: 'quip-grid-thread'
                ,preventRender: true
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
        ,fields: ['thread','comments','menu']
        ,paging: true
        ,autosave: false
        ,remoteSort: true
        ,primaryKey: 'thread'
        ,columns: [{
            header: _('quip.thread')
            ,dataIndex: 'thread'
            ,sortable: true
            ,width: 400
        },{
            header: _('quip.comments')
            ,dataIndex: 'comments'
            ,sortable: false
            ,width: 100
        }]
    });
    Quip.grid.Thread.superclass.constructor.call(this,config)
};
Ext.extend(Quip.grid.Thread,MODx.grid.Grid,{
    manageThread: function() {
        location.href = '?a='+MODx.request.a+'&action=thread&thread='+this.menu.record.thread;   
    }
    ,truncateThread: function() {        
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('quip.thread_truncate_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/thread/truncate'
                ,thread: this.menu.record.thread
            }
            ,listeners: {
                'success': {fn:this.removeActiveRow,scope:this}
            }
        });
    }
});
Ext.reg('quip-grid-thread',Quip.grid.Thread);