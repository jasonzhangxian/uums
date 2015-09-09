<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


  class Module_department extends Module {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'department';
      $this->_group = 'department';
      $this->_icon = 'personal.png';
      $this->_sort_order = 5;
      
      $this->_title = '部门管理';
    }
  }
  
/* End of file department.php */
/* Location: ./modules/department.php */