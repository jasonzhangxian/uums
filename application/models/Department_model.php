<?php

/**
 * Created on: 2015-08-07 by zxd 
 * @author zxd
 */
class Department_model extends MY_Model {

	private $_table_name = 'department';

    public function __construct() {
        parent::__construct($this->_table_name);
    }

    public function get_tree($parent_id = 0)
	{
		$tree = array();
		$children = $this->get_all(array('parent_id'=>$parent_id),'*','order','desc');
		if(!empty($children))
		{
			foreach($children as $child)
			{
				$node_children = array('id'=>$child['department_id'],'text'=>$child['department_name'],'leaf'=>TRUE,'expanded'=>TRUE);
				$my_child = $this->get_tree($child['department_id']);
				if(!empty($my_child))
				{
					$node_children['children'] = $my_child;
					unset($node_children['leaf']);
				}
				$tree[] = $node_children;
			}
		}
		return $tree;
	}
	public function get_all_children($parent_id = 0)
	{
		$children_id = array();
		$children = $this->get_all(array('parent_id'=>$parent_id),'*','order','desc');
		if(!empty($children))
		{
			foreach($children as $child)
			{
				$children_id[] = $child['department_id'];
				$my_child = $this->get_all_children($child['department_id']);
				if(!empty($my_child))
				{
					$children_id = array_merge($children_id, $my_child);
				}
			}
		}
		return $children_id;
	}    
}

/* End of file access.php */
/* Location: ./application/models/access.php */
