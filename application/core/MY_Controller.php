<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 默认控制器类
 */
class MY_Controller extends CI_Controller {
    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Shanghai');
        header('Content-type:text/html; charset=utf-8');
        $this->load->helper('url');

    }
}

/**
 * API接口控制器类
 */
class Api_Controller extends MY_Controller {
    function __construct() {
        parent::__construct();
        //此处要做哪些方面的验证
    }

}

/**
 * 后台控制器类
 */
class Admin_Controller extends MY_Controller {

    function __construct() 
	{
        parent::__construct();
		//判断是否已登录（除了不需登陆的界面外）否则跳转登陆界面		
		$this->load->library('admin');
		if (!$this->admin->is_logged_on() && !$this->admin->is_need_login())
        {
			if($this->is_ajax())
			{
				echo json_encode(array('error'=>'not login'));
				exit;
			}else
				redirect('admin/login/index');
		}
		//缓存
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

        $base_url = base_url();
        $site_url = site_url();
		//设置一些基础数据
        $this->twig->assign('base_url', $base_url);
        $this->twig->assign('site_url', $site_url);
        $this->twig->assign('images_url', $base_url."templates/base/web/images/");
        $this->twig->assign('css_url', $base_url."templates/base/web/css/");
        $this->twig->assign('js_url', $base_url."templates/base/web/javascript/");

	}

    // --------------------------------------------------------------------

    /**
     * Is this a ajax request
     *
     * @access protected
     * @return bool
     */
    protected function is_ajax()
    {
        return $this->input->is_ajax_request();
    }

    // --------------------------------------------------------------------


    /**
     * set output
     *
     * The sub class could override this method to extend the output type
     *
     * @access protected
     * @param array or string or xml etc...
     * @return void
     */
    protected function set_output($output) 
	{
        $type = gettype($output);

        if ($type == 'array')
        {
            $this->output_json($output);
        }

        if ($type == 'string')
        {
            $this->output_string($output);
        }
    }

    // --------------------------------------------------------------------

    /**
     * set output to a json string
     *
     * @access private
     * @param array
     * @return void
     */
    private function output_json($output)
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    // --------------------------------------------------------------------

    /**
     * set output to a string
     *
     * @access private
     * @param string
     * @return void
     */

    private function output_string($output)
    {
        $this->output->set_content_type('text/plain')->set_output($output);
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */