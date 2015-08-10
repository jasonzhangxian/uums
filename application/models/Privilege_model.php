<?php

/**
 * Created on: 2015-08-07 by zxd 
 * @author zxd
 */
class Privilege_model extends MY_Model {

	private $_table_name = 'privilege';

    public function __construct() {
        parent::__construct($this->_table_name);
    }

    
}

/* End of file access.php */
/* Location: ./application/models/access.php */
