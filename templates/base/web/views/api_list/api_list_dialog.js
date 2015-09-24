Ext.define('Uums.api_list.Api_listDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'api_list-dialog-win';
    config.title = '新建接口';<!--此处需要改-->
    config.modal = true;
    config.width = 350;
    config.iconCls = 'icon-api_list-win';
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
    var api_listId = id || null;
    
    this.frmApi_list.form.reset();
    this.frmApi_list.form.baseParams['id'] = api_listId;
    
    if (api_listId > 0) {
      this.frmApi_list.load({
        url: Uums.api_list_request_url,
        method: 'GET',
        success: function(form, action) {
          Uums.api_list.Api_listDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(UumsLanguage.msgErrTitle, UumsLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      Uums.api_list.Api_listDialog.superclass.show.call(this);
    }
  },
  
  buildForm: function() {
     
    this.frmApi_list = Ext.create('Ext.form.FormPanel', {
      url: Uums.api_list_request_url,
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
        {xtype: 'textfield', fieldLabel: '接口代码', name: 'api_string', allowBlank: false},<!--此处需要改-->
        {xtype: 'textfield', fieldLabel: '接口名称', name: 'api_name', allowBlank: false},
        {
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
          }
      ]
    });
    
    return this.frmApi_list;
  },
  
  submitForm : function() {
    this.frmApi_list.form.submit({
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

/* End of file api_list_dialog.tpl */
/* Location: ./templates/base/web/views/api_list/api_list_dialog.tpl */