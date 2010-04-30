Ext.onReady(function() {
    MODx.load({ xtype: 'quip-page-home'});
});

Quip.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'quip-panel-threads'
            ,renderTo: 'quip-panel-home-div'
        }]
    }); 
    Quip.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(Quip.page.Home,MODx.Component);
Ext.reg('quip-page-home',Quip.page.Home);