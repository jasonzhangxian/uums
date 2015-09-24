Ext.define('Uums.admin_user.AdminUserDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    this.treeLoading = config.treeLoading;
    this.user_id = config.user_id;

    config.id = 'admin_user-dialog-win';
    config.title = '新建用户';
    config.width = 520;
    config.height = 400;
    config.modal = true;
    config.iconCls = 'icon-admin_user-win';
    config.layout = 'fit';
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: UumsLanguage.btnSave,
        handler: function() {
          this.submitForm();
        },
        scope: this
      },
      {
        text: UumsLanguage.btnClose,
        handler: function() { 
          this.close();
        },
        scope: this
      }
    ];
    
    this.addEvents({'savesuccess' : true});  
    
    this.callParent([config]);
  },
  
  show: function (user_id) {
    var user_id = user_id || null;
    
    this.frmAdminUser.form.reset();
    this.frmAdminUser.form.baseParams['user_id'] = user_id;
    
    if (user_id > 0) {
      this.frmAdminUser.load({
        url: Uums.admin_user_request_url,
        method: 'GET',
        success: function(form, action) {
          Uums.admin_user.AdminUserDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(UumsLanguage.msgErrTitle, UumsLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      Uums.admin_user.AdminUserDialog.superclass.show.call(this);
    }
  },
  
  buildForm: function() {
     
    this.frmAdminUser = Ext.create('Ext.form.FormPanel', {
      url: Uums.admin_user_request_url,
      method: 'POST',
      baseParams: {}, 
      border: false,
      layout: 'border',
      fieldDefaults: {
        labelAlign: 'left',
        labelWidth: 80,
        anchor: '98%'
      },
      items: [
        this.getAdminPanel(),
        this.getQuartersPanel()
      ]
    });
    
    return this.frmAdminUser;
  },
  
  
  getAdminPanel: function() {
    
    this.pnlAdmin = Ext.create('Ext.Panel', {
      region: 'west',
      border: true,
      width: 280,
      bodyPadding: 10,
      layout: 'anchor',
      items: [
        {xtype: 'textfield', fieldLabel: '用户账号', name: 'username', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '密码', name: 'password', allowBlank: true},
        {xtype: 'textfield', fieldLabel: '真实姓名', name: 'realname', allowBlank: false},
        {
          layout: 'column',
          border: false,
          items:[{
            id: 'sex',
            border: false,
            style: "padding-right: 5px;",
            items: [{fieldLabel: '性别', boxLabel: '男', xtype:'radio', name: 'sex', hideLabel: false, inputValue: '1'}]
          },{
            border: false,
            style: "padding-right: 5px;",
            items: [{fieldLabel: '性别', boxLabel: '女', xtype:'radio', name: 'sex', hideLabel: true, inputValue: '2'}]
          }]
        },
        {
            id: "department_name",
            xtype: "uums_department_editor",
            parentItem: this,
            fieldLabel: "所属部门",
            editable: false,
            width: 250,
            allowBlank: false
        },
        {id: "department_id", xtype: "hidden", name: "department_id", value: 0},
        {
            id: "grade_name",
            xtype: "uums_grade_editor",
            parentItem: this,
            fieldLabel: "职级",
            editable: false,
            width: 250,
            allowBlank: false
        },
        {id: "grade_id", xtype: "hidden", name: "grade_id", value: 0},
        {xtype: 'textfield', fieldLabel: '手机号', name: 'mobile', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '微信号', name: 'weixin_no', allowBlank: true},
        {xtype: 'textfield', fieldLabel: '邮箱', name: 'email', allowBlank: true},
        {xtype: 'textfield', fieldLabel: '工号', name: 'new7_code', allowBlank: true},
        {xtype: 'datefield', fieldLabel: '入职时间', name: 'entry_time',editable: false, format: 'Y-m-d'},
        {
          layout: 'column',
          border: false,
          items:[{
            id: 'is_deleted',
            border: false,
            style: "padding-right: 5px;",
            items:[{fieldLabel: '是否启用', xtype:'radio', id: 'statusEnable', name: 'is_deleted', boxLabel: '已启用', inputValue: '0', checked: true}]
          },{
            border: false,
            items: [{fieldLabel: '是否启用', boxLabel: '已停用', xtype:'radio', name: 'is_deleted', hideLabel: true, inputValue: '1'}]
          }]
        }]
    });
    
    return this.pnlAdmin;
  },
  setDepartment: function (data) {
        var department_name = Ext.getCmp("department_name");
        department_name.setValue(data.text);
        var department_id = Ext.getCmp("department_id");
        department_id.setValue(data.id);
  },
  setGrade: function (data) {
        var grade_name = Ext.getCmp("grade_name");
        grade_name.setValue(data.text);
        var grade_id = Ext.getCmp("grade_id");
        grade_id.setValue(data.id);
  },

  getQuartersPanel: function() {
    
    var extraParams = {action:'tree'};
    
    if (this.user_id > 0)
    {
      extraParams.user_id = this.user_id;
    }    
   
    var dsQuarters = Ext.create('Ext.data.TreeStore', {
      proxy: {
        type: 'ajax',
        url : '{{ site_url('admin/quarters/quarters') }}',
        extraParams: extraParams
      }
    });
    
    this.pnlQuarters = Ext.create('Ext.tree.TreePanel', {
      name: 'access_modules',
      id: 'access_modules',
      region: 'center',
      store: dsQuarters,
      bodyPadding: 10,
      rootVisible: false,
      border: true,
      autoScroller: true,
      dockedItems: [{
        xtype: 'toolbar',
        items: [
          {'text':'设置岗位'}    
        ]
      }]
    });
    
    return this.pnlQuarters;
  },
  
  loadQuarters: function(user_id) {
    this.pnlQuarters.getStore().on('beforeload', function() {
      var proxy = this.pnlQuarters.getStore().getProxy();
    
      proxy.extraParams['user_id'] = user_id;
    }, this);
  },

  submitForm : function() {
    var quarters = [];
    var checkedRecords = this.pnlQuarters.getChecked();
    
    if (!Ext.isEmpty(checkedRecords)) {
      Ext.each(checkedRecords, function(record) {
        quarters.push(record.get('id'));
      });
    }

    this.frmAdminUser.form.submit({
      params: {
        quarters: Ext.JSON.encode(quarters)
      },
      waitMsg: UumsLanguage.formSubmitWaitMsg,
      success: function(form, action) {
         this.fireEvent('savesuccess', action.result.feedback);
         
         this.close();  
      },    
      failure: function(form, action) {
        if (action.failureType != 'client') {
          Ext.Msg.alert(UumsLanguage.msgErrTitle, action.result.feedback);
        }
      },  
      scope: this
    });   
  }
});

/* End of file admin_user_dialog.tpl */
/* Location: ./templates/base/web/views/admin_user/admin_user_dialog.tpl */