Ext.onReady(function() {
    MODx.load({ 
        xtype: 'quip-page-thread'
        ,thread: Quip.request.thread
    });
});

Quip.page.Thread = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'quip-panel-thread'
        ,buttons: [{
            text: _('quip.back_to_threads')
            ,id: 'quip-btn-back'
            ,handler: function() {
                location.href = '?a='+Quip.request.a+'&action=home';
            }
            ,scope: this
        }]
        ,components: [{
            xtype: 'quip-panel-thread'
            ,renderTo: 'quip-panel-thread'
            ,thread: config.thread
        }]
    }); 
    Quip.page.Thread.superclass.constructor.call(this,config);
};
Ext.extend(Quip.page.Thread,MODx.Component);
Ext.reg('quip-page-thread',Quip.page.Thread);