
Ext.namespace("Uums.department");

{{ include('department/department_grid.tpl')}}
{{ include('department/department_dialog.tpl')}}
{{ include('department/department_main_panel.tpl')}}

Uums.department.request_url = '/admin/department/department';
                           <!--需要改-->
Ext.override(Uums.desktop.DepartmentWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('department-win');

    if (!win) {                               
      this.pnl = Ext.create('Uums.department.mainPanel');
      
      this.pnl.on('createdepartment', this.onCreateDepartment, this);
      this.pnl.on('editdepartment', this.onEditDepartment, this);
      this.pnl.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'department-win',
        title: '部门列表',<!--此处需要改-->
        width: 400,
        height: 400,
        iconCls: 'icon-department-win',
        layout: 'fit',
        items: this.pnl
      });
    }   
    
    win.show();
  },
  
  onCreateDepartment: function() {
    var dlg = this.createDepartmentDialog();
    
    dlg.setTitle('新建部门');
    dlg.show();
  },
  
  onEditDepartment: function(rec) {
    var dlg = this.createDepartmentDialog();
    
    dlg.setTitle('编辑部门');
    dlg.show(rec.get('department_id'));<!--department_id-->
  },
    
  createDepartmentDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('department-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow({}, Uums.department.DepartmentDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.grdDepartment.onRefresh();
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
/* Location: ./templates/base/web/views/department/main.tpl */