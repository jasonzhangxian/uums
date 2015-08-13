<?php

/**
 * Created on: 2015-08-07 by zxd 
 * @author zxd
 */
class Admin_user_model extends MY_Model {

	private $_table_name = 'admin_user';

    public function __construct() {
        parent::__construct($this->_table_name);
    }
    
    public function get_user_privilege($user_id,$sys_code = '')
    {
    	$this->db->select('privilege.*');
    	$this->db->from('user_to_quarters');
    	$this->db->join('quarters','user_to_quarters.quarters_id=quarters.quarters_id');
    	$this->db->join('quarters_to_privilege','user_to_quarters.quarters_id=quarters_to_privilege.quarters_id');
    	$this->db->join('privilege','privilege.privilege_id=quarters_to_privilege.privilege_id');
    	$this->db->where(array('user_to_quarters.user_id'=>$user_id));
    	$this->db->where(array('quarters.is_deleted'=>0));
    	$this->db->where(array('privilege.is_deleted'=>0));
    	if($sys_code)
    		$this->db->where(array('privilege.sys_code'=>$sys_code));
    	$data = $this->db->get()->result_array();
    	return ($data);
    }
}

/* End of file access.php */
/* Location: ./application/models/access.php */
