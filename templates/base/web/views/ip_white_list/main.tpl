
Ext.namespace("Uums.ip_white_list");

{{ include('ip_white_list/ip_white_list_grid.tpl')}}
{{ include('ip_white_list/ip_white_list_dialog.tpl')}}
{{ include('ip_white_list/ip_white_list_main_panel.tpl')}}

Uums.ip_white_list.request_url = '/admin/ip_white_list/ip_white_list';
                           <!--需要改-->
Ext.override(Uums.desktop.IpWhiteListWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('ip_white_list-win');

    if (!win) {                               
      this.pnl = Ext.create('Uums.ip_white_list.mainPanel');
      
      this.pnl.on('createip_white_list', this.onCreateIp_white_list, this);
      this.pnl.on('editip_white_list', this.onEditIp_white_list, this);
      this.pnl.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'ip_white_list-win',
        title: 'IP列表',<!--此处需要改-->
        width: 400,
        height: 400,
        iconCls: 'icon-ip_white_list-win',
        layout: 'fit',
        items: this.pnl
      });
    }   
    
    win.show();
  },
  
  onCreateIp_white_list: function() {
    var dlg = this.createIp_white_listDialog();
    
    dlg.setTitle('新建IP');
    dlg.show();
  },
  
  onEditIp_white_list: function(rec) {
    var dlg = this.createIp_white_listDialog();
    
    dlg.setTitle('编辑IP');
    dlg.show(rec.get('id'));<!--ip_white_list_id-->
  },
    
  createIp_white_listDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('ip_white_list-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow({}, Uums.ip_white_list.Ip_white_listDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.grdIp_white_list.onRefresh();
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
/* Location: ./templates/base/web/views/ip_white_list/main.tpl */