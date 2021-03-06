
Ext.define('Uums.grade.GradeGrid', {
  extend: 'Ext.grid.Panel',
  
  statics: {
    renderStatus: function(status) {
      if(status == 0) {
        return '<img class="img-button" src="{{ icon_status_url('icon_status_green.gif') }}" />&nbsp;<img class="img-button btn-status-off" style="cursor: pointer" src="{{ icon_status_url('icon_status_red_light.gif') }}" />';
      }else {
        return '<img class="img-button btn-status-on" style="cursor: pointer" src="{{ icon_status_url('icon_status_green_light.gif') }}" />&nbsp;<img class="img-button" src= "{{ icon_status_url('icon_status_red.gif') }}" />';
      }
    }
  },
  
  constructor: function(config) {
    var statics = this.statics();
    
    config = config || {};
    
    config.region = 'center';
    config.border = false;
    config.viewConfig = {emptyText: UumsLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[<!--此处需要改-->
        'grade_id',
        'grade_name',
        'department_id',
        'grade_level',
        'parent_id'
      ],
      pageSize: Uums.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Uums.grade_request_url,
        reader: {
          type: 'json',
          root: Uums.CONF.JSON_READER_ROOT,
          error: 'error',
          totalProperty: Uums.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: false
    });
    config.store.load({
  		callback: function ( records, operation, success,response) {
  			if (success) {
  				var text = Ext.JSON.decode(operation.response.responseText);
  				if(typeof(text.error) != 'undefined' && text.error == 'not login'){
  					Ext.MessageBox.alert('提示', '请登录',function(){
  						window.location.href = '/admin/login';
  					});
  				}
  			}
  		}
  	});
    config.columns =[<!--此处需要改-->
      { header: '职位名称', dataIndex: 'grade_name', width: 80},
      { header: '所属部门', dataIndex: 'department_id', flex:1},
      { header: '职位水平', dataIndex: 'grade_level', flex:1},
      { header: '等级编号', dataIndex: 'parent_id', flex:1},
      <!--{ header: '状态', dataIndex: 'is_closed', renderer: statics.renderStatus, width: 80, align: 'center'},-->
      {
        xtype:'actioncolumn', 
        width:80,
        header: '操作',
        items: [{
          iconCls: 'icon-action icon-edit-record',
          tooltip: UumsLanguage.tipEdit,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', rec);
          },
          scope: this
        },{
            iconCls: 'icon-action icon-delete-record',
            tooltip: UumsLanguage.tipDelete,
            handler: function(grid, rowIndex, colIndex) {
              var rec = grid.getStore().getAt(rowIndex);
              
              this.onDelete(rec);
            },
            scope: this                
        }]
      }
    ];
    
    config.listeners = {
      itemclick: this.onClick
    };
    
    config.search = Ext.create('Ext.form.TextField', {name: 'search', width: 130});
    
    config.tbar = [
    {
      text: UumsLanguage.btnAdd,
      iconCls: 'add',
      handler: function() {
        this.fireEvent('create');
      },
      scope: this
    },
    '-',
    { 
      text: UumsLanguage.btnRefresh,
      iconCls: 'refresh',
      handler: this.onRefresh,
      scope: this
    },
    '->',
    config.search,
    '',
    {
      iconCls: 'search',
      handler: this.onSearch,
      scope: this
    }];
   
    config.dockedItems = [{
      xtype: 'pagingtoolbar',
      store: config.store,
      dock: 'bottom',
      displayInfo: true
    }];  
    
    this.addEvents({'selectchange' : true, 'create' : true, 'edit': true, 'notifysuccess': true});  
    
    this.callParent([config]);
  },
  
  onRefresh: function() {
    this.getStore().load();
  },
  
  onDelete: function(record) {
    var gradeId = record.get('grade_id');
    <!--grade_id-->
    Ext.MessageBox.confirm(
      UumsLanguage.msgWarningTitle, 
      UumsLanguage.msgDeleteConfirm,
      function(btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            url : Uums.grade_request_url,
            method: 'DELETE',
            params: {
                grade_id: gradeId<!--grade_id: gradeId-->
            },
            callback: function(options, success, response) {
              var result = Ext.decode(response.responseText);
              
              if (result.success == true) {
                this.onRefresh();
                
                this.fireEvent('notifysuccess', result.feedback);
              } else {
                Ext.MessageBox.alert(UumsLanguage.msgErrTitle, result.feedback);
              }
            }, 
            scope: this
          });
        }
      }, 
      this
    );
  },
  
  onSearch: function () {
    var filter = this.search.getValue() || null;
    var store = this.getStore();

    store.getProxy().extraParams['search'] = filter;
    store.load();
  },
  
  onClick: function(view, record, item, index, e) {
    if (!e.getTarget(".icon-action"))
    {
      this.fireEvent('selectchange', record);
    }
    
    var action = false;
    var module = 'set_status';
  
    if (index !== false) {
      var btn = e.getTarget(".img-button");
      
      if (btn) {
        action = btn.className.replace(/img-button btn-/, '').trim();
      }

      if (action != 'img-button') {
        var record = this.getStore().getAt(index);
        var gradeId = record.get('grade_id');<!--grade_id-->
        
        switch(action) {
          case 'status-off':
          case 'status-on':
            flag = (action == 'status-on') ? 0 : 1;
            this.onAction(module, gradeId, index, flag);

            break;
        }
      }
    }
  },
  
  onAction: function(action, gradeId, index, flag) {
    Ext.Ajax.request({
      url: Uums.grade_request_url,
      method: 'PUT',
      params: {
        grade_id: gradeId,<!--grade_id-->
        flag: flag
      },
      callback: function(options, success, response) {
        var result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          var store = this.getStore();
          
          store.getAt(index).set('is_closed', flag);<!--此处需要改-->
          
          this.fireEvent('notifysuccess', result.feedback);
        }
      },
      scope: this
    });
  }
});

/* End of file grade_grid.tpl */
/* Location: ./templates/base/web/views/grade/grade_grid.tpl */
