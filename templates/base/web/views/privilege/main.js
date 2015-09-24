
  Ext.namespace("Uums.privilege");


 {{ include( 'privilege/privilege_grid.js' ) }};
 {{ include( 'privilege/privilege_dialog.js' ) }};
 {{ include( 'privilege/privilege_main_panel.js' ) }};
 {{ include( 'privilege/privilege_general_panel.js' ) }};
 {{ include( 'privilege/privilege_move_dialog.js' ) }};
 {{ include( 'privilege/privilege_tree_panel.js' ) }};


Ext.override(Uums.desktop.PrivilegeWindow, {
  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('privilege-win');
     
    if (!win) {
      var pnlPrivilege = Ext.create('Uums.privilege.mainPanel');
      
      this.attachEventsToPrivilegeGrd(pnlPrivilege);
      
      win = desktop.createWindow({
        id: 'privilege-win',
        title: '权限管理',
        width: 930,
        height: 400,
        iconCls: 'icon-privilege-win',
        layout: 'fit',
        items: pnlPrivilege
      });
    }
    
    win.show();
  },
  
  onCreatePrivilege: function(pnlPrivilegeTree, privilegeId) {
    var dlg = this.createPrivilegeDialog(privilegeId);
    
    this.onSaveSuccess(dlg, pnlPrivilegeTree);
    
    dlg.show();
  },
  
  onEditPrivilege: function(pnlPrivilegeTree, record) {
    var dlg = this.createPrivilegeDialog();
    dlg.setTitle(record.get('privilege_name'));
    
    this.onSaveSuccess(dlg, pnlPrivilegeTree);
    
    dlg.show(record.get('privilege_id'));
  },
  
  onMovePrivilege: function(pnlPrivilegeTree, record) {
    var dlg = this.createPrivilegeMoveDialog();
    
    dlg.setTitle('移动权限');
    
    this.onSaveSuccess(dlg, pnlPrivilegeTree);
    
    dlg.show(record.get('privilege_id'));
  },
  
  onBatchMovePrivilege: function(pnlPrivilegeTree, privilegeIds) {
    var dlg = this.createPrivilegeMoveDialog();
    
    dlg.setTitle('移动权限');
    
    this.onSaveSuccess(dlg, pnlPrivilegeTree);
    
    dlg.show(privilegeIds);
  },
  
  onSaveSuccess: function(dlg, pnlPrivilegeTree) {
    dlg.on('savesuccess', function() {
      pnlPrivilegeTree.refresh();
    }, this);
  },
  
  onDeletePrivilegeSuccess: function (pnlPrivilegeTree, feedback) {
    this.onShowNotification(feedback);
    pnlPrivilegeTree.refresh();
  },
  
  onShowNotification: function (feedback) {
    this.app.showNotification({
      title: UumsLanguage.msgSuccessTitle,
      html: feedback
    });
  },
  
  createPrivilegeDialog: function(privilegeId) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('privilege-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({privilegeId: privilegeId}, Uums.privilege.PrivilegeDialog);
      
      dlg.on('savesuccess', this.onShowNotification, this);
    }

    return dlg;
  },
  
  createPrivilegeMoveDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('privilege-move-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Uums.privilege.PrivilegeMoveDialog);
      
      dlg.on('savesuccess', this.onShowNotification, this);
    }
    
    return dlg;
  },
  
  attachEventsToPrivilegeGrd: function(pnlPrivilege) {
    pnlPrivilege.grdPrivilege.on('deletesuccess', function(feedback) {
      this.onDeletePrivilegeSuccess(pnlPrivilege.pnlPrivilegeTree, feedback);
    }, this);
    
    pnlPrivilege.grdPrivilege.on('create', function(privilegeId) {
      this.onCreatePrivilege(pnlPrivilege.pnlPrivilegeTree, privilegeId);
    }, this);
    
    pnlPrivilege.grdPrivilege.on('edit', function(rec) {
      this.onEditPrivilege(pnlPrivilege.pnlPrivilegeTree, rec);
    }, this);
    
    pnlPrivilege.grdPrivilege.on('moveprivilege', function(rec) {
      this.onMovePrivilege(pnlPrivilege.pnlPrivilegeTree, rec);
    }, this);
    
    pnlPrivilege.grdPrivilege.on('batchmoveprivilege', function(privilegeIds) {
      this.onBatchMovePrivilege(pnlPrivilege.pnlPrivilegeTree, privilegeIds);
    }, this);
    
    pnlPrivilege.grdPrivilege.on('notifysuccess', this.onShowNotification, this);
  }
});
