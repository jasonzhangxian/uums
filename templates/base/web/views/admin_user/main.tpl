
Ext.namespace("Uums.admin_user");

{{ include('admin_user/admin_user_grid.tpl')}}
{{ include('admin_user/admin_user_dialog.tpl')}}
{{ include('admin_user/admin_user_main_panel.tpl')}}

Uums.admin_user.request_url = '/admin/admin_user/admin_user';

Ext.override(Uums.desktop.AdminUserWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('admin_user-win');

    if (!win) {                               
      this.pnl = Ext.create('Uums.admin_user.mainPanel');
      
      this.pnl.on('createadmin_user', this.onCreateAdminUser, this);
      this.pnl.on('editadmin_user', this.onEditAdminUser, this);
      this.pnl.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'admin_user-win',
        title: '用户列表',
        width: 760,
        height: 400,
        iconCls: 'icon-admin_user-win',
        layout: 'fit',
        items: this.pnl
      });
    }   
    
    win.show();
  },
  
  onCreateAdminUser: function() {
    var dlg = this.createAdminUserDialog();
    
    dlg.setTitle('新建用户');
    dlg.show();
  },
  
  onEditAdminUser: function(rec) {
    var dlg = this.createAdminUserDialog({'user_id':rec.get('user_id')});

    dlg.setTitle(rec.get('realname'));
    
    dlg.pnlQuarters.getStore().on('load', function() {dlg.pnlQuarters.expandAll();}, this);

    dlg.show(rec.get('user_id'));
  },
    
  createAdminUserDialog: function(config) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('admin_user-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow(config, Uums.admin_user.AdminUserDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.grdAdminUser.onRefresh();
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
/* Location: ./templates/base/web/views/admin_user/main.tpl */