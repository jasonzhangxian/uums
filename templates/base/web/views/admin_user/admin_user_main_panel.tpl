
Ext.define('Uums.admin_user.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.layout = 'border';
    
    this.treeDepartment = Ext.create('Uums.admin_user.DepartmentTreePanel');
    this.treeDepartment.on('selectchange', this.onDepartmentSelectChange, this);

    this.grdAdminUser = Ext.create('Uums.admin_user.AdminUserGrid');
    this.grdAdminUser.on('create', function() {this.fireEvent('createadmin_user');}, this);
    this.grdAdminUser.on('edit', function(rec) {this.fireEvent('editadmin_user', rec);}, this);
    this.grdAdminUser.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback);}, this);
    this.grdAdminUser.getStore().on('load', this.onGrdAdminUserLoad, this);

    config.items = [this.treeDepartment,this.grdAdminUser];
    
    this.addEvents({'createadmin_user': true, 'editadmin_user': true, 'createaddress': true, 'editaddress': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onGrdAdminUserLoad: function() {
    if (this.grdAdminUser.getStore().getCount() > 0) {
      this.grdAdminUser.getSelectionModel().select(0);
      var record = this.grdAdminUser.getStore().getAt(0);
      
      //this.onGrdAdminUserSelectChange(record);
    }
  },

  onDepartmentSelectChange: function(department_id) {
    this.grdAdminUser.refreshGrid(department_id);
    //this.pnlAccordion.grdAddressBook.iniGrid(record);
  }
});

/* End of file admin_user_main_panel.tpl */
/* Location: ./templates/base/web/views/admin_user/admin_user_main_panel.tpl */