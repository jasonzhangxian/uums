
Ext.define('Uums.department.DepartmentTreePanel', {
  extend: 'Ext.tree.TreePanel',
  
  constructor: function(config) {
    config = config || {};
    
    config.region = 'center';
    config.border = false;
    config.autoScroll = true;
    config.containerScroll = true;
    config.split = true;
    config.width = 200;
    config.rootVisible = false;
    config.currentDepartmentId = '';
    
    config.store = Ext.create('Ext.data.TreeStore', {
      proxy: {
        type: 'ajax',
        url : Uums.department_request_url,
        extraParams : {action:'tree'}
      },
      root: {
        id: 0,
        text: '--所有权限--',
        leaf: false,
        expandable: true,  
        expanded: true  
      },
      listeners: {
        'load': function() {
          this.expandAll();
          //this.setDepartmentId(1);
        },
        scope: this
      }
    });
    
    config.tbar = [{
      text: UumsLanguage.btnAdd,
      iconCls: 'add',
      handler: function() {
        this.fireEvent('create');
      },
      scope: this
    },{
      text: UumsLanguage.btnEdit,
      iconCls: 'edit',
      handler: function() {
        this.fireEvent('edit',this.currentDepartmentId);
      },
      scope: this
    },{
      text: UumsLanguage.btnDelete,
      iconCls: 'remove',
      handler: function() {
        this.fireEvent('delete',this.currentDepartmentId);
      },
      scope: this
    },{
      text: UumsLanguage.btnRefresh,
      iconCls: 'refresh',
      handler: this.refresh,
      scope: this
    }];
    
    
    config.listeners = {
      "itemclick": this.onDepartmentNodeClick,
      scope: this
    };
    
    this.callParent([config]);
  },
  
  onDepartmentNodeClick: function (view, record, node) {
    var departmentId = record.get('id');
    this.setDepartmentId(departmentId);
  },

  setDepartmentId: function(departmentId) {
    this.currentDepartmentId = departmentId;
  },  
  
  refresh: function() {
    this.getStore().load();
  }
});

/* End of file department_grid.tpl */
/* Location: ./templates/base/web/views/department/department_grid.tpl */
