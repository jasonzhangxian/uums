
Ext.define('Uums.privilege.PrivilegeGrid', {
  extend: 'Ext.grid.Panel',
  
  statics: {
    renderStatus : function(status) {
      if(status == 1) {
        return '<img class="img-button" src="{{ icon_status_url('icon_status_green.gif') }}" />&nbsp;<img class="img-button btn-status-off" style="cursor: pointer" src="{{ icon_status_url('icon_status_red_light.gif') }}" />';
      }else {
        return '<img class="img-button btn-status-on" style="cursor: pointer" src="{{ icon_status_url('icon_status_green_light.gif') }}" />&nbsp;<img class="img-button" src= "{{ icon_status_url('icon_status_red.gif') }}" />';
      }
    },
    renderType : function(type) {
      if(type == 1) {
        return '菜单';
      }else {
        return '按钮';
      }
    }
  },
  
  constructor: function(config) {
    var statics = this.statics();
    
    config = config || {};
    
    config.region = 'center';
    config.viewConfig = {emptyText: UumsLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        'privilege_id', 
        'privilege_name',
		'parent_name',
		'system_name',
		'update_time',
		'update_user_name',
        'status',
		'type',
		'order'
      ],
      pageSize: Uums.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Uums.privilege.request_url,
		extraParams : {action:'list'},
        reader: {
          type: 'json',
          root: Uums.CONF.JSON_READER_ROOT,
          totalProperty: Uums.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: false
    });
    
    config.selModel = Ext.create('Ext.selection.CheckboxModel');
    config.columns =[
      { header: '权限名称', dataIndex: 'privilege_name', sortable: true, flex: 1},
      { header: '所属上级', dataIndex: 'parent_name', sortable: true, width:80},
      { header: '所属系统', dataIndex: 'system_name', sortable: true, width:100},
      { header: '修改时间', dataIndex: 'update_time', sortable: true, width:120},
      { header: '修改人', dataIndex: 'update_user_name', sortable: true, width:60},
      { header: '是否启用', align: 'center', renderer: statics.renderStatus, dataIndex: 'status',width:60},
      { header: '类型', dataIndex: 'type', renderer: statics.renderType, sortable: true, width:40},
      { header: '排序', dataIndex: 'sort_order', sortable: true, width:40},
      {
        xtype: 'actioncolumn', 
        width: 80,
        header: '操作',
        items: [{
          tooltip: UumsLanguage.tipEdit,
          iconCls: 'icon-action icon-edit-record',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', rec);
          },
          scope: this
        },
        {
          iconCls: 'icon-action icon-move-record',
          tooltip: UumsLanguage.tipMove,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('moveprivilege', rec);
          },
          scope: this        
        },
        {
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
    
    config.search = Ext.create('Ext.form.TextField', {
      width: 150,
      paramName: 'search'
    });
    
    config.tbar = [
      {
        text: UumsLanguage.btnAdd,
        iconCls: 'add',
        handler: function() {this.fireEvent('create', this.privilegeId);},
        scope: this
      },
      '-', 
      {
        text: UumsLanguage.btnDelete,
        iconCls: 'remove',
        handler: this.onBatchDelete,
        scope: this
      },
      '-',
      {
        text: UumsLanguage.btnMove,
        iconCls: 'icon-move-record',
        handler: this.onBathMove,
        scope: this
      }, 
      '-',
      {
        text: UumsLanguage.btnRefresh,
        iconCls: 'refresh',
        handler: this.onSearch,
        scope: this
      }, 
      '->',
      config.search,
      '',
      {
        iconCls: 'search',
        handler: this.onSearch,
        scope: this
      }
    ];
    
    config.listeners = {
      itemclick: this.onClick
    };
    
    config.dockedItems = [{
      xtype: 'pagingtoolbar',
      store: config.store,
      dock: 'bottom',
      displayInfo: true
    }];
    
    this.addEvents({'deletesuccess': true, 'create': true, 'moveprivilege': true, 'batchmoveprivilege': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  refreshGrid: function (privilegeId) {
    this.privilegeId = privilegeId;
    
    var store = this.getStore();

    store.getProxy().extraParams['privilege_id'] = privilegeId;
    store.load();
  },
  
  onDelete: function(record) {
    var privilegeId = record.get('privilege_id');
    
    Ext.MessageBox.confirm(
      UumsLanguage.msgWarningTitle, 
      UumsLanguage.msgDeleteConfirm, 
      function (btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            waitMsg: UumsLanguage.formSubmitWaitMsg,
            url: Uums.privilege.request_url,
            method: 'DELETE',
            params: {
              privilege_id: privilegeId
            },
            callback: function (options, success, response) {
              var result = Ext.decode(response.responseText);
              
              if (result.success == true) {
                this.fireEvent('deletesuccess', result.feedback);
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
  
  onBatchDelete: function() {
    var selections = this.selModel.getSelection();
    
    keys = [];
    Ext.each(selections, function(item) {
      keys.push(item.get('privilege_id'));
    });
    
    if (keys.length > 0) {
      var batch = Ext.JSON.encode(keys);
      
      Ext.MessageBox.confirm(
        UumsLanguage.msgWarningTitle, 
        UumsLanguage.msgDeleteConfirm,
        function(btn) {
          if (btn == 'yes') {
            Ext.Ajax.request({
              waitMsg: UumsLanguage.formSubmitWaitMsg,
              url: '{{ site_url('privilege/delete_privilege') }}',
              params: {
                batch: batch
              },
              callback: function(options, success, response) {
                var result = Ext.decode(response.responseText);
                
                if (result.success == true) {
                  this.fireEvent('deletesuccess', result.feedback);
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
    } else {
      Ext.MessageBox.alert(UumsLanguage.msgInfoTitle, UumsLanguage.msgMustSelectOne);
    }
  },
  
  onBathMove: function() {
    var selections = this.selModel.getSelection();
    
    keys = [];
    Ext.each(selections, function(item) {
      keys.push(item.get('privilege_id'));
    });
    
    if (keys.length > 0) {
      this.fireEvent('batchmoveprivilege', keys);
    }else {
      Ext.MessageBox.alert(UumsLanguage.msgInfoTitle, UumsLanguage.msgMustSelectOne);
    }
  },
  
  onSearch: function () {
    var filter = this.search.getValue() || null;
    var store = this.getStore();
    store.getProxy().extraParams['search'] = filter;
    
    store.load();
  },
  
  onClick: function(view, record, item, index, e) {
    var action = false;
    var module = 'set_status';
    
    var btn = e.getTarget(".img-button");
    if (!Ext.isEmpty(btn)) {
      action = btn.className.replace(/img-button btn-/, '').trim();

      if (action != 'img-button') {
        var privilegeId = this.getStore().getAt(index).get('privilege_id');
        
        switch(action) {
          case 'status-off':
            flag = (action == 'status-on') ? 1 : 0;
            
            Ext.MessageBox.confirm(
              UumsLanguage.msgWarningTitle, 
              UumsLanguage.msgDisableProducts, 
              function (btn) {
                if (btn == 'no') {
                  this.onAction(module, privilegeId, flag, 0, index);
                } else{
                  this.onAction(module, privilegeId, flag, 1, index);
                }
              }, 
              this
            );  
            
            break;               
          case 'status-on':
            flag = (action == 'status-on') ? 1 : 0;
            
            this.onAction(module, privilegeId, flag, 0, index);
            break;
        }
      }
    }
  },
  
  onAction: function(action, privilegeId, flag, product_flag, index) {
    Ext.Ajax.request({
      url: Uums.privilege.request_url,
      method: 'PUT',
      params: {
        privilege_id: privilegeId,
        status: flag,
        children_status: product_flag
      },
      callback: function(options, success, response) {
        var result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          var store = this.getStore();
          
          store.getAt(index).set('status', flag);
          store.getAt(index).commit();
          
          this.fireEvent('notifysuccess', result.feedback);
        }
      },
      scope: this
    });
  }
});

/* End of file privilege_grid.php */
/* Location: ./templates/base/web/views/privilege/privilege_grid.php */