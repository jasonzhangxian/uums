Ext.define('Uums.api_logs.Api_logsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'api_logs-dialog-win';
    config.title = '新建日志';<!--此处需要改-->
    config.modal = true;
    config.width = 350;
    config.iconCls = 'icon-api_logs-win';
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
    var api_logsId = id || null;
    
    this.frmApi_logs.form.reset();
    this.frmApi_logs.form.baseParams['lid'] = api_logsId;<!--修改-->
    
    if (api_logsId > 0) {
      this.frmApi_logs.load({
        url: Uums.api_logs_request_url,
        method: 'GET',
        success: function(form, action) {
          Uums.api_logs.Api_logsDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(UumsLanguage.msgErrTitle, UumsLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      Uums.api_logs.Api_logsDialog.superclass.show.call(this);
    }
  },
  
  buildForm: function() {
     
    this.frmApi_logs = Ext.create('Ext.form.FormPanel', {
      url: Uums.api_logs_request_url,
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
        {xtype: 'textfield', fieldLabel: '接口名称', name: 'api', allowBlank: false},<!--此处需要改-->
        {xtype: 'textfield', fieldLabel: 'IP地址', name: 'ip_address', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '系统代码', name: 'system_code', allowBlank: false},
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
    
    return this.frmApi_logs;
  },
  
  submitForm : function() {
    this.frmApi_logs.form.submit({
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

/* End of file api_logs_dialog.tpl */
/* Location: ./templates/base/web/views/api_logs/api_logs_dialog.tpl */