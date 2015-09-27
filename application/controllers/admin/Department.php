<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Department extends REST_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() 
    {
        parent::__construct();
        $this->load->model('department_model','department');
    }

    
    /**
     * 获取部门信息
     * 根据ID获取单条部门信息、获取列表
     * @access public
     * @return string
     */
    public function department_get()
    {
        $action = $this->get('action');
        if(!empty($action))
        {
            $result = $this->{'department_'.$action}();
        }
        else
        {
            $department_id = $this->get('department_id');
             if($department_id)
            {
                $data = $this->department->get_one(array('department_id'=>$department_id));
                $department_info = $this->department->get_one(array('department_id'=>$data['parent_id']));
                $data['parent_name'] = $department_info['department_name'];
                $result = array('success' => TRUE, 'data' => $data);
            }
            else
            {
                $start = $this->get('start') ? $this->get('start') : 0;
                $limit = $this->get('limit') ? $this->get('limit') : MAX_DISPLAY_SEARCH_RESULTS;
                $search = $this->get('search');
                $where = array();
                $records = array();
                if(!empty($search))
                {
                    $where['like'] = array('department_name'=>$search);
                }
                $department = $this->department->get_all($where,'*','department_id','desc', $limit,$start);
                $total = $this->department->get_count($where);
                if($department!==NULL)
                {
                    foreach($department as $d)
                    {
                        $department_info = $this->department->get_one(array('department_id'=>$g['parent_id']));
                        $records[] = array(
                                            'department_id' => $d['department_id'],
                                            'department_name' => $d['department_name'],
                                            'departmnt_level' => $d['departmnt_level'],
                                            'parent_id' => $d['parent_id'],
                                            'parent_name' => $department_info['department_name']
                                        );     
                    }
                }
                $result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
            }
        }
        $this->response($result, REST_Controller::HTTP_OK);
     }
    /**
     * 保存部门信息（添加与修改）
     *
     * @access public
     * @return string
     */
    public function department_post()
    {
        $this->load->library('form_validation');
        $department_id = $this->post('department_id');
        $department_name = $this->post('department_name');
        $departmnt_level = $this->post('departmnt_level');
        $parent_id = $this->post('parent_id');
        $order = $this->post('order');
        $error = FALSE;
        $feedback = array();
        $data = array('department_id'=>$department_id,
                      'department_name'=>$department_name,
                      'departmnt_level'=>$departmnt_level,
                      'parent_id'=>$parent_id,
                      'order'=>$order
                     );

        if($department_id == $parent_id)
        {
          $error = TRUE;
          $feedback[] = "所属上级不能选自己！";
        }

        $department_name_error_message = array('required'  => '%s不能为空.',
                                        'min_length' => '%s长度必须大于2位',
                                        'is_unique' => '您输入的%s系统中已存在.'
                                  );
        $department_name_rule = 'trim|required|min_length[3]|is_unique[department.department_name]';
        $this->form_validation->set_rules('department_name','部门名称',$department_name_rule,$department_name_error_message);

        if ($this->form_validation->run() == false) 
        {
          $error = TRUE;
          $feedback[] = validation_errors();
        }
        if ($error === FALSE)
        {
            if($this->department->save((is_numeric($department_id) ? array('department_id'=>$department_id) : NULL),$data))
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
     * 删除部门
     *
     * @access public
     * @param $id
     * @return string
     */
    public function department_delete()
    {
        $department_id = $this->delete('department_id');
        //验证是否可以删除（存在下级部门不可删，存在职级不可删，存在员工不可删....）
        $children = $this->department->get_all(array('parent_id'=>$department_id));
        $this->load->model(array('admin_user_model','grade_to_department_model'));
        $admin_user = $this->admin_user_model->get_all(array('department_id'=>$department_id));
        $grade = $this->grade_to_department_model->get_all(array('department_id'=>$department_id));
        if($children)
        {
            $response = array('success' => FALSE, 'feedback' => '错误： 存在下属部门，不可以删除。');
        }
        else if($admin_user)
        {
            $response = array('success' => FALSE, 'feedback' => '错误： 该部门有仍有用户，不可以删除。');
        }
        else if($grade)
        {
            $response = array('success' => FALSE, 'feedback' => '错误： 该部门有仍有职级，不可以删除。');
        }
        else
        {
            if ($this->department->delete(array('department_id'=>$department_id)))
            {
                $response = array('success' => TRUE, 'feedback' => '成功： 此项操作成功。');
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => '错误： 操作失败。');
            }
        }


        $this->response($response, REST_Controller::HTTP_OK);
    }


    /**
     * 获取部门树形结构
     *
     * @access public
     * @param $node
     * @return string
     */
    private function department_tree()
    {
        $node = $this->get('node');
        $result = $this->department->get_tree($node);
        return $result;
    }
}
