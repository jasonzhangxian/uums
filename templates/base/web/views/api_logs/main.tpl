
Ext.namespace("Uums.api_logs");

{{ include('api_logs/api_logs_grid.tpl')}}
{{ include('api_logs/api_logs_dialog.tpl')}}
{{ include('api_logs/api_logs_main_panel.tpl')}}

Uums.api_logs.request_url = '/admin/api_logs/api_logs';
                           <!--需要改-->
Ext.override(Uums.desktop.ApiLogsWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('api_logs-win');

    if (!win) {                               
      this.pnl = Ext.create('Uums.api_logs.mainPanel');
      
      this.pnl.on('createapi_logs', this.onCreateApi_logs, this);
      this.pnl.on('editapi_logs', this.onEditApi_logs, this);
      this.pnl.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'api_logs-win',
        title: '日志列表',<!--此处需要改-->
        width: 600,
        height: 400,
        iconCls: 'icon-api_logs-win',
        layout: 'fit',
        items: this.pnl
      });
    }   
    
    win.show();
  },
  
  onCreateApi_logs: function() {
    var dlg = this.createApi_logsDialog();
    
    dlg.setTitle('新建日志');
    dlg.show();
  },
  
  onEditApi_logs: function(rec) {
    var dlg = this.createApi_logsDialog();
    
    dlg.setTitle('编辑日志');
    dlg.show(rec.get('lid'));<!--api_logs_id-->
  },
    
  createApi_logsDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('api_logs-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow({}, Uums.api_logs.Api_logsDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.grdApi_logs.onRefresh();
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
/* Location: ./templates/base/web/views/api_logs/main.tpl */