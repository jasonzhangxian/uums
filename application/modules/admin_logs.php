<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


  class Module_admin_logs extends Module {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'admin_logs';
      $this->_group = 'admin_user';
      $this->_icon = 'personal.png';
      $this->_sort_order = 2;
      
      $this->_title = '操作日志';
    }
  }
  
/* End of file admin_logs.php */
/* Location: ./modules/admin_logs.php */