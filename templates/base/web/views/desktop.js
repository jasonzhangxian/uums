
/*
 * Desktop configuration
 */

Ext.onReady(function () {
  var UumsDesktop;
  
  UumsDesktop = new Uums.desktop.App({
    startConfig: {
      title : "{{realname}}"
    },
    
    /**
     * Return modules.
     */
    getModules: function() {
      return {{modules | raw}};
    },
    
    /**
     * Return the launchers object.
     */
    getLaunchers : function(){
      return {{launchers | raw}};
    },
    
    /**
     * Return the Styles object.
     */
    getStyles : function(){
      return {{styles | raw}};
    },
    
    onLogout: function() {
      Ext.Ajax.request({
        url: Uums.CONF.CONN_URL,
        params: {
          action: 'logoff',
        },
        callback: function(options, success, response) {
          result = Ext.decode(response.responseText);
          
          if (result.success == true) {
            window.location = "{{ site_url("admin/login") }}";
          }
        }
      });
    },
    
    onSettings: function() {
      var winSetting = this.getDesktopSettingWindow();
      
      winSetting.show();
    }
  });
});
{{output | raw}}