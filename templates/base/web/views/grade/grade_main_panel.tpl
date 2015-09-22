
Ext.define('Uums.grade.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.layout = 'border';
    
    this.grdGrade = Ext.create('Uums.grade.GradeGrid');
    
    this.grdGrade.on('selectchange', this.onGrdGradeSelectChange, this);
    this.grdGrade.on('create', function() {this.fireEvent('creategrade');}, this);
    this.grdGrade.on('edit', function(rec) {this.fireEvent('editgrade', rec);}, this);
    this.grdGrade.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback);}, this);
    this.grdGrade.getStore().on('load', this.onGrdGradeLoad, this);

    config.items = [this.grdGrade];
    
    this.addEvents({'creategrade': true, 'editgrade': true, 'createaddress': true, 'editaddress': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onGrdGradeLoad: function() {
    if (this.grdGrade.getStore().getCount() > 0) {
      this.grdGrade.getSelectionModel().select(0);
      var record = this.grdGrade.getStore().getAt(0);
      
      this.onGrdGradeSelectChange(record);
    }
  },

  onGrdGradeSelectChange: function(record) {
    //this.pnlAccordion.grdAddressBook.iniGrid(record);
  }
});

/* End of file grade_main_panel.tpl */
/* Location: ./templates/base/web/views/grade/grade_main_panel.tpl */