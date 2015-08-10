<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends Admin_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('department_model','department');
    }

	public function index()
	{
		//$this->twig->render('department',$this->department->get_one());
		$department = $this->department->get_one();
		$this->twig->assign('user_name',$department['user_name']);
		$this->twig->render('department');
	}
}
