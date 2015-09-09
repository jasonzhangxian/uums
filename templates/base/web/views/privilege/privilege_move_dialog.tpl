
Ext.define('Uums.privilege.PrivilegeMoveDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'privilege-move-dialog-win';
    config.title = '移动权限';
    config.layout = 'fit';
    config.width = 400;
    config.autoHeight = true;
    config.modal = true;
    config.iconCls = 'icon-privilege-win';
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: UumsLanguage.btnSave,
        handler: function () {
          this.submitForm();
          
          this.disable();
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
  
  show: function (privilegeId) {
    var privilegeIds = [];
    
    if (Ext.isArray(privilegeId))
    {
      privilegeIds = privilegeId;
    }else {
      privilegeIds.push(privilegeId);
    }
    
    privilegeIds = Ext.JSON.encode(privilegeIds);
    
    this.frmPrivilege.form.baseParams['privilege_ids'] = privilegeIds;

    this.frmPrivilege.load({
      url : Uums.privilege.request_url,
      method: 'GET',
      success: function (form, action) {
        // get the parent id of the loading privilege.
        var parentId = action.result.data.parent_id;
          
        // if the format of the parent id is looked like '4', '2', they should be converted to int.
        if (Ext.isString(parentId) && (parentId.indexOf('_') == -1)) {
          parentId = parseInt(parentId);
        }
          
        //the store of the combox should not be load automatically so that we could confirm that all the data is loaded as calling the setValue.
        this.dsParentPrivilege.on('load', function() {
          this.cboParentPrivilege.setValue(parentId);
        }, this);
		this.dsParentPrivilege.proxy.extraParams['sys_code'] = action.result.data.sys_code;
        this.dsParentPrivilege.load();
      },
      failure: function (form, action) {
        Ext.Msg.alert(UumsLanguage.msgErrTitle, action.result.feedback);
      },
      scope: this  
    });

    this.callParent();
  },
  
  buildForm: function() {
    this.dsParentPrivilege = Ext.create('Ext.data.Store', {
      fields:[
        'privilege_id', 
        'privilege_name',
        'margin'
      ],
      pageSize: Uums.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Uums.privilege.request_url,
		extraParams : {action:'list'},
        reader: {
          type: 'json',
          root: Uums.CONF.JSON_READER_ROOT,
          totalProperty: Uums.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    this.cboParentPrivilege = Ext.create('Ext.form.ComboBox', {
      listConfig: {
        getInnerTpl: function() {
          return '<div style="margin-left: {margin}px">{privilege_name}</div>';
        }
      },
      fieldLabel: '所属权限',
      store: this.dsParentPrivilege,
      queryMode: 'local',
      valueField: 'privilege_id',
      displayField: 'privilege_name',
      name: 'parent_id',
      triggerAction: 'all'
    });
    
    this.frmPrivilege = Ext.create('Ext.form.Panel', {
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelAlign: 'top',
        labelWidth: 160,
        labelSeparator: '',
        anchor: '98%'
      },
      url : Uums.privilege.request_url,
      method: 'GET',
      baseParams: {
        action: 'move'
      },
      items: this.cboParentPrivilege
    });
    
    return this.frmPrivilege;
  },
  
  submitForm: function () {
    var parentId = this.cboParentPrivilege.getValue();
    
    this.frmPrivilege.form.submit({
      params: {'parent_id': parentId},
      waitMsg: UumsLanguage.formSubmitWaitMsg,
      success: function (form, action) {
        this.fireEvent('savesuccess', action.result.feedback);
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
});


/* End of file privilege_move_dialog.php */
/* Location: ./templates/base/web/views/privilege/privilege_move_dialog.php */