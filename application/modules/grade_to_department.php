<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


  class Module_grade_to_department extends Module {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'grade_to_department';
      $this->_group = 'department';
      $this->_icon = 'personal.png';
      $this->_sort_order = 17;
      
      $this->_title = '部门职级分配';
    }
  }
  
/* End of file grade_to_department.php */
/* Location: ./modules/grade_to_department.php */