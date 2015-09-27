
Ext.namespace("Uums.grade");

{{ include('grade/grade_tree.js')}}
{{ include('grade/grade_dialog.js')}}
{{ include('grade/grade_main_panel.js')}}

Ext.override(Uums.desktop.GradeWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('grade-win');

    if (!win) {                               
      this.pnl = Ext.create('Uums.grade.mainPanel');
      
      this.pnl.on('creategrade', this.onCreateGrade, this);
      this.pnl.on('editgrade', this.onEditGrade, this);
      this.pnl.on('deletegrade', this.onDeleteGrade, this);
      this.pnl.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'grade-win',
        title: '职位列表',
        width: 250,
        height: 400,
        iconCls: 'icon-grade-win',
        layout: 'fit',
        items: this.pnl
      });
    }   
    
    win.show();
  },
  
  onCreateGrade: function() {
    var dlg = this.createGradeDialog();
    
    dlg.setTitle('新建职位');
    dlg.show();
  },
  
  onEditGrade: function(grade_id) {
    if(grade_id == ''){
        Ext.MessageBox.alert(UumsLanguage.msgInfoTitle, UumsLanguage.msgMustSelectOne);
    }else{
      var dlg = this.createGradeDialog();
      dlg.setTitle('编辑职位');
      dlg.show(grade_id);
    }
  },

  onDeleteGrade: function(grade_id) {
    Ext.MessageBox.confirm(
      UumsLanguage.msgWarningTitle, 
      UumsLanguage.msgDeleteConfirm,
      function(btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            url : Uums.grade_request_url,
            method: 'DELETE',
            params: {
                grade_id: grade_id
            },
            callback: function(options, success, response) {
              var result = Ext.decode(response.responseText);
              
              if (result.success == true) {
                this.pnl.treeDepartment.refresh();
                this.app.showNotification({title: UumsLanguage.msgSuccessTitle, html: feedback});
              } else {
                Ext.MessageBox.alert(UumsLanguage.msgErrTitle, result.feedback);
              }
            }, 
            scope: this
          });
        }
      }, 
      this
    );
  },
  createGradeDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('grade-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow({}, Uums.grade.GradeDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.treeGrade.refresh();
        this.app.showNotification({title: UumsLanguage.msgSuccessTitle, html: feedback});
      }, this);
    }
    
    return dlg;    
  },
  

  
  onShowNotification: function(feedback) {
    this.app.showNotification( {title: UumsLanguage.msgSuccessTitle, html: feedback} );
  }
});

/* End of file main.tpl */
/* Location: ./templates/base/web/views/grade/main.tpl */