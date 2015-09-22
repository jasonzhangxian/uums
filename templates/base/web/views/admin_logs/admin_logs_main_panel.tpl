
Ext.define('Uums.admin_logs.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.layout = 'border';
    
    this.grdAdmin_logs = Ext.create('Uums.admin_logs.Admin_logsGrid');
    
    this.grdAdmin_logs.on('selectchange', this.onGrdAdmin_logsSelectChange, this);
    this.grdAdmin_logs.on('create', function() {this.fireEvent('createadmin_logs');}, this);
    this.grdAdmin_logs.on('edit', function(rec) {this.fireEvent('editadmin_logs', rec);}, this);
    this.grdAdmin_logs.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback);}, this);
    this.grdAdmin_logs.getStore().on('load', this.onGrdAdmin_logsLoad, this);

    config.items = [this.grdAdmin_logs];
    
    this.addEvents({'createadmin_logs': true, 'editadmin_logs': true, 'createaddress': true, 'editaddress': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onGrdAdmin_logsLoad: function() {
    if (this.grdAdmin_logs.getStore().getCount() > 0) {
      this.grdAdmin_logs.getSelectionModel().select(0);
      var record = this.grdAdmin_logs.getStore().getAt(0);
      
      this.onGrdAdmin_logsSelectChange(record);
    }
  },

  onGrdAdmin_logsSelectChange: function(record) {
    //this.pnlAccordion.grdAddressBook.iniGrid(record);
  }
});

/* End of file admin_logs_main_panel.tpl */
/* Location: ./templates/base/web/views/admin_logs/admin_logs_main_panel.tpl */