
Ext.define('Uums.user_kpi.UplaodDialog', {
    extend: 'Ext.Window',
    
    constructor: function(config) {
        config = config || {};
        
        config.id = 'user_kpi-upload-dialog-win';
        config.title = '数据导入';
        config.width = 350;
        config.modal = false;
        config.iconCls = 'icon-user_kpi-win';
        config.items =  this.buildForm();  
        
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
        
        this.addEvents({'saveSuccess' : true});  
          
        this.callParent([config]);
    },
      
    buildForm: function() {
        this.frmUser_kpi = Ext.create('Ext.form.FormPanel', {
            fileUpload: true,
            url: Uums.user_kpi_request_url+"?action=import",
            enctype: 'multipart/form-data',
            method: 'POST', 
            layoutConfig: {
            	labelSeparator: ''
            },
            border: false,
            bodyPadding: 10,
            fieldDefaults: {
                labelAlign: 'left',
                labelWidth: 80,
                anchor: '98%'
            },
            items: [
            	{xtype: 'fileuploadfield', fieldLabel: '上传文件', name: 'file', anchor: '97%'}
            ]
        });
        
        return this.frmUser_kpi;
    },
    
    submitForm : function() {
        this.frmUser_kpi.form.submit({
            waitMsg: UumsLanguage.formSubmitWaitMsg,
            success: function(form, action) {
                this.fireEvent('saveSuccess', action.result.feedback);
                this.close();  
            },    
            failure: function(form, action) {
                if (action.failureType != 'client') {
					Ext.MessageBox.alert(UumsLanguage.msgErrTitle, action.result.feedback);
                }
            },  
            scope: this
        });   
    }
});