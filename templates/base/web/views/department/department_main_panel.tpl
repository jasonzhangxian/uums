
Ext.define('Uums.department.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.layout = 'border';
    
    this.grdDepartment = Ext.create('Uums.department.DepartmentGrid');
    
    this.grdDepartment.on('selectchange', this.onGrdDepartmentSelectChange, this);
    this.grdDepartment.on('create', function() {this.fireEvent('createdepartment');}, this);
    this.grdDepartment.on('edit', function(rec) {this.fireEvent('editdepartment', rec);}, this);
    this.grdDepartment.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback);}, this);
    this.grdDepartment.getStore().on('load', this.onGrdDepartmentLoad, this);

    config.items = [this.grdDepartment];
    
    this.addEvents({'createdepartment': true, 'editdepartment': true, 'createaddress': true, 'editaddress': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onGrdDepartmentLoad: function() {
    if (this.grdDepartment.getStore().getCount() > 0) {
      this.grdDepartment.getSelectionModel().select(0);
      var record = this.grdDepartment.getStore().getAt(0);
      
      this.onGrdDepartmentSelectChange(record);
    }
  },

  onGrdDepartmentSelectChange: function(record) {
    //this.pnlAccordion.grdAddressBook.iniGrid(record);
  }
});

/* End of file department_main_panel.tpl */
/* Location: ./templates/base/web/views/department/department_main_panel.tpl */