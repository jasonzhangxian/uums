<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_user extends Admin_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('admin_user_model','admin_user');
    }

	public function index()
	{
		//$this->twig->render('admin_user',$this->admin_user->get_one());
		$admin_user = $this->admin_user->get_one();
		$this->twig->assign('user_name',$admin_user['user_name']);
		$this->twig->render('admin_user');
	}
}
