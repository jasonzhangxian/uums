<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


  class Module_quarters_to_privilege extends Module {
    public function __construct()
    {
      parent::__construct();
      
      $this->_module = 'quarters_to_privilege';
      $this->_group = 'quarters';
      $this->_icon = 'personal.png';
      $this->_sort_order = 3;
      
      $this->_title = '岗位权限分配';
    }
  }
  
/* End of file quarters_to_privilege.php */
/* Location: ./modules/quarters_to_privilege.php */