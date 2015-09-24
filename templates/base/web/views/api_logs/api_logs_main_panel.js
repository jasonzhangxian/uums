
Ext.define('Uums.api_logs.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.layout = 'border';
    
    this.grdApi_logs = Ext.create('Uums.api_logs.Api_logsGrid');
    
    this.grdApi_logs.on('selectchange', this.onGrdApi_logsSelectChange, this);
    this.grdApi_logs.on('create', function() {this.fireEvent('createapi_logs');}, this);
    this.grdApi_logs.on('edit', function(rec) {this.fireEvent('editapi_logs', rec);}, this);
    this.grdApi_logs.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback);}, this);
    this.grdApi_logs.getStore().on('load', this.onGrdApi_logsLoad, this);

    config.items = [this.grdApi_logs];
    
    this.addEvents({'createapi_logs': true, 'editapi_logs': true, 'createaddress': true, 'editaddress': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onGrdApi_logsLoad: function() {
    if (this.grdApi_logs.getStore().getCount() > 0) {
      this.grdApi_logs.getSelectionModel().select(0);
      var record = this.grdApi_logs.getStore().getAt(0);
      
      this.onGrdApi_logsSelectChange(record);
    }
  },

  onGrdApi_logsSelectChange: function(record) {
    //this.pnlAccordion.grdAddressBook.iniGrid(record);
  }
});

/* End of file api_logs_main_panel.tpl */
/* Location: ./templates/base/web/views/api_logs/api_logs_main_panel.tpl */