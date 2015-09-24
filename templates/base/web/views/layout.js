<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <title>统一用户管理系统</title>
    <link rel="shortcut icon" href="{{images_url}}favicon.ico" type="image/x-icon" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="-1" />
    <meta name="generator" content="统一用户管理系统 -- 新七天管理平台" />    
	<link rel="stylesheet" type="text/css" href="{{js_url}}extjs/resources/css/ext-all.css" />
	{% block page_css %}
	{% endblock %}
	<script src="{{js_url}}extjs/ext-all-debug.js"></script> 
  </head>

  <body scroll="no">

  <div id="x-loading-mask" style="width:100%; height:100%; background:#000000; position:absolute; z-index:20000; left:0; top:0;">&#160;</div>
  <div id="x-loading-panel" style="position:absolute;left:40%;top:40%;border:1px solid #9c9f9d;padding:2px;background:#d1d8db;width:300px;text-align:center;z-index:20001;">
    <div class="x-loading-panel-mask-indicator" style="border:1px solid #c1d1d6;color:#666;background:white;padding:10px;margin:0;padding-left: 20px;height:130px;text-align:left;">
      <img class="x-loading-panel-logo" style="display:block;margin-bottom:15px;" src="{{images_url}}tomatocart.jpg" />
      <img src="{{images_url}}loading.gif" style="width:16px;height:16px;vertical-align:middle" />&#160;
      <span id="load-status">加载中</span>
      <div style="font-size:10px; font-weight:normal; margin-top:15px;">Copyright &copy; 2015 New7</div>
    </div>
  </div> 
{% block container %}
{% endblock %}