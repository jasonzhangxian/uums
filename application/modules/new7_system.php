<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


  class Module_new7_system extends Module {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'new7_system';
      $this->_group = 'new7_system';
      $this->_icon = 'personal.png';
      $this->_sort_order = 7;
      
      $this->_title = '系统设置';
    }
  }
  
/* End of file new7_system.php */
/* Location: ./modules/new7_system.php */