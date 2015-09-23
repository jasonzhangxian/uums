
Ext.namespace("Uums.department");

{{ include('department/department_tree.tpl')}}
{{ include('department/department_dialog.tpl')}}
{{ include('department/department_main_panel.tpl')}}

Uums.department.request_url = '/admin/department/department';

Ext.override(Uums.desktop.DepartmentWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('department-win');

    if (!win) {                               
      this.pnl = Ext.create('Uums.department.mainPanel');
      
      this.pnl.on('createdepartment', this.onCreateDepartment, this);
      this.pnl.on('editdepartment', this.onEditDepartment, this);
      this.pnl.on('deletedepartment', this.onDeleteDepartment, this);
      this.pnl.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'department-win',
        title: '部门列表',
        width: 250,
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
  
  onEditDepartment: function(department_id) {
    if(department_id == ''){
        Ext.MessageBox.alert(UumsLanguage.msgInfoTitle, UumsLanguage.msgMustSelectOne);
    }else{
      var dlg = this.createDepartmentDialog();
      dlg.setTitle('编辑部门');
      dlg.show(department_id);
    }
  },
  onDeleteDepartment: function(department_id) {
    Ext.MessageBox.confirm(
      UumsLanguage.msgWarningTitle, 
      UumsLanguage.msgDeleteConfirm,
      function(btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            url : Uums.department.request_url,
            method: 'DELETE',
            params: {
                department_id: department_id
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
  createDepartmentDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('department-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow({}, Uums.department.DepartmentDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.treeDepartment.refresh();
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