<?php

/**
 * Created on: 2015-08-07 by zxd 
 * @author zxd
 */
class Admin_logs_model extends MY_Model {

	private $_table_name = 'admin_logs';

    public function __construct() {
        parent::__construct($this->_table_name);
    }

    
}

/* End of file access.php */
/* Location: ./application/models/access.php */
