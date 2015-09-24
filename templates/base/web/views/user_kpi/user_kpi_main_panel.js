
Ext.define('Uums.user_kpi.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.layout = 'border';
    
    this.treeDepartment = Ext.create('Uums.admin_user.DepartmentTreePanel');
    this.treeDepartment.on('selectchange', this.onDepartmentSelectChange, this);

    this.grdUserKpi = Ext.create('Uums.user_kpi.UserKpiGrid');
    this.grdUserKpi.on('create', function() {this.fireEvent('createuser_kpi');}, this);
    this.grdUserKpi.on('edit', function(rec) {this.fireEvent('edituser_kpi', rec);}, this);
    this.grdUserKpi.on('delete', function(rec) {this.fireEvent('deleteuser_kpi', rec);}, this);
    this.grdUserKpi.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback);}, this);
    this.grdUserKpi.getStore().on('load', this.onGrdUserKpiLoad, this);

    config.items = [this.treeDepartment,this.grdUserKpi];
    
    this.addEvents({'createuser_kpi': true, 'edituser_kpi': true, 'createaddress': true, 'editaddress': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onGrdUserKpiLoad: function() {
    if (this.grdUserKpi.getStore().getCount() > 0) {
      this.grdUserKpi.getSelectionModel().select(0);
      var record = this.grdUserKpi.getStore().getAt(0);
    }
  },

  onDepartmentSelectChange: function(department_id,leaf) {
    this.grdUserKpi.refreshGrid(department_id);
  }
});

/* End of file user_kpi_main_panel.tpl */
/* Location: ./templates/base/web/views/user_kpi/user_kpi_main_panel.tpl */