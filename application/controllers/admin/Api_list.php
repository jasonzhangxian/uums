<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_list extends Admin_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('api_list_model','api_list');
    }

	public function index()
	{
		//$this->twig->render('api_list',$this->api_list->get_one());
		$api_list = $this->api_list->get_one();
		$this->twig->assign('user_name',$api_list['user_name']);
		$this->twig->render('api_list');
	}
}
