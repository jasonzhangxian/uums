
Ext.define('Uums.admin_user.AdminUserGrid', {
  extend: 'Ext.grid.Panel',
  
  statics: {
    renderStatus: function(status) {
      if(status == 1) {
        return '<img class="img-button btn-status-on" style="cursor: pointer" src="{{ icon_status_url('icon_status_green_light.gif') }}" />&nbsp;<img class="img-button" src= "{{ icon_status_url('icon_status_red.gif') }}" />';
      }else {
        return '<img class="img-button" src="{{ icon_status_url('icon_status_green.gif') }}" />&nbsp;<img class="img-button btn-status-off" style="cursor: pointer" src="{{ icon_status_url('icon_status_red_light.gif') }}" />';
      }
    },
    renderSex: function(sex) {
      if(sex == 1)
        return '男';
      else if(sex == 2)
        return '女';
      else
        return '未知';
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
        'user_id',
        'username',
        'realname',
        'new7_code',
        'sex',
        'mobile',
        'weixin_no',
        'email',
        'entry_time',
        'is_deleted'
      ],
      pageSize: Uums.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Uums.admin_user.request_url,
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
      { header: 'ID号', dataIndex: 'user_id', flex: 1},
      { header: '用户账号', dataIndex: 'username', width: 120},
      { header: '真实姓名', dataIndex: 'realname', width: 80},
      { header: '手机号', dataIndex: 'mobile', width: 120},
      { header: '性别', dataIndex: 'sex', renderer: statics.renderSex, width: 60, align: 'center'},
      { header: '状态', dataIndex: 'is_deleted', renderer: statics.renderStatus, width: 60, align: 'center'},
      { header: '邮箱', dataIndex: 'email', width: 120},
      {
        xtype:'actioncolumn', 
        width:40,
        header: '操作',
        items: [{
          iconCls: 'icon-action icon-edit-record',
          tooltip: UumsLanguage.tipEdit,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', rec);
          },
          scope: this
        }/*,{
            iconCls: 'icon-action icon-delete-record',
            tooltip: UumsLanguage.tipDelete,
            handler: function(grid, rowIndex, colIndex) {
              var rec = grid.getStore().getAt(rowIndex);
              
              this.onDelete(rec);
            },
            scope: this                
        }*/]
      },
      { header: '工号', dataIndex: 'new7_code', width: 120},
      { header: '微信号', dataIndex: 'weixin_no', width: 120},
      { header: '入职时间', dataIndex: 'entry_time', width: 120}
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
    var user_id = record.get('user_id');
    
    Ext.MessageBox.confirm(
      UumsLanguage.msgWarningTitle, 
      UumsLanguage.msgDeleteConfirm,
      function(btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            url : Uums.admin_user.request_url,
			      method: 'DELETE',
            params: {
              user_id: user_id
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
      url: Uums.admin_user.request_url,
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

/* End of file admin_user_grid.tpl */
/* Location: ./templates/base/web/views/admin_user/admin_user_grid.tpl */
