
Ext.define('Uums.grade.GradeTreePanel', {
  extend: 'Ext.tree.TreePanel',
  
  constructor: function(config) {
    config = config || {};
    
    config.region = 'center';
    config.border = false;
    config.autoScroll = true;
    config.containerScroll = true;
    config.split = true;
    config.width = 200;
    config.rootVisible = false;
    config.currentGradeId = '';
    
    config.store = Ext.create('Ext.data.TreeStore', {
      proxy: {
        type: 'ajax',
        url : Uums.grade_request_url,
        extraParams : {action:'tree'}
      },
      root: {
        id: 0,
        text: '--所有权限--',
        leaf: false,
        expandable: true,  
        expanded: true  
      },
      listeners: {
        'load': function() {
          this.expandAll();
          //this.setGradeId(1);
        },
        scope: this
      }
    });
    
    config.tbar = [{
      text: UumsLanguage.btnAdd,
      iconCls: 'add',
      handler: function() {
        this.fireEvent('create');
      },
      scope: this
    },{
      text: UumsLanguage.btnEdit,
      iconCls: 'edit',
      handler: function() {
        this.fireEvent('edit',this.currentGradeId);
      },
      scope: this
    },{
      text: UumsLanguage.btnDelete,
      iconCls: 'remove',
      handler: function() {
        this.fireEvent('delete',this.currentGradeId);
      },
      scope: this
    },{
      text: UumsLanguage.btnRefresh,
      iconCls: 'refresh',
      handler: this.refresh,
      scope: this
    }];
    
    
    config.listeners = {
      "itemclick": this.onGradeNodeClick,
      scope: this
    };
    
    this.callParent([config]);
  },
  
  onGradeNodeClick: function (view, record, node) {
    var gradeId = record.get('id');
    this.setGradeId(gradeId);
  },

  setGradeId: function(gradeId) {
    this.currentGradeId = gradeId;
  },  
  
  refresh: function() {
    this.getStore().load();
  }
});

/* End of file grade_grid.tpl */
/* Location: ./templates/base/web/views/grade/grade_grid.tpl */
