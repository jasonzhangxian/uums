
Ext.define('Uums.api_list.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.layout = 'border';
    
    this.grdApi_list = Ext.create('Uums.api_list.Api_listGrid');
    
    this.grdApi_list.on('selectchange', this.onGrdApi_listSelectChange, this);
    this.grdApi_list.on('create', function() {this.fireEvent('createapi_list');}, this);
    this.grdApi_list.on('edit', function(rec) {this.fireEvent('editapi_list', rec);}, this);
    this.grdApi_list.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback);}, this);
    this.grdApi_list.getStore().on('load', this.onGrdApi_listLoad, this);

    config.items = [this.grdApi_list];
    
    this.addEvents({'createapi_list': true, 'editapi_list': true, 'createaddress': true, 'editaddress': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onGrdApi_listLoad: function() {
    if (this.grdApi_list.getStore().getCount() > 0) {
      this.grdApi_list.getSelectionModel().select(0);
      var record = this.grdApi_list.getStore().getAt(0);
      
      this.onGrdApi_listSelectChange(record);
    }
  },

  onGrdApi_listSelectChange: function(record) {
    //this.pnlAccordion.grdAddressBook.iniGrid(record);
  }
});

/* End of file api_list_main_panel.tpl */
/* Location: ./templates/base/web/views/api_list/api_list_main_panel.tpl */