{% extends "layout.tpl" %}

{% block page_css%}
    <link rel="stylesheet" type="text/css" href="{{css_url}}login.css" />
{% endblock %}

{% block container %}
  <div id="x-login-panel">
    <img src="{{images_url}}s.gif" class="login-logo abs-position" />
    <div class="login-features abs-position">
      <p></p>
      <p align="justify"></p>
    </div>
    <img src="{{images_url}}s.gif" class="login-screenshot abs-position" />
    <div id="x-login-form" class="x-login-form abs-position"></div>
  </div>

  <script type="text/javascript">
    Ext.onReady(function(){
      Ext.BLANK_IMAGE_URL = '{{images_url}}s.gif';
      Ext.EventManager.onWindowResize(centerPanel);
      
      var loginPanel = Ext.get("x-login-panel");
      
      centerPanel();
      
      Ext.namespace("Uums");

      var txtUserName = null;
      var frmlogin = Ext.create('Ext.form.Panel', {
        bodyPadding: 5,
        width: 335,
        url: '{{base_url}}admin/login/process',
        layout: 'anchor',
        defaults: {
          anchor: '100%'
        },
        defaultType: 'textfield',
        items: [
          txtUserName = new Ext.form.TextField({
            name: 'user_name', 
            fieldLabel: '用户名', 
            labelSeparator: ' ', 
            allowBlank:false,
            listeners: {
              specialkey: function(field, e) {
                if (e.getKey() == e.ENTER) {
                  login();
                }
              }
            }
          }),
          {
            name: 'user_password', 
            fieldLabel: '密码', 
            inputType: 'password', 
            labelSeparator: ' ', 
            allowBlank:false, 
            listeners: {
              specialkey: function(field, e) {
                if (e.getKey() == e.ENTER) {
                  login();
                }
              }
            }
          }
        ],
        buttons: [{
          text: '登陆',
          handler: login, 
          scope: this
        }],
        listeners : {
          render: function() {txtUserName.focus(false, true);}
        },
        renderTo: 'x-login-form'
      });
      

      
      function centerPanel(){
        var xy = loginPanel.getAlignToXY(document, 'c-c');
        positionPanel(loginPanel, xy[0], xy[1]);
      }
      
      function login() {
        frmlogin.form.submit({
          success: function (form, action) {
            window.location = '{{base_url}}admin/index';
          },
          failure: function (form, action) {
            if (action.failureType != 'client') {
              Ext.Msg.alert('ms_error', action.result.error);
            }
          },
          scope: this
        });
      }
      
      function positionPanel(el, x, y){
        if(x && typeof x[1] == 'number') {
          y = x[1];
          x = x[0];
        }
        
        el.pageX = x;
        el.pageY = y;
        
        if(x === undefined || y === undefined){ // cannot translate undefined points
          return;
        }
        
        if(y < 0) { 
          y = 10;
        }
        
        var p = el.translatePoints(x, y);
        el.setLocation(p.left, p.top);
        
        return el;
      }
      
      function removeLoadMask() {
        var loading = Ext.get('x-loading-panel');
        var mask = Ext.get('x-loading-mask');
        loading.hide();
        mask.hide();
      }
      
      removeLoadMask(); 
    });  
    

  </script>
  {% endblock %}