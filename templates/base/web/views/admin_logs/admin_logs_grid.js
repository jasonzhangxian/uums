
Ext.define('Uums.admin_logs.Admin_logsGrid', {
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
        'log_id',
        'admin_id',
        'log_info',
        'log_time'
      ],
      pageSize: Uums.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Uums.admin_logs_request_url,
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
      { header: '用户名称', dataIndex: 'admin_id', width: 120},
      { header: '操作内容', dataIndex: 'log_info', flex:1},
      { header: '操作时间', dataIndex: 'log_time', width:120},
      {
        xtype:'actioncolumn', 
        width:60,
        header: '操作',
        items: [{
         
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
    var admin_logsId = record.get('log_id');
    <!--admin_logs_id-->
    Ext.MessageBox.confirm(
      UumsLanguage.msgWarningTitle, 
      UumsLanguage.msgDeleteConfirm,
      function(btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            url : Uums.admin_logs_request_url,
            method: 'DELETE',
            params: {
                log_id: admin_logsId<!--admin_logs_id: admin_logsId-->
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
        var admin_logsId = record.get('log_id');<!--admin_logs_id-->
        
        switch(action) {
          case 'status-off':
          case 'status-on':
            flag = (action == 'status-on') ? 0 : 1;
            this.onAction(module, admin_logsId, index, flag);

            break;
        }
      }
    }
  },
  
  onAction: function(action, admin_logsId, index, flag) {
    Ext.Ajax.request({
      url: Uums.admin_logs_request_url,
	  method: 'PUT',
      params: {
        log_id: admin_logsId,
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

/* End of file admin_logs_grid.tpl */
/* Location: ./templates/base/web/views/admin_logs/admin_logs_grid.tpl */
