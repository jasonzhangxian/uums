<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Privilege extends Admin_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('privilege_model','privilege');
    }

	public function index()
	{
		//$this->twig->render('privilege',$this->privilege->get_one());
		$privilege = $this->privilege->get_one();
		$this->twig->assign('user_name',$privilege['user_name']);
		$this->twig->render('privilege');
	}
}
