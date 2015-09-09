<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


  class Module_quarters extends Module {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'quarters';
      $this->_group = 'quarters';
      $this->_icon = 'personal.png';
      $this->_sort_order = 3;
      
      $this->_title = '岗位管理';
    }
  }
  
/* End of file quarters.php */
/* Location: ./modules/quarters.php */