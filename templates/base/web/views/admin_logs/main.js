
Ext.namespace("Uums.admin_logs");

{{ include('admin_logs/admin_logs_grid.js')}}
{{ include('admin_logs/admin_logs_dialog.js')}}
{{ include('admin_logs/admin_logs_main_panel.js')}}



Ext.override(Uums.desktop.AdminLogsWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('admin_logs-win');

    if (!win) {                               
      this.pnl = Ext.create('Uums.admin_logs.mainPanel');
      
      this.pnl.on('createadmin_logs', this.onCreateAdmin_logs, this);
      this.pnl.on('editadmin_logs', this.onEditAdmin_logs, this);
      this.pnl.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'admin_logs-win',
        title: '日志列表',<!--此处需要改-->
        width: 600,
        height: 400,
        iconCls: 'icon-admin_logs-win',
        layout: 'fit',
        items: this.pnl
      });
    }   
    
    win.show();
  },
  
  onCreateAdmin_logs: function() {
    var dlg = this.createAdmin_logsDialog();
    
    dlg.setTitle('新建日志');
    dlg.show();
  },
  
  onEditAdmin_logs: function(rec) {
    var dlg = this.createAdmin_logsDialog();
    
    dlg.setTitle('编辑日志');
    dlg.show(rec.get('log_id'));
  },
    
  createAdmin_logsDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('admin_logs-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow({}, Uums.admin_logs.Admin_logsDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.grdAdmin_logs.onRefresh();
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
/* Location: ./templates/base/web/views/admin_logs/main.tpl */