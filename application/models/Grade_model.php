<?php

/**
 * Created on: 2015-08-07 by zxd 
 * @author zxd
 */
class Grade_model extends MY_Model {

	private $_table_name = 'grade';

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
				$node_children = array('id'=>$child['grade_id'],'text'=>$child['grade_name'],'leaf'=>TRUE,'expanded'=>TRUE);
				$my_child = $this->get_tree($child['grade_id']);
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
    
    
}

/* End of file access.php */
/* Location: ./application/models/access.php */
