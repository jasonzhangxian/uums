<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 实例化表对象
 * 对每个表创建一个Model
 * 继承此类，就不需要再去重复的写简单的增删改查
 * 复杂的操作，再单独写function
 * 对应多表联合查询，写在主表的Model里面
 */

class MY_Model extends CI_Model {

	private $_table_name = '';


    public function __construct($table_name = '') 
	{
        parent::__construct();
		if(!empty($table_name))
			$this->_table_name = $table_name;
    }

    /**
     * 获取单条数据
     * 
     * @access   public
     * @param  $where  array    条件数组
     * @param  $fields  string   查询字段
     * @return   array    一维数据数组
     */
    public function get_one($where = array(), $fields = '*') 
	{
		$this->db->select($fields);
		$this->db->from($this->_table_name);
        if ($where) {
            $this->db->where($where);
        }
        return $this->db->get()->row_array();
    }

    /**
     * 获取多条数据
     * 
     * @access   public
     * @param    array    条件数组
     * @param    string   查询字段
     * @return   array    多维数据数组
     */
    public function get_all($where = array() , $fields = '*' , $sort = '' , $order = 'asc' , $limit = 0 , $offset = 0) 
	{
		$this->db->select($fields);
		$this->db->from($this->_table_name);
        $this->_construct_where($where);

        if (!empty($order))
            $this->db->order_by($sort,$order);
		if($limit > 0)
			$this->db->limit($limit,$offset);
		return $this->db->get()->result_array();
    }

    /*
     * 查询总数 
     */

    public function get_count($where = array()) 
	{
        $this->_construct_where($where);
        
        $count = $this->db->count_all_results($this->_table_name);
        return $count;
    }


    /*
     * 添加数据
     * 
     * @access   public
     * @param    array    数据数组
     * @return   number   添加的记录 ID
     */

    public function insert($data) 
	{
        $this->db->insert($this->_table_name, $data);
        return $this->db->insert_id();
    }

    /*
     * 更新数据
     * 
     * @access   public
     * @param    array    数据数组
     * @param    array    条件数组
     * @return   number   影响行数
     */

    public function update($data, $where = array()) 
	{
        if ($where) {
            $this->db->where($where);
        }
        $this->db->update($this->_table_name, $data);
        return $this->db->affected_rows();
    }

    /*
     * 删除数据
     * 
     * @access   public
     * @param    array    条件数组
     * @return   number   影响行数
     */

    public function delete($where) 
	{
        $this->db->delete($this->_table_name, $where);
        return $this->db->affected_rows();
    }

    /*
     * 替换数据
     * 
     * @access   public
     * @param    array    数据数组
     * @return   number   添加的记录 ID 或影响的行数
     */

    public function replace($data) 
	{
        return $this->db->replace($this->_table_name, $data);
    }

    /*
     * 获取刚刚执行的SQL语句
     * 
     * @access   public
     * @return   string   SQL语句
     */

	public function last_query()
	{
		return $this->db->last_query();
	}

    /*
     * 判断表格是否存在
     * 
     * @access   public
     * @return   bool
     */

	public function table_exists($table)
	{
		return $this->db->table_exists($table);
	}
    
    /*
     * 保存数据
     * 
     * @access   public
     * @return   bool
     */
    public function save($where,$data)
	{
		if(empty($where))
		{
			return $this->insert($data);
		}else
		{
			$this->update($data,$where);
			return TRUE;
		}
	}
    /*
     * 构造where
     * 
     * @access   protected
     */
    protected function _construct_where($where = array())
    {
        if($where)
        {
            foreach($where as $key=>$value)
            {
                if(strpos($key,"in ") !== FALSE)
                {
                    $this->db->where_in(str_replace('in ','',$key),$value);
                    unset($where[$key]);
                }
                if($key == "like")
                {
                    $i = 0;
                    foreach($value as $k => $v)
                    {
                        $this->db->{$i==0?'like':'or_like'}($k,$v);
                        $i++;
                    }
                    unset($where[$key]);
                }
            }
        }
        if ($where) {
            $this->db->where($where);
        }
        return;
    }
}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */