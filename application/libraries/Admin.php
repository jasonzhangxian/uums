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
 * Customer Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	library
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class Admin
{
    /**
     * ci instance
     *
     * @access private
     * @var string
     */
    protected $ci = NULL;

    /**
     * admin data
     *
     * @access private
     * @var array
     */
    protected $_data = array();

    /**
     * admin no_need_login_uri
     *
     * @access private
     * @var array
     */
	protected $no_need_login_uri = array('admin/login/index',//登陆页面
														'admin/lndex/logoff',//退出
														'admin/login/process'//执行登陆
												);
    /**
     * Uums Customer Constructor
     * 
     * @access public
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->ci =& get_instance();

        // Grab the customer data array from the session table, if it exists
        if ($this->ci->session->userdata('admin_data') !== NULL)
        {
            $this->_data = $this->ci->session->userdata('admin_data');
        }

        log_message('debug', "Uums Admin Class Initialized");
    }

    /**
     * Get admin id
     *
     * @access public
     * @return mixed $value
     */
    public function get_id()
    {
        if (isset($this->_data['user_id']) && is_numeric($this->_data['user_id']))
        {
            return $this->_data['user_id'];
        }

        return FALSE;
    }

    /**
     * Get name
     *
     * @access public
     * @return mixed $value
     */
    public function get_name()
    {
        static $username = NULL;

        if (empty($username))
        {
            $username = $this->_data['username'];
        }

        return $username;
    }

    /**
     * Check whether admin is logged in
     *
     * @access public
     * @return mixed $value
     */
    public function is_logged_on()
    {
        static $logged_on = NULL;

        if (is_null($logged_on))
        {
            $logged_on = isset($this->_data['user_id']) && !empty($this->_data['user_id']);
        }

        return $logged_on;
    }
    
    /**
     * Check whether page is need logged in
     *
     * @access public
     * @return mixed $value
     */
	public function is_need_login()
	{
		return in_array($this->ci->router->directory.$this->ci->router->class.'/'.$this->ci->router->method,$this->no_need_login_uri) ;
	}

    /**
     * Perform login action and assign email and passwrod data.
     *
     * @access public
     * @param string $email
     * @param string $password
     */
    public function login($user_info)
    {
        //if customer data is not null
        if ($user_info !== FALSE && isset($user_info->base_info))
        {
            $this->_data = array();

            $this->_data['user_id'] = $user_info->base_info->user_id;
            $this->_data['username'] = $user_info->base_info->username;
            $this->_data['realname'] = $user_info->base_info->realname;
            $this->_data['new7_code'] = $user_info->base_info->new7_code;

            //set data to session
            $this->ci->session->set_userdata('admin_data', $this->_data);
            $this->ci->input->set_cookie('username', $user_info->base_info->username, 7*24*3600);
        }
        //if user data is not found and session is not empty then reset session
        else if ($this->ci->session->userdata('admin_data') !== NULL)
        {
            $this->reset();
        }
    }

    /**
     * Reset admin & session data.
     * 
     * @access public
     */
    public function reset()
    {
        //clean customer data
        $this->_data = array();

        //clean session data
        $this->ci->session->unset_userdata('admin_data');
    }
}
// END Customer Class

/* End of file customer.php */
/* Location: ./system/tomatocart/libraries/customer.php */