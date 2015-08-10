<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ip_white_list extends Admin_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('ip_white_list_model','ip_white_list');
    }

	public function index()
	{
		//$this->twig->render('ip_white_list',$this->ip_white_list->get_one());
		$ip_white_list = $this->ip_white_list->get_one();
		$this->twig->assign('user_name',$ip_white_list['user_name']);
		$this->twig->render('ip_white_list');
	}
}
