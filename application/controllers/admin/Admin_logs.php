<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_logs extends Admin_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('admin_logs_model','admin_logs');
    }

	public function index()
	{
		//$this->twig->render('admin_logs',$this->admin_logs->get_one());
		$admin_logs = $this->admin_logs->get_one();
		$this->twig->assign('user_name',$admin_logs['user_name']);
		$this->twig->render('admin_logs');
	}
}
