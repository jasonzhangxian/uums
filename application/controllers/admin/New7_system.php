<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class New7_system extends Admin_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('new7_system_model','new7_system');
    }

	public function index()
	{
		//$this->twig->render('new7_system',$this->new7_system->get_one());
		$new7_system = $this->new7_system->get_one();
		$this->twig->assign('user_name',$new7_system['user_name']);
		$this->twig->render('new7_system');
	}
}
