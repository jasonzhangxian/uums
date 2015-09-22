Ext.define('Uums.new7_system.New7_systemDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'new7_system-dialog-win';
    config.title = '新建系统';<!--此处需要改-->
    config.modal = true;
    config.width = 350;
    config.iconCls = 'icon-new7_system-win';
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
    var new7_systemId = id || null;
    
    this.frmNew7_system.form.reset();
    this.frmNew7_system.form.baseParams['system_id'] = new7_systemId;<!--修改-->
    
    if (new7_systemId > 0) {
      this.frmNew7_system.load({
        url: Uums.new7_system.request_url,
	method: 'GET',
        success: function(form, action) {
          Uums.new7_system.New7_systemDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(UumsLanguage.msgErrTitle, UumsLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      Uums.new7_system.New7_systemDialog.superclass.show.call(this);
    }
  },
  
  buildForm: function() {
     
    this.frmNew7_system = Ext.create('Ext.form.FormPanel', {
      url: Uums.new7_system.request_url,
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
        <!--此处需要改-->
        {xtype: 'textfield', fieldLabel: '系统名称', name: 'system_name', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '系统代码', name: 'sys_code', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '系统路径', name: 'system_url', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '系统密钥', name: 'secret_key', allowBlank: false},
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
    
    return this.frmNew7_system;
  },
  
  submitForm : function() {
    this.frmNew7_system.form.submit({
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

/* End of file new7_system_dialog.tpl */
/* Location: ./templates/base/web/views/new7_system/new7_system_dialog.tpl */