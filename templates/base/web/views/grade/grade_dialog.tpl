Ext.define('Uums.grade.GradeDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'grade-dialog-win';
    config.title = '新建职位';<!--此处需要改-->
    config.modal = true;
    config.width = 350;
    config.iconCls = 'icon-grade-win';
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
    var gradeId = id || null;
    
    this.frmGrade.form.reset();
    this.frmGrade.form.baseParams['grade_id'] = gradeId;<!--修改-->
    
    if (gradeId > 0) {
      this.frmGrade.load({
        url: Uums.grade.request_url,
	method: 'GET',
        success: function(form, action) {
          Uums.grade.GradeDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(UumsLanguage.msgErrTitle, UumsLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      Uums.grade.GradeDialog.superclass.show.call(this);
    }
  },
  
  buildForm: function() {
     
    this.frmGrade = Ext.create('Ext.form.FormPanel', {
      url: Uums.grade.request_url,
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
        {xtype: 'textfield', fieldLabel: '职位名称', name: 'grade_name', allowBlank: false},<!--此处需要改-->
        {xtype: 'textfield', fieldLabel: '部门编号', name: 'department_id', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '职位水平', name: 'grade_level', allowBlank: false},
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
    
    return this.frmGrade;
  },
  
  submitForm : function() {
    this.frmGrade.form.submit({
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

/* End of file grade_dialog.tpl */
/* Location: ./templates/base/web/views/grade/grade_dialog.tpl */