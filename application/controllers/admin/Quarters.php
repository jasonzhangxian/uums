<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quarters extends Admin_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('quarters_model','quarters');
    }

	public function index()
	{
		//$this->twig->render('quarters',$this->quarters->get_one());
		$quarters = $this->quarters->get_one();
		$this->twig->assign('user_name',$quarters['user_name']);
		$this->twig->render('quarters');
	}
}
