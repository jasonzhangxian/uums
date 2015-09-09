Ext.define('Uums.quarters.QuartersDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'quarters-dialog-win';
    config.title = '新建岗位';
    config.modal = true;
    config.width = 350;
    config.iconCls = 'icon-quarters-win';
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
    var quartersId = id || null;
    
    this.frmQuarters.form.reset();
    this.frmQuarters.form.baseParams['quarters_id'] = quartersId;
    
    if (quartersId > 0) {
      this.frmQuarters.load({
        url: Uums.quarters.request_url,
		method: 'GET',
        success: function(form, action) {
          Uums.quarters.QuartersDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(UumsLanguage.msgErrTitle, UumsLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      Uums.quarters.QuartersDialog.superclass.show.call(this);
    }
  },
  
  buildForm: function() {
     
    this.frmQuarters = Ext.create('Ext.form.FormPanel', {
      url: Uums.quarters.request_url,
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
        {xtype: 'textfield', fieldLabel: '岗位名称', name: 'quarters_name', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '岗位描述', name: 'quarters_desc', allowBlank: false},
        {xtype: 'checkbox',anchor: '', fieldLabel: '是否启用', name: 'status'}
      ]
    });
    
    return this.frmQuarters;
  },
  
  submitForm : function() {
    this.frmQuarters.form.submit({
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

/* End of file quarters_dialog.tpl */
/* Location: ./templates/base/web/views/quarters/quarters_dialog.tpl */