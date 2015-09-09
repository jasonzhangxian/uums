<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Privilege extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('privilege_model','privilege');
    }

    /**
     * 获取权限相关信息
     * 根据action不同，获取树形结构、获取详情、获取列表
     * @access public
     * @return string
     */
    public function privilege_get()
    {
        $action = $this->get('action');
		if(!empty($action))
		{
			$result = $this->{'privilege_'.$action}();
		}else
		{
			$privilege_id = $this->get('privilege_id');
			if($privilege_id)
			{
				$data = $this->privilege->get_one(array('privilege_id'=>$privilege_id));
				$result = array('success' => TRUE, 'data' => $data);
			}else
			{
				$start = $this->get('start') ? $this->get('start') : 0;
				$limit = $this->get('limit') ? $this->get('limit') : MAX_DISPLAY_SEARCH_RESULTS;
				$search = $this->get('search');
				$where = array();
				$records = array();
				if(!empty($search)){
					$where['like'] = array('privilege_name'=>$search,'privilege_code'=>$search);
				}
				$privilege = $this->privilege->get_all($where,'*','privilege_id','desc', $limit,$start);
				$total = $this->privilege->get_count($where);
				if ($privilege !== NULL)
				{
					foreach($privilege as $q)
					{
						$records[] = array(
							'privilege_id' => $q['privilege_id'],
							'privilege_name' => $q['privilege_name'],
							'privilege_code' => $q['privilege_code'],
							'status' => $q['status']);     
					}
				}
				$result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
			}
		}
		$this->response($result, REST_Controller::HTTP_OK);
    }

    /**
     * 保存权限信息
     *
     * @access public
     * @return string
     */
    public function privilege_post()
    {
        $privilege_id = $this->input->post('privilege_id');
        $privilege_name = $this->input->post('privilege_name');
        $sys_code = $this->input->post('sys_code');
        $parent_id = $this->input->post('parent_id');
        $privilege_code = $this->input->post('privilege_code');
        $status = $this->input->post('status');
        $sort_order = $this->input->post('sort_order');
        $type = $this->input->post('type');

        $error = FALSE;
        $feedback = array();

        //customer firstname
        if (empty($privilege_name))
        {
            $error = TRUE;
            $feedback[] = '权限名称不能为空';
        }
		if(empty($sys_code))
        {
            $error = TRUE;
            $feedback[] = '请选择所属系统';
        }
		if(empty($privilege_code))
        {
            $error = TRUE;
            $feedback[] = '权限编码不能为空';
        }
		$tmp = $this->privilege->get_one(array('sys_code'=>$sys_code,'privilege_code' => $privilege_code));
		if(!empty($tmp) && $tmp['privilege_id'] != $privilege_id)
		{
            $error = TRUE;
            $feedback[] = '该权限编码已经存在，请修改';
		}

        $data = array('privilege_name' => $privilege_name,
						'sys_code' => $sys_code,
						'parent_id' => $parent_id,
						'privilege_code' => $privilege_code,
						'sort_order' => $sort_order,
						'type' => $type,
						'status' => $status,
						'update_time'=> time(),
						'update_user_id'=> $this->admin->get_id());
		if($parent_id)
		{
			$tmp = $this->privilege->get_one(array('privilege_id'=>$parent_id));
			$data['level'] = $tmp['level'] + 1;
		}else
		{
			$data['level'] = 0;
		}
        //save customer data
        if ($error === FALSE)
        {
            if ($this->privilege->save((is_numeric($privilege_id) ? array('privilege_id'=>$privilege_id) : NULL),$data))
            {
                $response = array('success' => TRUE, 'feedback' => '成功： 此项操作成功');
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => '错误： 操作失败');
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => '错误： 操作失败' . '<br />' . implode('<br />', $feedback));
        }

		$this->response($response, REST_Controller::HTTP_OK);
    }
    /**
     * 修改状态
     *
     * @access public
     * @return string
     */
    public function privilege_put()
    {
        $status = $this->put('status');
        $privilege_id = $this->put('privilege_id');
        $children_status = $this->put('children_status');

        if ($this->privilege->update(array('status'=>$status),array('privilege_id'=>$privilege_id)))
        {
			if($children_status)
				$this->privilege->update(array('status'=>$status),array('parent_id'=>$privilege_id));
            $response = array('success' => TRUE, 'feedback' => '成功： 此项操作成功。');
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => '错误： 操作失败。');
        }

		$this->response($response, REST_Controller::HTTP_OK);
    }

    /**
     * 删除权限
     *
     * @access public
     * @param $privilege_id
     * @return string
     */
    public function privilege_delete($privilege_id = '')
    {
        $privilege_id = $this->delete('privilege_id');
		//验证是否可删
		$tmp = $this->privilege->get_one(array('parent_id'=>$privilege_id));
		if(empty($tmp))
		{
			if ($this->privilege->delete(array('privilege_id'=>$privilege_id)))
			{
				$response = array('success' => TRUE, 'feedback' => '成功： 此项操作成功。');
			}
			else
			{
				$response = array('success' => FALSE, 'feedback' => '错误： 操作失败。');
			}
		}
		else
		{
			$response = array('success' => FALSE, 'feedback' => '错误： 操作失败。<br>请先删除该权限下的子权限');
		}

		$this->response($response, REST_Controller::HTTP_OK);
    }

	private function privilege_tree()
	{
		$node = $this->get('node');
		$result = $this->privilege->get_tree($node);
		return $result;
	}
	private function privilege_list()
	{
		$start = $this->get('start') ? $this->get('start') : 0;
		$limit = $this->get('limit') ? $this->get('limit') : MAX_DISPLAY_SEARCH_RESULTS;
		$privilege_id = $this->get('privilege_id');
		$sys_code = $this->get('sys_code');
		$where = array();
		if(is_numeric($privilege_id))
		{
			$where = array('parent_id'=>$privilege_id);
		}else
		{
			$where = !empty($privilege_id)?array('sys_code'=>$privilege_id):array();
		}
		if(!empty($sys_code))
			$where['sys_code'] = $sys_code;
		$records = $this->privilege->get_all($where,'*','privilege_id','desc', $limit,$start);
		if(!empty($records))
		{
			$admin_user_list = $this->cache->get('admin_user_list');
			$new7_system_list = $this->cache->get('new7_system_list');
			$privilege_list = $this->cache->get('privilege_list');
			foreach($records as &$value)
			{
				$value['update_user_name'] = $admin_user_list[$value['update_user_id']]['realname'];
				$value['system_name'] = $new7_system_list[$value['sys_code']]['system_name'];
				$value['parent_name'] = $value['parent_id']==0?'':$privilege_list[$value['parent_id']]['privilege_name'];
				$value['update_time'] = date("Y-m-d H:i",$value['update_time']);
				$value['margin'] = $value['level']*10;
			}
		}
		$total = $this->privilege->get_count($where);
		$result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
		return $result;
	}
	private function privilege_folder()
	{
		$privilege_id = $this->get('privilege_id');
		$sys_code = $this->get('sys_code');
		$where = array();
		if(is_numeric($privilege_id))
		{
			$where = array('parent_id'=>$privilege_id);
		}else
		{
			$where = !empty($privilege_id)?array('sys_code'=>$privilege_id):array();
		}
		if(!empty($sys_code))
			$where['sys_code'] = $sys_code;
		$records = $this->privilege->get_all($where,'*','sort_order','asc');
		if(!empty($records))
		{
			$result = array();
			$len = count($records);
			$my_parent_key = array(0);
			for($i=0;$i<=$len;$i++)
			{
				$j = 1;
				foreach($records as $key=>&$value)
				{
					$value['margin'] = $value['level']*10;
					if($value['level'] == $i)
					{
						$k= str_pad(pow(10,($i+1))+$j+$my_parent_key[$value['parent_id']],$len,'0');
						if($i>=0)
							$my_parent_key[$value['privilege_id']] = $k;
						//echo $j."_".$value['parent_id']."_".$i."_".$k."";
						$result[$k] = $value;
						unset($records[$key]);
						$j++;
					}
				}
			}
		}
		ksort($result);
		//print_r($result);
		$result = array(EXT_JSON_READER_ROOT => array_values($result));
		return $result;
	}
	private function privilege_move()
	{
		$privilege_ids = $this->get('privilege_ids');
		$parent_id = $this->get('parent_id');
		$result = array();
		if($privilege_ids)
		{
			$privilege_ids = json_decode($privilege_ids,TRUE);
			if($parent_id !== NULL)
			{
				//判断？避免形成死循环？
				//执行移动
				$tmp = $this->privilege->get_one(array('privilege_id'=>$parent_id));
				foreach($privilege_ids as $privilege_id)
				{
					$this->privilege->update(array('parent_id'=>$parent_id,'level'=>$tmp['level']+1),array('privilege_id'=>$privilege_id));
				}
				$result = array('success' => TRUE, 'feedback' => '成功： 此项操作成功。');
			}
			else
			{
				$data = $this->privilege->get_all(array('in privilege_id'=>$privilege_ids));
				if(!empty($data))
				{
					foreach($data as $key =>$value)
					{
						$parent_id = $value['parent_id'];
						$sys_code = $value['sys_code'];
					}
				}
				$result = array('success' => TRUE, 'data' => array('parent_id' =>$parent_id,'sys_code'=>$sys_code));
			}
		}
		return $result;
	}
}
