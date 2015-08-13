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
    function __construct() {
        parent::__construct();
        $base_url = base_url();
        $site_url = site_url();
        $this->twig->assign('base_url', $base_url);
        $this->twig->assign('site_url', $site_url);
    }

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */