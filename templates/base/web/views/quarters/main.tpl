
Ext.namespace("Uums.quarters");

{{ include('quarters/quarters_grid.tpl')}}
{{ include('quarters/quarters_dialog.tpl')}}
{{ include('quarters/quarters_main_panel.tpl')}}

Uums.quarters.request_url = '/admin/quarters/quarters';

Ext.override(Uums.desktop.QuartersWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('quarters-win');

    if (!win) {                               
      this.pnl = Ext.create('Uums.quarters.mainPanel');
      
      this.pnl.on('createquarters', this.onCreateQuarters, this);
      this.pnl.on('editquarters', this.onEditQuarters, this);
      this.pnl.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'quarters-win',
        title: '岗位列表',
        width: 560,
        height: 400,
        iconCls: 'icon-quarters-win',
        layout: 'fit',
        items: this.pnl
      });
    }   
    
    win.show();
  },
  
  onCreateQuarters: function() {
    var dlg = this.createQuartersDialog();
    
    dlg.setTitle('新建岗位');
    dlg.show();
  },
  
  onEditQuarters: function(rec) {
    var dlg = this.createQuartersDialog();
    
    dlg.setTitle('编辑岗位');
    dlg.show(rec.get('quarters_id'));
  },
    
  createQuartersDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('quarters-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow({}, Uums.quarters.QuartersDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.grdQuarters.onRefresh();
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
/* Location: ./templates/base/web/views/quarters/main.tpl */