Ext.define('Uums.department.DepartmentDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'department-dialog-win';
    config.title = '新建部门';<!--此处需要改-->
    config.modal = true;
    config.width = 350;
    config.iconCls = 'icon-department-win';
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
    var departmentId = id || null;
    
    this.frmDepartment.form.reset();
    this.frmDepartment.form.baseParams['department_id'] = departmentId;<!--修改-->
    
    if (departmentId > 0) {
      this.frmDepartment.load({
        url: Uums.department.request_url,
	method: 'GET',
        success: function(form, action) {
          Uums.department.DepartmentDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(UumsLanguage.msgErrTitle, UumsLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      Uums.department.DepartmentDialog.superclass.show.call(this);
    }
  },
  
  buildForm: function() {
     
    this.frmDepartment = Ext.create('Ext.form.FormPanel', {
      url: Uums.department.request_url,
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
        {xtype: 'textfield', fieldLabel: '部门名称', name: 'department_name', allowBlank: false},<!--此处需要改-->
        {xtype: 'textfield', fieldLabel: '部门水平', name: 'departmnt_level', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '等级编号', name: 'parent_id', allowBlank: false},
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
    
    return this.frmDepartment;
  },
  
  submitForm : function() {
    this.frmDepartment.form.submit({
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

/* End of file department_dialog.tpl */
/* Location: ./templates/base/web/views/department/department_dialog.tpl */