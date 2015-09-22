Ext.define('Uums.admin_logs.Admin_logsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'admin_logs-dialog-win';
    config.title = '新建日志';<!--此处需要改-->
    config.modal = true;
    config.width = 550;
    config.iconCls = 'icon-admin_logs-win';
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
  
  show: function (id) {
    var admin_logsId = id || null;
    
    this.frmAdmin_logs.form.reset();
    this.frmAdmin_logs.form.baseParams['log_id'] = admin_logsId;<!--修改-->
    
    if (admin_logsId > 0) {
      this.frmAdmin_logs.load({
        url: Uums.admin_logs.request_url,
	method: 'GET',
        success: function(form, action) {
          Uums.admin_logs.Admin_logsDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(UumsLanguage.msgErrTitle, UumsLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      Uums.admin_logs.Admin_logsDialog.superclass.show.call(this);
    }
  },
  
  buildForm: function() {
     
    this.frmAdmin_logs = Ext.create('Ext.form.FormPanel', {
      url: Uums.admin_logs.request_url,
	  method: 'POST',
      baseParams: {}, 
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelAlign: 'left',
        labelWidth: 80,
        anchor: '98%'
      },
      items: [
        {xtype: 'textfield', fieldLabel: '日志信息', name: 'log_info', allowBlank: false},<!--此处需要改-->
        /*{
            layout: 'column',
            border: false,
            items:[{
              id: 'is_closed',
              border: false,
              style: "padding-right: 5px;",
              items:[{fieldLabel: '是否启用', xtype:'radio', id: 'statusEnable', name: 'is_closed', boxLabel: '已启用', inputValue: '0', checked: true}]
            },{
              border: false,
              items: [{fieldLabel: '未启用', boxLabel: '未启用', xtype:'radio', name: 'is_closed', hideLabel: true, inputValue: '1'}]
            }]
          }*/
      ]
    });
    
    return this.frmAdmin_logs;
  },
  
  submitForm : function() {
    this.frmAdmin_logs.form.submit({
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

/* End of file admin_logs_dialog.tpl */
/* Location: ./templates/base/web/views/admin_logs/admin_logs_dialog.tpl */