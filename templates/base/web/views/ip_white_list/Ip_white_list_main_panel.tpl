
Ext.define('Uums.ip_white_list.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.layout = 'border';
    
    this.grdIp_white_list = Ext.create('Uums.ip_white_list.Ip_white_listGrid');
    
    this.grdIp_white_list.on('selectchange', this.onGrdIp_white_listSelectChange, this);
    this.grdIp_white_list.on('create', function() {this.fireEvent('createip_white_list');}, this);
    this.grdIp_white_list.on('edit', function(rec) {this.fireEvent('editip_white_list', rec);}, this);
    this.grdIp_white_list.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback);}, this);
    this.grdIp_white_list.getStore().on('load', this.onGrdIp_white_listLoad, this);

    config.items = [this.grdIp_white_list];
    
    this.addEvents({'createip_white_list': true, 'editip_white_list': true, 'createaddress': true, 'editaddress': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onGrdIp_white_listLoad: function() {
    if (this.grdIp_white_list.getStore().getCount() > 0) {
      this.grdIp_white_list.getSelectionModel().select(0);
      var record = this.grdIp_white_list.getStore().getAt(0);
      
      this.onGrdIp_white_listSelectChange(record);
    }
  },

  onGrdIp_white_listSelectChange: function(record) {
    //this.pnlAccordion.grdAddressBook.iniGrid(record);
  }
});

/* End of file ip_white_list_main_panel.tpl */
/* Location: ./templates/base/web/views/ip_white_list/ip_white_list_main_panel.tpl */