Ext.onReady(function() {
    MODx.load({ 
        xtype: 'quip-page-thread'
        ,thread: MODx.request.thread
    });
});

Quip.page.Thread = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'quip-panel-thread'
        ,buttons: [/*{
            text: _('save')
            ,id: 'quip-btn-save'
            ,process: 'mgr/thread/update'
            ,method: 'remote'
            ,keys: [{
                key: 's'
                ,alt: true
                ,ctrl: true
            }]
        },'-',*/{
            text: _('quip.back_to_threads')
            ,id: 'quip-btn-back'
            ,handler: function() {
                location.href = '?a='+MODx.request.a+'&action=home';
            }
            ,scope: this
        }]
        ,components: [{
            xtype: 'quip-panel-thread'
            ,renderTo: 'quip-panel-thread-div'
            ,thread: config.thread
        }]
    }); 
    Quip.page.Thread.superclass.constructor.call(this,config);
};
Ext.extend(Quip.page.Thread,MODx.Component);
Ext.reg('quip-page-thread',Quip.page.Thread);