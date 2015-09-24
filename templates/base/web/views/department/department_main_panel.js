
Ext.define('Uums.department.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.layout = 'border';
    
    config.treeDepartment = Ext.create('Uums.department.DepartmentTreePanel');
    
    config.treeDepartment.on('selectchange', this.onGrdDepartmentSelectChange, this);
    config.treeDepartment.on('create', function() {this.fireEvent('createdepartment');}, this);
    config.treeDepartment.on('edit', function(rec) {this.fireEvent('editdepartment', rec);}, this);
    config.treeDepartment.on('delete', function(rec) {this.fireEvent('deletedepartment', rec);}, this);
    config.treeDepartment.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback);}, this);
    config.treeDepartment.getStore().on('load', this.onGrdDepartmentLoad, this);

    config.items = [config.treeDepartment];
    
    this.addEvents({'createdepartment': true, 'editdepartment': true, 'createaddress': true, 'editaddress': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onGrdDepartmentLoad: function() {
    // if (this.grdDepartment.getStore().getCount() > 0) {
    //   this.grdDepartment.getSelectionModel().select(0);
    //   var record = this.grdDepartment.getStore().getAt(0);
      
    //   this.onGrdDepartmentSelectChange(record);
    // }
  },

  onGrdDepartmentSelectChange: function(record) {
    debugger
    //this.pnlAccordion.grdAddressBook.iniGrid(record);
  }
});

/* End of file department_main_panel.tpl */
/* Location: ./templates/base/web/views/department/department_main_panel.tpl */