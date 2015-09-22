Ext.define('Uums.ip_white_list.Ip_white_listDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'ip_white_list-dialog-win';
    config.title = '新建IP';<!--此处需要改-->
    config.modal = true;
    config.width = 350;
    config.iconCls = 'icon-ip_white_list-win';
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
    var ip_white_listId = id || null;
    
    this.frmIp_white_list.form.reset();
    this.frmIp_white_list.form.baseParams['id'] = ip_white_listId;
    
    if (ip_white_listId > 0) {
      this.frmIp_white_list.load({
        url: Uums.ip_white_list.request_url,
	method: 'GET',
        success: function(form, action) {
          Uums.ip_white_list.Ip_white_listDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(UumsLanguage.msgErrTitle, UumsLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      Uums.ip_white_list.Ip_white_listDialog.superclass.show.call(this);
    }
  },
  
  buildForm: function() {
     
    this.frmIp_white_list = Ext.create('Ext.form.FormPanel', {
      url: Uums.ip_white_list.request_url,
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
        {xtype: 'textfield', fieldLabel: 'IP地址', name: 'ip_address', allowBlank: false},<!--此处需要改-->
        /*{xtype: 'textfield', fieldLabel: '修改时间', name: 'update_time', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '修改用户编号', name: 'update_user_id', allowBlank: false}*/
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
    
    return this.frmIp_white_list;
  },
  
  submitForm : function() {
    this.frmIp_white_list.form.submit({
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

/* End of file ip_white_list_dialog.tpl */
/* Location: ./templates/base/web/views/ip_white_list/ip_white_list_dialog.tpl */