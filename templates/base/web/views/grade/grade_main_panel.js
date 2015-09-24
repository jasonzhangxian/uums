
Ext.define('Uums.grade.mainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.layout = 'border';
    
    config.treeGrade = Ext.create('Uums.grade.GradeTreePanel');
    
    config.treeGrade.on('selectchange', this.onTreeGradeSelectChange, this);
    config.treeGrade.on('create', function() {this.fireEvent('creategrade');}, this);
    config.treeGrade.on('edit', function(rec) {this.fireEvent('editgrade', rec);}, this);
    config.treeGrade.on('delete', function(rec) {this.fireEvent('deletegrade', rec);}, this);
    config.treeGrade.on('notifysuccess', function(feedback) {this.fireEvent('notifysuccess', feedback);}, this);

    config.items = [config.treeGrade];
    
    this.addEvents({'creategrade': true, 'editgrade': true, 'createaddress': true, 'editaddress': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onGrdGradeLoad: function() {
    if (this.treeGrade.getStore().getCount() > 0) {
      this.treeGrade.getSelectionModel().select(0);
      var record = this.treeGrade.getStore().getAt(0);
      
      this.onTreeGradeSelectChange(record);
    }
  },

  onTreeGradeSelectChange: function(record) {
    //this.pnlAccordion.grdAddressBook.iniGrid(record);
  }
});

/* End of file grade_main_panel.tpl */
/* Location: ./templates/base/web/views/grade/grade_main_panel.tpl */