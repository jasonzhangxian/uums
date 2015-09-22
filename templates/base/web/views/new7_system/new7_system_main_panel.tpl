
Ext.define('Uums.new7_system.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.layout = 'border';
    
    this.grdNew7_system = Ext.create('Uums.new7_system.New7_systemGrid');
    
    this.grdNew7_system.on('selectchange', this.onGrdNew7_systemSelectChange, this);
    this.grdNew7_system.on('create', function() {this.fireEvent('createnew7_system');}, this);
    this.grdNew7_system.on('edit', function(rec) {this.fireEvent('editnew7_system', rec);}, this);
    this.grdNew7_system.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback);}, this);
    this.grdNew7_system.getStore().on('load', this.onGrdNew7_systemLoad, this);

    config.items = [this.grdNew7_system];
    
    this.addEvents({'createnew7_system': true, 'editnew7_system': true, 'createaddress': true, 'editaddress': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onGrdNew7_systemLoad: function() {
    if (this.grdNew7_system.getStore().getCount() > 0) {
      this.grdNew7_system.getSelectionModel().select(0);
      var record = this.grdNew7_system.getStore().getAt(0);
      
      this.onGrdNew7_systemSelectChange(record);
    }
  },

  onGrdNew7_systemSelectChange: function(record) {
    //this.pnlAccordion.grdAddressBook.iniGrid(record);
  }
});

/* End of file new7_system_main_panel.tpl */
/* Location: ./templates/base/web/views/new7_system/new7_system_main_panel.tpl */