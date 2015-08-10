<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grade extends Admin_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('grade_model','grade');
    }

	public function index()
	{
		//$this->twig->render('grade',$this->grade->get_one());
		$grade = $this->grade->get_one();
		$this->twig->assign('user_name',$grade['user_name']);
		$this->twig->render('grade');
	}
}
