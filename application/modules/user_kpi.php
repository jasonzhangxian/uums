<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


  class Module_user_kpi extends Module {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'user_kpi';
      $this->_group = 'admin_user';
      $this->_icon = 'personal.png';
      $this->_sort_order = 1;
      
      $this->_title = '绩效考核';
    }
  }
  
/* End of file user_kpi.php */
/* Location: ./modules/user_kpi.php */