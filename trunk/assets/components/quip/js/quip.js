var Quip = function(config) {
    config = config || {};
    Quip.superclass.constructor.call(this,config);
};
Ext.extend(Quip,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {}
});
Ext.reg('quip',Quip);

var Quip = new Quip();