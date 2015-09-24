
Ext.define('Uums.privilege.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.layout = {
      type: 'border',
      padding: 5
    };
    
    config.border = false;
    
    config.pnlPrivilegeTree = Ext.create('Uums.privilege.PrivilegeTreePanel');
    config.grdPrivilege = Ext.create('Uums.privilege.PrivilegeGrid');
    
    config.pnlPrivilegeTree.on('selectchange', this.onPnlPrivilegeTreeNodeSelectChange, this);
    
    config.items = [config.pnlPrivilegeTree, config.grdPrivilege];
    
    this.callParent([config]);
  },
  
  onPnlPrivilegeTreeNodeSelectChange: function(privilegeId) {
    this.grdPrivilege.refreshGrid(privilegeId);
  }
});

