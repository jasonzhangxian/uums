
Ext.define('Uums.quarters.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.layout = 'border';
    
    this.grdQuarters = Ext.create('Uums.quarters.QuartersGrid');
    
    this.grdQuarters.on('selectchange', this.onGrdQuartersSelectChange, this);
    this.grdQuarters.on('create', function() {this.fireEvent('createquarters');}, this);
    this.grdQuarters.on('edit', function(rec) {this.fireEvent('editquarters', rec);}, this);
    this.grdQuarters.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback);}, this);
    this.grdQuarters.getStore().on('load', this.onGrdQuartersLoad, this);

    config.items = [this.grdQuarters];
    
    this.addEvents({'createquarters': true, 'editquarters': true, 'createaddress': true, 'editaddress': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onGrdQuartersLoad: function() {
    if (this.grdQuarters.getStore().getCount() > 0) {
      this.grdQuarters.getSelectionModel().select(0);
      var record = this.grdQuarters.getStore().getAt(0);
      
      this.onGrdQuartersSelectChange(record);
    }
  },

  onGrdQuartersSelectChange: function(record) {
    //this.pnlAccordion.grdAddressBook.iniGrid(record);
  }
});

/* End of file quarters_main_panel.tpl */
/* Location: ./templates/base/web/views/quarters/quarters_main_panel.tpl */