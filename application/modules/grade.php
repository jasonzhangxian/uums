<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


  class Module_grade extends Module {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'grade';
      $this->_group = 'department';
      $this->_icon = 'personal.png';
      $this->_sort_order = 16;
      
      $this->_title = '职位管理';
    }
  }
  
/* End of file grade.php */
/* Location: ./modules/grade.php */