<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entry extends Api_Controller {

	private $_api;
	private $_sys_code;
	private $_timestamp;
	private $_sign;

    function __construct()
    {
        parent::__construct();
		$this->load->model('ip_white_list_model','ip_white_list');
		$this->load->model('new7_system_model','new7_system');
		$this->load->model('api_list_model','api_list');
		$this->load->model('api_logs_model','api_logs');
		$this->load->library(array('check','result'));
		$this->_api = $this->input->get('api');
		$this->_sys_code = $this->input->get('sys_code');
		$this->_timestamp = $this->input->get('timestamp');
		$this->_sign = $this->input->get('sign');
    }

	/**
	 * API访问唯一入口
	 */
	public function index()
	{
		//验证访问者IP
		$this->check_ip();
		//去前后空格
		$this->_api = trim($this->_api);
		$this->_sys_code = trim($this->_sys_code);
		$this->_timestamp = trim($this->_timestamp);
		$this->_sign = trim($this->_sign);
		//系统级参数验证 -- 必带参数 如 api sys_code timestamp
		$this->check->checkNotNull($this->_api,'api');
		$this->check->checkMinLength($this->_api,4,'api');
		$this->check->checkMaxLength($this->_api,10,'api');
		$this->check->checkNotNull($this->_sys_code,'sys_code');
		$this->check->checkMinLength($this->_sys_code,4,'sys_code');
		$this->check->checkMaxLength($this->_sys_code,10,'sys_code');
		$this->check->checkNotNull($this->_timestamp,'timestamp');
		$this->check->checkMaxValue($this->_timestamp,time()+300,'timestamp');
		$this->check->checkMinValue($this->_timestamp,time()-300,'timestamp');
		//验证来源系统是否存在
		$secret_key = $this->check_system();
		//根据密钥验证签名sign
		//$this->check_sign($secret_key);
		//根据请求接口引入相应文件
		$this->load->library('api/'.($api = $this->check_api()));
		//记录访问
		//$this->api_logs->insert(array('api'=>$api,'ip_address'=>get_ip(),'log_time'=>time(),'system_code'=>$this->_sys_code));
		//执行调用
		$this->{$api}->execute();
	}

	/**
	 * 验证访问者IP
	 */
	private function check_ip()
	{
		if(!$this->ip_white_list->get_one(array('ip_address'=>get_ip()))){
			$this->result->set("server-check-error:Invalid ip_address ".get_ip(),11)->output();
		}
	}

	/**
	 * 验证访问者系统
	 */
	private function check_system()
	{
		$sys_info = $this->new7_system->get_one(array('system_name'=>$this->_sys_code));
		if(!$sys_info)
		{
			$this->result->set("server-check-error:Invalid sys_code",12)->output();
		}else{
			return $sys_info['secret_key'];
		}
	}

	/**
	 * 验证要访问的API
	 */
	private function check_api()
	{
		$api_info = $this->api_list->get_one(array('api_string'=>$this->_api));
		if(!$api_info OR $api_info['is_closed'] == 1)
		{
			$this->result->set("server-check-error:Invalid api",13)->output();
		}
		return $this->_api;
	}

	/**
	 * 验证签名
	 */
	private function check_sign($secret_key = '')
	{
		if(!isset($this->_sign) OR $this->get_sign($secret_key) != $this->_sign)
			$this->result->set("server-check-error:Invalid sign",14)->output();
	}

	/**
	 * 重构签名
	 */
	private function get_sign($secret_key = '')
	{
		return md5($this->_api.$this->_sys_code.$secret_key.$this->_timestamp);
	}
}
