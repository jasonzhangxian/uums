


Ext.define('Uums.privilege.PrivilegeDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'privilege-dialog-win';
    config.title = '新建权限';
    config.layout = 'fit';
    config.width = 320;
    config.height = 380;
    config.modal = true;
    config.iconCls = 'icon-privilege-win';
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: UumsLanguage.btnSave,
        handler: function () {
          this.submitForm();
        },
        scope: this
      }, 
      {
        text: UumsLanguage.btnClose,
        handler: function () {
          this.close();
        },
        scope: this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function(id) {
    var privilegeId = id || null;
    
    if (privilegeId > 0) {
      this.frmPrivilege.form.baseParams['privilege_id'] = privilegeId;
       
      this.frmPrivilege.load({
        url: Uums.privilege_request_url,
        method: 'GET',
        success: function (form, action) {
          // get the parent id of the loading privilege.
          var sys_code = action.result.data.sys_code;
          var parent_id = action.result.data.parent_id;
          var type = action.result.data.type;
          var status = action.result.data.status;

          //this.pnlGeneral.status.setValue(status);
          this.pnlGeneral.dsSysPrivilege.on('load', function() {
            this.pnlGeneral.cboSysPrivilege.setValue(sys_code);
            this.pnlGeneral.dsParentPrivilege.proxy.extraParams['sys_code'] = sys_code;
            this.pnlGeneral.dsParentPrivilege.load();
          }, this);
          this.pnlGeneral.dsSysPrivilege.load();
          this.pnlGeneral.dsParentPrivilege.on('load', function() {
            this.pnlGeneral.cboParentPrivilege.setValue(parent_id);
          }, this);
          
          Uums.privilege.PrivilegeDialog.superclass.show.call(this);
          
        },
        failure: function (form, action) {
          Ext.Msg.alert(UumsLanguage.msgErrTitle, action.result.feedback);
        },
        scope: this
      });
      
    } else {
      this.pnlGeneral.dsSysPrivilege.load();
      
      this.pnlGeneral.cboParentPrivilege.disable();

      Uums.privilege.PrivilegeDialog.superclass.show.call(this);
    }
    
  },
  
  buildForm: function(privilegeId) {
    this.pnlGeneral = Ext.create('Uums.privilege.GeneralPanel');
    
    var tabPrivilege = Ext.create('Ext.TabPanel', {
      activeTab: 0,
      border: false,
      deferredRender: false,
      items: [
        this.pnlGeneral
      ]
    });
    
    this.frmPrivilege = Ext.create('Ext.form.Panel', {
      border: false,
      fieldDefaults: {
        labelAlign: 'left',
        labelWidth: 80,
        labelSeparator: '',
        anchor: '98%'
      },
      url: Uums.privilege_request_url,
      method: 'POST',
      baseParams: {},
      items: tabPrivilege
    });
    
    return this.frmPrivilege; 
  },
    

  submitForm: function () {
    //get the privilege status
    var status = this.pnlGeneral.query('#statusEnable')[0].getGroupValue();
    
    //if the privilege is disabled, need to confirm whether the correlative products should be disabled too.
    if (status == 0) {
      var params = {'product_flag': 1};
      
      Ext.MessageBox.confirm(
        UumsLanguage.msgWarningTitle, 
        UumsLanguage.msgDisableProducts, 
        function (btn) {
          if (btn == 'no') {
            params.product_flag = 0;

            this.frmPrivilege.form.submit({
              params: params,
              waitMsg: UumsLanguage.formSubmitWaitMsg,
              success: function (form, action) {
                this.fireEvent('saveSuccess', action.result.feedback);
                this.close();
              },
              failure: function (form, action) {
                if (action.failureType != 'client') {
                  Ext.MessageBox.alert(UumsLanguage.msgErrTitle, action.result.feedback);
                }
              },
              scope: this
            });
          } else{
            this.frmPrivilege.form.submit({
              params: params,
              waitMsg: UumsLanguage.formSubmitWaitMsg,
              success: function (form, action) {
                this.fireEvent('saveSuccess', action.result.feedback, action.result.privilege_id, action.result.text);
                this.close();
              },
              failure: function (form, action) {
                if (action.failureType != 'client') {
                  Ext.MessageBox.alert(UumsLanguage.msgErrTitle, action.result.feedback);
                }
              },
              scope: this
            });
          }
        }, 
        this
      );       
    }else {
      this.frmPrivilege.form.submit({
        waitMsg: UumsLanguage.formSubmitWaitMsg,
        success: function (form, action) {
          this.fireEvent('saveSuccess', action.result.feedback, action.result.privilege_id, action.result.text);
          this.close();
        },
        failure: function (form, action) {
          if (action.failureType != 'client') {
            Ext.MessageBox.alert(UumsLanguage.msgErrTitle, action.result.feedback);
          }
        },
        scope: this
      });
    }
  }
});

/* End of file privilege_dialog.php */
/* Location: ./templates/base/web/views/privilege/privilege_dialog.php */