
Quip.grid.Notification = function(config) {
    config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();
    this.ident = config.ident || 'quip-'+Ext.id();
    Ext.applyIf(config,{
        url: Quip.config.connector_url
        ,baseParams: {
            action: 'mgr/thread/notification/getList'
            ,thread: config.thread || null
        }
        ,fields: ['id','thread','email','cls']
        ,paging: true
        ,autosave: false
        ,remoteSort: true
        ,autoExpandColumn: 'body'
        ,sm: this.sm
        ,columns: [this.sm,{
            header: _('quip.email')
            ,dataIndex: 'email'
            ,sortable: false
            ,editable: false
            ,width: 120
        }]
        ,tbar: [{
            text: _('quip.bulk_actions')
            ,menu: [{
                text: _('quip.remove_selected')
                ,handler: this.removeSelected
                ,scope: this
            }]
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: this.ident+'-tf-search'
            ,emptyText: _('search')+'...'
            ,listeners: {
                'change': {fn: this.search, scope: this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this.getValue());
                            this.blur();
                            return true;}
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,id: this.ident+'-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });
    Quip.grid.Notification.superclass.constructor.call(this,config)
};
Ext.extend(Quip.grid.Notification,MODx.grid.Grid,{
    _addEnterKeyHandler: function() {
        this.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
            this.fireEvent('change');
        },this);
    }
    ,clearFilter: function() {
    	var s = this.getStore();
        s.baseParams.search = '';
        Ext.getCmp(this.ident+'-tf-search').reset();
    	this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.search = nv;
        this.getBottomToolbar().changePage(1);
        this.refresh();
        return true;
    }
    ,_renderUrl: function(v,md,rec) {
        return '<a href="'+rec.data.url+'" target="_blank">'+rec.data.pagetitle+'</a>';
    }

    ,removeSelected: function(btn,e) {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'mgr/thread/notification/removeMultiple'
                ,notifications: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }
    ,removeNotification: function() {
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('quip.notification_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/thread/notification/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.removeActiveRow,scope:this}
            }
        });
    }
    ,verifyPerm: function(perm,rs) {
        var valid = true;
        for (var i=0;i<rs.length;i++) {
            if (rs[i].data.cls.indexOf(perm) == -1) {
                valid = false;
            }
        }
        return valid;
    }
    ,getMenu: function() {
        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
            var rs = this.getSelectionModel().getSelections();

            m.push({
                text: _('quip.notification_remove')
                ,handler: this.removeSelected
            });
        } else {
            var n = this.menu.record;
            var cls = n.cls.split(',');

            m.push({
                text: _('quip.notification_remove')
                ,handler: this.removeNotification
            });
        }
        return m;
    }
});
Ext.reg('quip-grid-notification',Quip.grid.Notification);
