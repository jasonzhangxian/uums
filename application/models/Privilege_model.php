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

    public function get_tree($parent_id = 0)
	{
		$tree = array();
		//各个系统下面的权限
		if($parent_id)
		{
			$children = $this->get_all(array('parent_id'=>$parent_id));
			if(!empty($children))
			{
				foreach($children as $child)
				{
					$node_children = array('id'=>$child['privilege_id'],'text'=>$child['privilege_name'],'leaf'=>TRUE);
					$my_child = $this->get_tree($child['privilege_id']);
					if(!empty($my_child))
					{
						$node_children['children'] = $my_child;
						unset($node_children['leaf']);
					}
					$tree[] = $node_children;
				}
			}
		}else
		{
			$this->load->model('new7_system_model','new7_system');
			$system = $this->new7_system->get_all();
			if(!empty($system))
			{
				foreach($system as $sys)
				{
					$children = $this->get_all(array('sys_code'=>$sys['sys_code'],'parent_id'=>$parent_id));
					$node_children = array();
					if(!empty($children))
					{
						foreach($children as $child)
						{
							$tmp = array('id'=>$child['privilege_id'],'text'=>$child['privilege_name'],'leaf'=>TRUE);
							$my_child = $this->get_tree($child['privilege_id']);
							if(!empty($my_child))
							{
								$tmp['children'] = $my_child;
								unset($tmp['leaf']);
							}
							$node_children[] = $tmp;
						}
					}
					$tree[] = array('id'=>$sys['sys_code'],'text'=>$sys['system_name'],'children'=>$node_children);
				}
			}
		}
		return $tree;
	}

	public function list_to_tree($data,$level = 0)
	{
		$result = array();
		$tmp = array();
		if(!empty($data))
		{
			foreach($data as $v)
			{
				$tmp[$v['level']][$v['parent_id']][$v['privilege_id']] = array('id'=>$v['privilege_id'],'text'=>$v['privilege_name'],'is_leaf'=>'TRUE');
			}
		}
		//print_r($tmp);
		for($i=count($tmp)-2;$i>=0;$i--)
		{
			if(!empty($tmp[$i]))
			{
				foreach($tmp[$i] as $key=>$val)
				{
					foreach($val as $k=>$v)
					{
						$node = array('id'=>$v['id'],'text'=>$v['text'],'is_leaf'=>'TRUE');
						if(isset($tmp[$i+1][$v['id']]))
							$node['children'] = array_values($tmp[$i+1][$v['id']]);
						if($i==0)
							$result[] = $node;
						else
							$tmp[$i][$key][$k] = $node;
					}
				}
			}
		}
		//print_r($result);exit;
		return $result;
	}
}

/* End of file access.php */
/* Location: ./application/models/access.php */
