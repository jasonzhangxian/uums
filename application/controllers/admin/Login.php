<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Login Controller
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Login extends Admin_Controller
{
    /**
     * Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();
		$this->load->library('admin');
    }

    /**
     * Default Function
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->twig->assign('title','啥啥啥');

		$this->twig->render('login');
    }

    /**
     * Login Process Function
     *
     * @access public
     * @return void
     */
    public function process()
    {
        $username = $this->input->post('user_name');
        $password = $this->input->post('user_password');
		$this->load->library('encryption');
		$this->load->library('uums',array('api'=>'login','username'=>$username,'password'=>$this->encryption->encrypt($password)));
		$response = $this->uums->execute();
        if (!isset($response->msg))
        {
			$customer = $this->admin->login($response);
			$response = array('success' => TRUE);
        }
        else
        {
            $response = array('success' => FALSE, 'error' => $response->msg);
        }

        $this->set_output($response);
    }


}

/* End of file login.php */
/* Location: ./application/controllers/login.php */