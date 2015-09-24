{% extends "layout.js" %}

{% block page_css%}
  <link rel="stylesheet" type="text/css" href="{{css_url}}desktop.css" />
	<link rel="stylesheet" type="text/css" href="{{css_url}}action.css" />
	<link rel="stylesheet" type="text/css" href="{{css_url}}icon.css" />
	<link rel="stylesheet" type="text/css" href="{{css_url}}icons-shortcuts.css" />
{% endblock %}

{% block container %}
  <script type="text/javascript">
    Ext.namespace("Uums");
    
    Uums.CONF = {
      TEMPLATE: 'default',
      CONN_URL: '{{ site_url('/admin/index') }}',
      LOAD_URL: '{{ site_url('/admin/index/load_module_view') }}',
      PDF_URL: '',
      GRID_PAGE_SIZE : 12,
      GRID_STEPS : 5,
      JSON_READER_ROOT: 'records',
      JSON_READER_TOTAL_PROPERTY: 'total'
    };
    Uums.admin_logs_request_url = '{{ site_url('/admin/admin_logs/admin_logs') }}';
    Uums.admin_user_request_url = '{{ site_url('/admin/admin_user/admin_user') }}';
    Uums.api_list_request_url = '{{ site_url('/admin/api_list/api_list') }}';
    Uums.api_logs_request_url = '{{ site_url('/admin/api_logs/api_logs') }}';
    Uums.ip_white_list_request_url = '{{ site_url('/admin/ip_white_list/ip_white_list') }}';
    Uums.grade_request_url = '{{ site_url('/admin/grade/grade') }}';
    Uums.department_request_url = '{{ site_url('/admin/department/department') }}';
    Uums.new7_system_request_url = '{{ site_url('/admin/new7_system/new7_system') }}';
    Uums.privilege_request_url = '{{ site_url('/admin/privilege/privilege') }}';
    Uums.quarters_request_url = '{{ site_url('/admin/quarters/quarters') }}';
    Uums.Languages = []; 
    var UumsLanguage = {};
    UumsLanguage = {DesktopSetting: "桌面设置",
                    LoadWallpaper: "加载墙纸中 ...",
                    Logout: "退出",
                    ModulesSetting: "模块设置",
                    Settings: "设置",
                    Start: "开始",
                    ThemeSetting: "主题设置",
                    WallpaperSetting: "墙纸设置",
                    afterPageText: "后页",
                    availableTheme: "可用主题",
                    beforePageText: "前页",
                    btnActivate: "发布",
                    btnExport: "导出",
                    btnAdd: "添加",
                    btnAddDir: "新建文件夹",
                    btnAddFile: "新建文件",
                    btnBack: "退后",
                    btnBackgroundColor: "背景色",
                    btnBackup: "备份",
                    btnBatchAdd: "批量添加",
                    btnClear: "清除",
                    btnClose: "关闭",
                    btnDeactivate: "取消发布",
                    btnDelete: "删除",
                    btnEdit: "编辑",
                    btnFontColor: "前景色",
                    btnMove: "移动",
                    btnOk: "确定",
                    btnPrint: "打印",
                    btnRefresh: "刷新",
                    btnReset: "重置",
                    btnSave: "保存",
                    btnSaveAndContinue: "保存并继续修改",
                    btnSearch: "搜索",
                    btnSettings: "设置",
                    btnSubmit: "提交",
                    btnUpload: "上传",
                    colAutorun: "自动运行",
                    colContextmenu: "右键菜单",
                    colModule: "模块",
                    colQuickstart: "快速启动",
                    colShortcut: "快捷方式",
                    connServerFailure: "服务器出错。",
                    defaultWallpapers: "默认墙纸",
                    desktopBackgroundTitle: "选择一种背景色",
                    displayMsg: "当前 {0} - {1} 总计:{2}",
                    emptyMsg: "没有数据。",
                    firstText: "第一页",
                    fontColorTitle: "选择一种前景色",
                    formSubmitWaitMsg: "请耐心等待",
                    gridNoRecords: "没有记录可以显示",
                    lastText: "最后一页",
                    loadingText: "加载...",
                    lostConnectionToServer: "无法连接服务器",
                    msgActiveConfirm: "确定要激活选中记录吗？",
                    msgDeactiveConfirm: "确定要取消激活选中的记录吗？",
                    msgDeleteConfirm: "确定要删除选中的记录吗 ？",
                    msgDisableProducts: "您要禁用该权限类别下所有的权限吗？",
                    msgErrLoadData: "加载该记录时出错。",
                    msgErrTitle: "错误",
                    msgInfoTitle: "信息",
                    msgMustSelectOne: "请至少选择一条记录",
                    msgSessionTimeout: "会话超时！请再次登录系统！",
                    msgSuccessTitle: "成功",
                    msgWarningTitle: "警告",
                    nextStepText: "下一页",
                    nextText: "下一页",
                    noThemeText: "没有主题可以显示。",
                    noWallpaperText: "无墙纸可用。",
                    prevStepText: "上一页",
                    prevText: "上一页",
                    refreshText: "刷新",
                    saveDataMsg: "保存数据中，请耐心等待...",
                    saveDataProgressText: "保存中...",
                    saveDataSuccess: "设置已保存!",
                    sidebarChoseGroundColor: "选择工具栏背景色",
                    sidebarConfigTitle: "侧边栏设置",
                    sidebarGadgetsTitle: "组件",
                    sidebarNoGadgets: "没有组件显示！",
                    sidebarSettingTitle: "侧边栏设置",
                    sidebarStateSettingTitle: "显示侧边栏 ？",
                    sidebarTransparencySettingTitle: "侧边栏透明度",
                    taskbarTransparency: "侧边栏透明度",
                    tipDelete: "删除",
                    tipDownload: "下载",
                    tipEdit: "编辑",
                    tipExecute: "执行",
                    tipMove: "移动",
                    tipRestore: "恢复",
                    tomatocartApp: "统一用户管理系统",
                    wallpaperPositionTitle: "如何放置墙纸？"
                    };

    Ext.BLANK_IMAGE_URL = '{{images_url}}s.gif';
  </script>

  <!-- Uums DESKTOP JS LIBRARY -->
  <script type="text/javascript" src="{{js_url}}desktop/core/classes.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/core/UumsApp.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/core/UumsModule.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/core/UumsDesktop.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/settings/backgrounds.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/settings/modules.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/settings/settings.js"></script>
  
  <!-- Uums EXTENSION JS LIBRARY -->
  <script type="text/javascript" src="{{js_url}}desktop/ux/Format.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/ux/ColorPicker.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/ux/CheckColumn.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/ux/MultiSelect.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/ux/layout/component/form/MultiSelect.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/ux/portal/PortalPanel.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/ux/portal/Portlet.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/ux/portal/PortalDropZone.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/ux/portal/PortalColumn.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/ux/portal/GridPortlet.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/ux/RowExpander.js"></script>
  <script type="text/javascript" src="{{js_url}}desktop/ux/notification.js"></script>
  
  <!-- GNERATING Uums DESKTOP -->
  <script type="text/javascript" src="{{base_url}}admin/index/desktop"></script>
  {% endblock %}