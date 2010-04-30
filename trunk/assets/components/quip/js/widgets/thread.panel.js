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
            ,items: [{
                xtype: 'quip-grid-comments'
                ,thread: config.thread
                ,preventRender: true
                ,width: '98%'
                ,bodyStyle: 'padding: 0'
            }]
        }]
    });
    Quip.panel.Thread.superclass.constructor.call(this,config);
};
Ext.extend(Quip.panel.Thread,MODx.FormPanel);
Ext.reg('quip-panel-thread',Quip.panel.Thread);