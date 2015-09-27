Ext.define('Uums.user_kpi.UserKpiDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    this.treeLoading = config.treeLoading;
    this.user_id = config.user_id;

    config.id = 'user_kpi-dialog-win';
    config.title = '新建绩效信息';
    config.width = 220;
    config.height = 320;
    config.modal = true;
    config.iconCls = 'icon-user_kpi-win';
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
    
    this.frmUserKpi.form.reset();
    this.frmUserKpi.form.baseParams['user_id'] = user_id;
    
    this.dsAdminUser.load();
    if (user_id > 0) {
      this.frmUserKpi.load({
        url: Uums.user_kpi_request_url,
        method: 'GET', 
        success: function(form, action) {
          Uums.user_kpi.UserKpiDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(UumsLanguage.msgErrTitle, UumsLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      Uums.user_kpi.UserKpiDialog.superclass.show.call(this);
    }
  },
  
  buildForm: function() {

    this.frmUserKpi = Ext.create('Ext.form.FormPanel', {
      url: Uums.user_kpi_request_url,
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
        this.getAdminPanel()
      ]
    });
    
    return this.frmUserKpi;
  },
  
  
  getAdminPanel: function() {

    this.dsAdminUser = Ext.create('Ext.data.Store', {
      fields:[
        'user_id', 
        'realname'
      ],
      proxy: {
        type: 'ajax',
        url : Uums.admin_user_request_url,
        method: 'GET',
        baseParams: {action: 'list'}, 
        reader: {
          type: 'json',
          root: Uums.CONF.JSON_READER_ROOT,
          totalProperty: Uums.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    this.cboAdminUser = Ext.create('Ext.form.ComboBox', {
      fieldLabel: '用户姓名',
      store: this.dsAdminUser,
      queryMode: 'local',
      valueField: 'user_id',
      displayField: 'realname',
      name: 'user_id',
      triggerAction: 'all'
    });
    var items = [];
    items.push(this.cboAdminUser);
    items.push({xtype: 'textfield', fieldLabel: '所属部门', name: 'department_name', allowBlank: false});
    items.push({xtype: 'textfield', fieldLabel: '职级', name: 'grade_name', allowBlank: false});
    items.push({xtype: 'datefield', fieldLabel: '月份', name: 'month',editable: false, format: 'Y-m'});
    items.push({xtype: 'textfield', fieldLabel: 'KPI1', name: 'kpi1', allowBlank: false});
    items.push({xtype: 'textfield', fieldLabel: 'KPI2', name: 'kpi2', allowBlank: true});
    items.push({xtype: 'textfield', fieldLabel: '基本工资', name: 'salary', allowBlank: true});
    items.push({xtype: 'textfield', fieldLabel: '绩效工资', name: 'performance_pay', allowBlank: true});
    this.pnlAdmin = Ext.create('Ext.Panel', {
      region: 'center',
      border: true,
      width: 280,
      bodyPadding: 10,
      layout: 'anchor',
      items: items
    });

    return this.pnlAdmin;
  },
  
  setAdminUser: function (data) {
        var realname = Ext.getCmp("realname");
        realname.setValue(data.text);
        var user_id = Ext.getCmp("user_id");
        user_id.setValue(data.id);
  },

  submitForm : function() {
    

    this.frmUserKpi.form.submit({
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

/* End of file user_kpi_dialog.tpl */
/* Location: ./templates/base/web/views/user_kpi/user_kpi_dialog.tpl */