<?php

/**
 * Created on: 2015-08-07 by zxd 
 * @author zxd
 */
class Ip_white_list_model extends MY_Model {

	private $_table_name = 'ip_white_list';

    public function __construct() {
        parent::__construct($this->_table_name);
    }

    
}

/* End of file access.php */
/* Location: ./application/models/access.php */
