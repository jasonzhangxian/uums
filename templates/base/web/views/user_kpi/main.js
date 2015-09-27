
Ext.namespace("Uums.user_kpi");

{{ include('user_kpi/user_kpi_grid.js')}}
{{ include('user_kpi/user_kpi_dialog.js')}}
{{ include('user_kpi/user_kpi_main_panel.js')}}
{{ include('user_kpi/user_kpi_upload_dialog.js')}}



Ext.override(Uums.desktop.UserKpiWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('user_kpi-win');

    if (!win) {                               
      this.pnl = Ext.create('Uums.user_kpi.mainPanel');
      
      this.pnl.on('createuser_kpi', this.onCreateUserKpi, this);
      this.pnl.on('edituser_kpi', this.onEditUserKpi, this);
      this.pnl.on('importuser_kpi', this.onImportUserKpi, this);
      this.pnl.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'user_kpi-win',
        title: '绩效考核信息',
        width: 800,
        height: 400,
        iconCls: 'icon-user_kpi-win',
        layout: 'fit',
        items: this.pnl
      });
    }   
    
    win.show();
  },
  
  onCreateUserKpi: function() {
    var dlg = this.createUserKpiDialog();
    
    dlg.setTitle('新建绩效信息');
    dlg.show();
  },
  
  onEditUserKpi: function(rec) {
    var dlg = this.createUserKpiDialog({'user_id':rec.get('user_id')});

    dlg.setTitle(rec.get('realname'));

    dlg.show(rec.get('user_id'));
  },
  onImportUserKpi: function() {
    var config = {};
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('user_kpi-upload-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow(config, Uums.user_kpi.UplaodDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.grdUserKpi.onRefresh();
        this.app.showNotification({title: UumsLanguage.msgSuccessTitle, html: feedback});
      }, this);
    }

    dlg.show();
  },
  createUserKpiDialog: function(config) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('user_kpi-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow(config, Uums.user_kpi.UserKpiDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.grdUserKpi.onRefresh();
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
/* Location: ./templates/base/web/views/user_kpi/main.tpl */