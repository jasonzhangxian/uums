
Ext.namespace("Uums.new7_system");

{{ include('new7_system/new7_system_grid.tpl')}}
{{ include('new7_system/new7_system_dialog.tpl')}}
{{ include('new7_system/new7_system_main_panel.tpl')}}

Uums.new7_system.request_url = '/admin/new7_system/new7_system';
                           <!--需要改-->
Ext.override(Uums.desktop.New7SystemWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('new7_system-win');

    if (!win) {                               
      this.pnl = Ext.create('Uums.new7_system.mainPanel');
      
      this.pnl.on('createnew7_system', this.onCreateNew7_system, this);
      this.pnl.on('editnew7_system', this.onEditNew7_system, this);
      this.pnl.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'new7_system-win',
        title: '系统列表',<!--此处需要改-->
        width: 400,
        height: 400,
        iconCls: 'icon-new7_system-win',
        layout: 'fit',
        items: this.pnl
      });
    }   
    
    win.show();
  },
  
  onCreateNew7_system: function() {
    var dlg = this.createNew7_systemDialog();
    
    dlg.setTitle('新建系统');
    dlg.show();
  },
  
  onEditNew7_system: function(rec) {
    var dlg = this.createNew7_systemDialog();
    
    dlg.setTitle('编辑系统');
    dlg.show(rec.get('system_id'));<!--new7_system_id-->
  },
    
  createNew7_systemDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('new7_system-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow({}, Uums.new7_system.New7_systemDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.grdNew7_system.onRefresh();
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
/* Location: ./templates/base/web/views/new7_system/main.tpl */