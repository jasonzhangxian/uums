
Ext.define('Uums.user_kpi.UserKpiGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    var statics = this.statics();
    
    config = config || {};
    
    config.region = 'center';
    config.border = false;
    config.viewConfig = {emptyText: UumsLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        'id',
        'user_id',
        'realname',
        'department_name',
        'grade_name',
        'month',
        'kpi1',
        'kpi2',
        'salary',
        'performance_pay'
      ],
      pageSize: Uums.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Uums.user_kpi_request_url,
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
  						window.location.href = '{{ site_url('/admin/login') }}';
  					});
  				}
  			}
  		}
  	});
    config.columns =[
      { header: '真实姓名', dataIndex: 'realname', width: 60},
      { header: '所属部门', dataIndex: 'department_name', width: 80},
      { header: '职级', dataIndex: 'grade_name', flex: 1},
      { header: '月份', dataIndex: 'month', width: 60},
      { header: 'KPI1', dataIndex: 'kpi1', width: 40},
      { header: 'KPI2', dataIndex: 'kpi2', width: 40},
      { header: '基本工资', dataIndex: 'salary', width: 70},
      { header: '绩效工资', dataIndex: 'performance_pay', width: 70},
      {
        xtype:'actioncolumn', 
        width: 60,
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
      text: UumsLanguage.btnImport,
      iconCls: 'quickCreate',
      handler: function() {
        this.fireEvent('import');
      },
      scope: this
    },
    '-',
    { 
      text: UumsLanguage.btnExport,
      iconCls: 'invoice',
      handler: this.onExport,
      scope: this
    },
    '-',
    { 
      text: UumsLanguage.btnExportSum,
      iconCls: 'invoice',
      handler: this.onExportSum,
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
  refreshGrid: function (department_id) {
    
    var store = this.getStore();

    store.getProxy().extraParams['department_id'] = department_id;
    store.load();
  },
  onDelete: function(record) {
    var id = record.get('id');
    
    Ext.MessageBox.confirm(
      UumsLanguage.msgWarningTitle, 
      UumsLanguage.msgDeleteConfirm,
      function(btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            url : Uums.user_kpi_request_url,
			      method: 'DELETE',
            params: {
              id: id
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

  onExport: function() {
    //执行导出
    var filter = this.search.getValue() || null;
    var export_loading = new Ext.LoadMask(Ext.get('user_kpi-win'),{msg:'正在生成表格文件...'});  
    export_loading.show();
    Ext.Ajax.request({
      url: Uums.user_kpi_request_url,
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
  onExportSum: function() {
    //执行导出
    var filter = this.search.getValue() || null;
    var export_loading = new Ext.LoadMask(Ext.get('user_kpi-win'),{msg:'正在生成表格文件...'});  
    export_loading.show();
    Ext.Ajax.request({
      url: Uums.user_kpi_request_url,
      method: 'GET',
      params: {
        action: 'export_sum',
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
        var user_id = record.get('user_id');
        
        switch(action) {
          case 'status-off':
          case 'status-on':
            flag = (action == 'status-on') ? 0 : 1;
            this.onAction(module, user_id, index, flag);

            break;
        }
      }
    }
  },
  
  onAction: function(action, user_id, index, flag) {
    Ext.Ajax.request({
      url: Uums.user_kpi_request_url,
	    method: 'PUT',
      params: {
        user_id: user_id,
        flag: flag
      },
      callback: function(options, success, response) {
        var result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          var store = this.getStore();
          
          store.getAt(index).set('is_deleted', flag);
          
          this.fireEvent('notifysuccess', result.feedback);
        }
      },
      scope: this
    });
  }
});

/* End of file user_kpi_grid.tpl */
/* Location: ./templates/base/web/views/user_kpi/user_kpi_grid.tpl */
