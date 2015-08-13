<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Admin_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
	public function do_login()
	{
		$username = 'zhangxuedong';
		$password = md5('3126615');
		$this->load->library('uums',array('api'=>'login','username'=>$username,'password'=>$password));
		$response = $this->uums->execute();
		//echo isset($response->msg)?$response->msg:$response->base_info->username;
		print_d($response);
		//判断是否登录成功
		//登陆成功，写入session
		//跳转
	}
}
