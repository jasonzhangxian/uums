<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_logs extends Admin_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('api_logs_model','api_logs');
    }

	public function index()
	{
		//$this->twig->render('api_logs',$this->api_logs->get_one());
		$api_logs = $this->api_logs->get_one();
		$this->twig->assign('user_name',$api_logs['user_name']);
		$this->twig->render('api_logs');
	}
}
