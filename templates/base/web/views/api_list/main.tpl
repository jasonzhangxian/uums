
Ext.namespace("Uums.api_list");

{{ include('api_list/api_list_grid.tpl')}}
{{ include('api_list/api_list_dialog.tpl')}}
{{ include('api_list/api_list_main_panel.tpl')}}

Uums.api_list.request_url = '/admin/api_list/api_list';
                           <!--需要改-->
Ext.override(Uums.desktop.ApiListWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('api_list-win');

    if (!win) {                               
      this.pnl = Ext.create('Uums.api_list.mainPanel');
      
      this.pnl.on('createapi_list', this.onCreateApi_list, this);
      this.pnl.on('editapi_list', this.onEditApi_list, this);
      this.pnl.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'api_list-win',
        title: '接口列表',<!--此处需要改-->
        width: 400,
        height: 400,
        iconCls: 'icon-api_list-win',
        layout: 'fit',
        items: this.pnl
      });
    }   
    
    win.show();
  },
  
  onCreateApi_list: function() {
    var dlg = this.createApi_listDialog();
    
    dlg.setTitle('新建接口');
    dlg.show();
  },
  
  onEditApi_list: function(rec) {
    var dlg = this.createApi_listDialog();
    
    dlg.setTitle('编辑接口');
    dlg.show(rec.get('id'));<!--api_list_id-->
  },
    
  createApi_listDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('api_list-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow({}, Uums.api_list.Api_listDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.grdApi_list.onRefresh();
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
/* Location: ./templates/base/web/views/api_list/main.tpl */