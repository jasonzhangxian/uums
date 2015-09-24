
Ext.define('Uums.new7_system.New7_systemGrid', {
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
      fields:[
        'system_id',
        'system_name',
        'sys_code',
        'system_url',
        'update_time',
        'update_user_id'
      ],
      pageSize: Uums.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Uums.new7_system_request_url,
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
    config.columns =[
      { header: '系统编号', dataIndex: 'system_id', width: 80},
      { header: '系统名称', dataIndex: 'system_name', flex:1},
      { header: '系统代码', dataIndex: 'sys_code', flex:1},
      { header: '系统路径', dataIndex: 'system_url', flex:1},
      { header: '修改时间', dataIndex: 'update_time', width:100},
      { header: '修改用户', dataIndex: 'update_user_id', width:100},
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
    '-',
    { 
      text: UumsLanguage.btnExport,
      iconCls: 'invoice',
      handler: this.onExport,
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
  
  onExport: function() {
    //执行导出
    var filter = this.search.getValue() || null;
    var export_loading = new Ext.LoadMask(Ext.get('new7_system-win'),{msg:'正在生成表格文件...'});  
    export_loading.show();
    Ext.Ajax.request({
      url: Uums.new7_system_request_url,
      method: 'GET',
      params: {
        action: 'export',
        search: filter
      },
      timeout: 120000,
      callback: function(options, success, response) {
        export_loading.hide();
        if(success){
          var result = Ext.decode(response.responseText);
          if (result.success == true) {
            Ext.MessageBox.alert(UumsLanguage.msgSuccessTitle, "<a href='{{base_url}}"+result.feedback+"'>导出成功，点击下载文件</a>");
          }else{
            Ext.MessageBox.alert(UumsLanguage.msgErrTitle, result.feedback);
          }
        }else{
          if(response.timedout)
            Ext.MessageBox.alert(UumsLanguage.msgErrTitle, '请求超时，请稍后重试...');
          else
            Ext.MessageBox.alert(UumsLanguage.msgErrTitle, response.statusText);
        }
      },
      scope: this
    });
    //显示下载链接
  },
  onDelete: function(record) {
    var new7_systemId = record.get('system_id');
    <!--new7_system_id-->
    Ext.MessageBox.confirm(
      UumsLanguage.msgWarningTitle, 
      UumsLanguage.msgDeleteConfirm,
      function(btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            url : Uums.new7_system_request_url,
            method: 'DELETE',
            params: {
                system_id: new7_systemId<!--new7_system_id: new7_systemId-->
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
        var new7_systemId = record.get('system_id');<!--new7_system_id-->
        
        switch(action) {
          case 'status-off':
          case 'status-on':
            flag = (action == 'status-on') ? 0 : 1;
            this.onAction(module, new7_systemId, index, flag);

            break;
        }
      }
    }
  },
  
  onAction: function(action, new7_systemId, index, flag) {
    Ext.Ajax.request({
      url: Uums.new7_system.request_url,
      method: 'PUT',
      params: {
        system_id: new7_systemId,<!--new7_system_id-->
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

/* End of file new7_system_grid.tpl */
/* Location: ./templates/base/web/views/new7_system/new7_system_grid.tpl */
