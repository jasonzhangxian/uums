<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


  class Module_privilege extends Module {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'privilege';
      $this->_group = 'privilege';
      $this->_icon = 'personal.png';
      $this->_sort_order = 4;
      
      $this->_title = '权限管理';
    }
  }
  
/* End of file privilege.php */
/* Location: ./modules/privilege.php */