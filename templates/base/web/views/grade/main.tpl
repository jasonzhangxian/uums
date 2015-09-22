
Ext.namespace("Uums.grade");

{{ include('grade/grade_grid.tpl')}}
{{ include('grade/grade_dialog.tpl')}}
{{ include('grade/grade_main_panel.tpl')}}

Uums.grade.request_url = '/admin/grade/grade';
                           <!--需要改-->
Ext.override(Uums.desktop.GradeWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('grade-win');

    if (!win) {                               
      this.pnl = Ext.create('Uums.grade.mainPanel');
      
      this.pnl.on('creategrade', this.onCreateGrade, this);
      this.pnl.on('editgrade', this.onEditGrade, this);
      this.pnl.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'grade-win',
        title: '职位列表',<!--此处需要改-->
        width: 400,
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
  
  onEditGrade: function(rec) {
    var dlg = this.createGradeDialog();
    
    dlg.setTitle('编辑职位');
    dlg.show(rec.get('grade_id'));<!--grade_id-->
  },
    
  createGradeDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('grade-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow({}, Uums.grade.GradeDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.grdGrade.onRefresh();
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