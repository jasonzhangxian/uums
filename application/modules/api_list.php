<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


  class Module_api_list extends Module {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'api_list';
      $this->_group = 'api_list';
      $this->_icon = 'personal.png';
      $this->_sort_order = 8;
      
      $this->_title = '接口设置';
    }
  }
  
/* End of file api_list.php */
/* Location: ./modules/api_list.php */