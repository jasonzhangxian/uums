<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


  class Module_ip_white_list extends Module {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'ip_white_list';
      $this->_group = 'api_list';
      $this->_icon = 'personal.png';
      $this->_sort_order = 9;
      
      $this->_title = 'IP白名单';
    }
  }
  
/* End of file ip_white_list.php */
/* Location: ./modules/ip_white_list.php */