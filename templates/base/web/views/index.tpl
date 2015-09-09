{% extends "layout.tpl" %}

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
      CONN_URL: '{{base_url}}admin/index',
      LOAD_URL: '{{base_url}}admin/index/load_module_view',
      PDF_URL: '',
      GRID_PAGE_SIZE : 12,
      GRID_STEPS : 5,
      JSON_READER_ROOT: 'records',
      JSON_READER_TOTAL_PROPERTY: 'total'
    };
    Uums.Languages = [];
    Uums.Languages.push({"id":"2","code":"zh_CN","country_iso":"cn","name":"Chinese Simplified","locale":"zh_CN.UTF-8,zh_CN,simplified chinese","charset":"utf-8","date_format_short":"%Y-%m-%d","date_format_long":"%Y \u5e74 %m \u6708 %d \u65e5 %A","time_format":"%H:%M:%S","text_direction":"ltr","currencies_id":"1","numeric_separator_decimal":".","numeric_separator_thousands":",","parent_id":"0"});
	Uums.Languages.push({"id":"1","code":"en_US","country_iso":"us","name":"English","locale":"en_US.UTF-8,en_US,english","charset":"utf-8","date_format_short":"%m\/%d\/%Y","date_format_long":"%A %d %B, %Y","time_format":"%H:%M:%S","text_direction":"ltr","currencies_id":"1","numeric_separator_decimal":".","numeric_separator_thousands":",","parent_id":"0"});  
    var UumsLanguage = {};
    UumsLanguage = {"tomatocartApp":"Tomatocart \u8d2d\u7269\u8f66\u7cfb\u7edf","Settings":"\u8bbe\u7f6e","Start":"\u5f00\u59cb","Logout":"\u9000\u51fa","btnSubmit":"\u63d0\u4ea4","btnSave":"\u4fdd\u5b58","btnClose":"\u5173\u95ed","btnSaveAndContinue":"\u4fdd\u5b58\u5e76\u7ee7\u7eed\u4fee\u6539","LoadWallpaper":"\u52a0\u8f7d\u5899\u7eb8\u4e2d ...","DesktopSetting":"\u684c\u9762\u8bbe\u7f6e","WallpaperSetting":"\u5899\u7eb8\u8bbe\u7f6e","ThemeSetting":"\u4e3b\u9898\u8bbe\u7f6e","ModulesSetting":"\u6a21\u5757\u8bbe\u7f6e","colModule":"\u6a21\u5757","colAutorun":"\u81ea\u52a8\u8fd0\u884c","colQuickstart":"\u5feb\u901f\u542f\u52a8","colShortcut":"\u5feb\u6377\u65b9\u5f0f","colContextmenu":"\u53f3\u952e\u83dc\u5355","btnSettings":"\u8bbe\u7f6e","btnSearch":"\u641c\u7d22","btnPrint":"\u6253\u5370","btnBack":"\u9000\u540e","btnAdd":"\u6dfb\u52a0","btnReset":"\u91cd\u7f6e","btnBatchAdd":"\u6279\u91cf\u6dfb\u52a0","btnAddFile":"\u65b0\u5efa\u6587\u4ef6","btnAddDir":"\u65b0\u5efa\u6587\u4ef6\u5939","btnEdit":"\u7f16\u8f91","btnRefresh":"\u5237\u65b0","btnDelete":"\u5220\u9664","btnUpload":"\u4e0a\u4f20","btnBackup":"\u5907\u4efd","btnMove":"\u79fb\u52a8","btnClear":"\u6e05\u9664","btnOk":"\u786e\u5b9a","btnActivate":"\u53d1\u5e03","btnDeactivate":"\u53d6\u6d88\u53d1\u5e03","beforePageText":"\u524d\u9875","firstText":"\u7b2c\u4e00\u9875","lastText":"\u6700\u540e\u4e00\u9875","nextText":"\u4e0b\u4e00\u9875","prevText":"\u4e0a\u4e00\u9875","afterPageText":"\u540e\u9875","refreshText":"\u5237\u65b0","displayMsg":"\u5f53\u524d {0} - {1} \u603b\u8ba1:{2}","emptyMsg":"\u6ca1\u6709\u6570\u636e\u3002","prevStepText":"\u4e0a\u4e00\u9875","nextStepText":"\u4e0b\u4e00\u9875","msgErrTitle":"\u9519\u8bef","msgWarningTitle":"\u8b66\u544a","msgInfoTitle":"\u4fe1\u606f","msgErrLoadData":"\u52a0\u8f7d\u8be5\u8bb0\u5f55\u65f6\u51fa\u9519\u3002","formSubmitWaitMsg":"\u8bf7\u8010\u5fc3\u7b49\u5f85","msgSuccessTitle":"\u6210\u529f","msgDeleteConfirm":"\u786e\u5b9a\u8981\u5220\u9664\u9009\u4e2d\u7684\u8bb0\u5f55\u5417 \uff1f","msgDisableProducts":"\u60a8\u8981\u7981\u7528\u8be5\u4ea7\u54c1\u7c7b\u522b\u4e0b\u6240\u6709\u7684\u4ea7\u54c1\u5417\uff1f","msgMustSelectOne":"\u8bf7\u81f3\u5c11\u9009\u62e9\u4e00\u6761\u8bb0\u5f55","msgSessionTimeout":"\u4f1a\u8bdd\u8d85\u65f6\uff01\u8bf7\u518d\u6b21\u767b\u5f55\u7cfb\u7edf\uff01","msgActiveConfirm":"\u786e\u5b9a\u8981\u6fc0\u6d3b\u9009\u4e2d\u8bb0\u5f55\u5417\uff1f","msgDeactiveConfirm":"\u786e\u5b9a\u8981\u53d6\u6d88\u6fc0\u6d3b\u9009\u4e2d\u7684\u8bb0\u5f55\u5417\uff1f","tipDelete":"\u5220\u9664","tipEdit":"\u7f16\u8f91","tipMove":"\u79fb\u52a8","tipRestore":"\u6062\u590d","tipDownload":"\u4e0b\u8f7d","tipExecute":"\u6267\u884c","gridNoRecords":"\u6ca1\u6709\u8bb0\u5f55\u53ef\u4ee5\u663e\u793a","availableTheme":"\u53ef\u7528\u4e3b\u9898","loadingText":"\u52a0\u8f7d...","noThemeText":"\u6ca1\u6709\u4e3b\u9898\u53ef\u4ee5\u663e\u793a\u3002","taskbarTransparency":"\u4fa7\u8fb9\u680f\u900f\u660e\u5ea6","defaultWallpapers":"\u9ed8\u8ba4\u5899\u7eb8","noWallpaperText":"\u65e0\u5899\u7eb8\u53ef\u7528\u3002","wallpaperPositionTitle":"\u5982\u4f55\u653e\u7f6e\u5899\u7eb8\uff1f","desktopBackgroundTitle":"\u9009\u62e9\u4e00\u79cd\u80cc\u666f\u8272","fontColorTitle":"\u9009\u62e9\u4e00\u79cd\u524d\u666f\u8272","btnBackgroundColor":"\u80cc\u666f\u8272","btnFontColor":"\u524d\u666f\u8272","saveDataMsg":"\u4fdd\u5b58\u6570\u636e\u4e2d\uff0c\u8bf7\u8010\u5fc3\u7b49\u5f85...","saveDataProgressText":"\u4fdd\u5b58\u4e2d...","saveDataSuccess":"\u8bbe\u7f6e\u5df2\u4fdd\u5b58!","connServerFailure":"\u670d\u52a1\u5668\u51fa\u9519\u3002","lostConnectionToServer":"\u65e0\u6cd5\u8fde\u63a5\u670d\u52a1\u5668","sidebarNoGadgets":"\u6ca1\u6709\u7ec4\u4ef6\u663e\u793a\uff01","sidebarGadgetsTitle":"\u7ec4\u4ef6","sidebarConfigTitle":"\u4fa7\u8fb9\u680f\u8bbe\u7f6e","sidebarSettingTitle":"\u4fa7\u8fb9\u680f\u8bbe\u7f6e","sidebarStateSettingTitle":"\u663e\u793a\u4fa7\u8fb9\u680f \uff1f","sidebarTransparencySettingTitle":"\u4fa7\u8fb9\u680f\u900f\u660e\u5ea6","sidebarChoseGroundColor":"\u9009\u62e9\u5de5\u5177\u680f\u80cc\u666f\u8272"};
    
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