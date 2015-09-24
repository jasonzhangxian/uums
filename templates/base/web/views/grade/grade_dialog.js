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
        url: Uums.grade_request_url,
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
      url: Uums.grade_request_url,
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
        {
            id: "parent_name",
            xtype: "uums_grade_editor",
            parentItem: this,
            fieldLabel: "职级",
            editable: false,
            width: 300,
            allowBlank: false
        },
        {id: "parent_id", xtype: "hidden", name: "parent_id", value: 0},
        {xtype: 'textfield', fieldLabel: '职位名称', name: 'grade_name', allowBlank: false},
        {xtype: 'numberfield', fieldLabel: '职位水平', name: 'grade_level', allowBlank: false,value: 0},
        {xtype: 'numberfield', fieldLabel: '排序', name: 'order', allowBlank: false, value: 0}
      ]
    });
    
    return this.frmGrade;
  },
  
  setGrade: function (data) {
        var parent_name = Ext.getCmp("parent_name");
        parent_name.setValue(data.text);
        var parent_id = Ext.getCmp("parent_id");
        parent_id.setValue(data.id);
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