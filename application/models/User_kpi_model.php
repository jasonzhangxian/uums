<?php

/**
 * Created on: 2015-08-07 by zxd 
 * @author zxd
 */
class User_kpi_model extends MY_Model {

	private $_table_name = 'user_kpi';

    public function __construct() {
        parent::__construct($this->_table_name);
    }

    public function get_all_info($where = array() , $fields = '*' , $sort = '' , $order = 'asc' , $limit = 0 , $offset = 0) 
	{
		$this->db->select($fields);
		$this->db->from($this->_table_name);
		$this->db->join('admin_user','admin_user.user_id='.$this->_table_name.'.user_id');
		//$this->db->join('department','admin_user.department_id=department.department_id');
		//$this->db->join('grade','admin_user.grade_id=grade.grade_id');
        $this->_construct_where($where);

        if (!empty($order))
            $this->db->order_by($sort,$order);
		if($limit > 0)
			$this->db->limit($limit,$offset);
		$query = $this->db->get();
		return array('total'=>$query->num_rows(),'data'=>$query->result_array());
    }
    
    public function get_all_sum($where = array()) 
	{
		$this->db->select('admin_user.realname,department_name,grade_name,min(month)min_month,max(month)max_month,sum(kpi1)kpi1,sum(kpi2)kpi2,avg(salary)salary,avg(performance_pay)performance_pay');
		$this->db->from($this->_table_name);
		$this->db->join('admin_user','admin_user.user_id='.$this->_table_name.'.user_id');
        $this->_construct_where($where);
        $this->db->group_by(array('user_kpi.user_id','user_kpi.department_name','user_kpi.grade_name'));
		$query = $this->db->get();
		return $query->result_array();
    }
}

/* End of file user_kpi.php */
/* Location: ./application/models/user_kpi.php */
