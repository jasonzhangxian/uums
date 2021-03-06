

Ext.define('Uums.privilege.PrivilegeTreePanel', {
  extend: 'Ext.tree.TreePanel',
  
  constructor: function(config) {
    config = config || {};
    
    config.region = 'west';
    config.border = false;
    config.autoScroll = true;
    config.containerScroll = true;
    config.split = true;
    config.width = 170;
    config.rootVisible = false;
    config.currentPrivilegeId = '';
    
    config.store = Ext.create('Ext.data.TreeStore', {
      proxy: {
        type: 'ajax',
        url : Uums.privilege_request_url,
        extraParams : {action:'tree'}
      },
      root: {
        id: '',
        text: '--所有权限--',
        leaf: false,
        expandable: true,  
        expanded: true  
      },
      listeners: {
        'load': function() {
          //this.expandAll();
          //this.setPrivilegeId(0);
        },
        scope: this
      }
    });
    
    config.tbar = [{
      text: UumsLanguage.btnRefresh,
      iconCls: 'refresh',
      handler: this.refresh,
      scope: this
    }];
    
    config.listeners = {
      "itemclick": this.onPrivilegeNodeClick,
      scope: this
    };
    
    this.addEvents({'selectchange' : true});
    
    this.callParent([config]);
  },
  
  onPrivilegeNodeClick: function (view, record, node) {
    var privilegeId = record.get('id');
    if(!record.get('leaf')) {
	    this.setPrivilegeId(privilegeId);
	}
  },

  setPrivilegeId: function(privilegeId) {
    this.currentPrivilegeId = privilegeId;
    
    this.fireEvent('selectchange', privilegeId);
  },  
  
  refresh: function() {
    this.getStore().load();
  }
});
