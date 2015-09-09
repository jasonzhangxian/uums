<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


  class Module_api_logs extends Module {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'api_logs';
      $this->_group = 'api_list';
      $this->_icon = 'personal.png';
      $this->_sort_order = 200;
      
      $this->_title = '操作日志';
    }
  }
  
/* End of file api_logs.php */
/* Location: ./modules/api_logs.php */