<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


  class Module_admin_user extends Module {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'admin_user';
      $this->_group = 'admin_user';
      $this->_icon = 'personal.png';
      $this->_sort_order = 1;
      
      $this->_title = '用户设置';
    }
  }
  
/* End of file admin_user.php */
/* Location: ./modules/admin_user.php */