
Ext.define('Uums.privilege.GeneralPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '基本信息';
    config.border = false;
    config.bodyPadding = 8;
    config.layout = 'anchor';
    
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var items = [];
    
    this.dsSysPrivilege = Ext.create('Ext.data.Store', {
      fields:[
        'sys_code', 
        'system_name',
        'margin'
      ],
      proxy: {
        type: 'ajax',
        url : '{{ site_url('admin/new7_system/new7_system') }}',
        reader: {
          type: 'json',
          root: Uums.CONF.JSON_READER_ROOT,
          totalProperty: Uums.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    this.cboSysPrivilege = Ext.create('Ext.form.ComboBox', {
      listConfig: {
        getInnerTpl: function() {
          return '<div style="margin-left: 0px">{system_name}</div>';
        }
      },
      fieldLabel: '所属系统',
      store: this.dsSysPrivilege,
      queryMode: 'local',
      valueField: 'sys_code',
      displayField: 'system_name',
      name: 'sys_code',
      triggerAction: 'all',
      listeners :{
        select: this.onCboSysPrivilegeSelect,
        scope: this
      } 
    });
    
    items.push(this.cboSysPrivilege);
    this.dsParentPrivilege = Ext.create('Ext.data.Store', {
      fields:[
        'privilege_id', 
        'privilege_name',
        'margin'
      ],
      proxy: {
        type: 'ajax',
        url : Uums.privilege.request_url,
        extraParams : {action:'folder'},
        reader: {
          type: 'json',
          root: Uums.CONF.JSON_READER_ROOT,
          totalProperty: Uums.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    this.cboParentPrivilege = Ext.create('Ext.form.ComboBox', {
      listConfig: {
        getInnerTpl: function() {
          return '<div style="margin-left: {margin}px">{privilege_name}</div>';
        }
      },
      fieldLabel: '所属上级',
      store: this.dsParentPrivilege,
      queryMode: 'local',
      valueField: 'privilege_id',
      displayField: 'privilege_name',
      name: 'parent_id',
      triggerAction: 'all'
    });
    items.push(this.cboParentPrivilege);

    items.push({xtype: 'textfield', fieldLabel: '权限名称', name: 'privilege_name', allowBlank: false});
    items.push({xtype: 'textfield', fieldLabel: '权限编码', name: 'privilege_code', allowBlank: false});
    items.push({xtype: 'panel', name: 'privilege_image', id: 'privilege_image_panel', border: false});
    
    items.push({
      layout: 'column',
      border: false,
      items:[{
        id: 'status',
        border: false,
        style: "padding-right: 5px;",
        items:[{fieldLabel: '是否启用', xtype:'radio', id: 'statusEnable', name: 'status', boxLabel: '已启用', inputValue: '1', checked: true}]
      },{
        border: false,
        items: [{fieldLabel: '未启用', boxLabel: '未启用', xtype:'radio', name: 'status', hideLabel: true, inputValue: '0'}]
      }]
    });
    
    items.push({
      layout: 'column',
      border: false,
      items:[{
        id: 'type',
        border: false,
        style: "padding-right: 5px;",
        items:[{fieldLabel: '类型', xtype:'radio', name: 'type', boxLabel: '未定义', inputValue: '0'}]
      },{
        border: false,
        style: "padding-right: 5px;",
        items: [{fieldLabel: '菜单', boxLabel: '菜单', xtype:'radio', name: 'type', hideLabel: true, inputValue: '1', checked: true}]
      },{
        border: false,
        style: "padding-right: 5px;",
        items: [{fieldLabel: '按钮', boxLabel: '按钮', xtype:'radio', name: 'type', hideLabel: true, inputValue: '2'}]
      }]
    });
    items.push({xtype: 'numberfield', fieldLabel: '排序', name: 'sort_order', value: 0});
    
    return items;
  },
  
  onCboSysPrivilegeSelect: function(combo, value) {
    this.cboParentPrivilege.enable();
    this.cboParentPrivilege.reset();
    this.dsParentPrivilege.proxy.extraParams['sys_code'] = value[0].get('sys_code');
    this.dsParentPrivilege.load();
  }
});

/* End of file privilege_general_panel.php */
/* Location: ./templates/base/web/views/privilege/privilege_general_panel.php */