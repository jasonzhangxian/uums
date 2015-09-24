<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
class Grade extends REST_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('grade_model','grade');
    }

    public function index()
    {
        //$this->twig->render('grade',$this->grade->get_one());
        $grade = $this->grade->get_one();
        $this->twig->assign('user_name',$grade['user_name']);
        $this->twig->render('grade');
    }
    //后添加
    /**
     * 获取职位信息
     * 根据ID获取单条职位信息、获取列表
     * @access public
     * @return string
     */
     public function grade_get()
     {
        $action = $this->get('action');
        if(!empty($action))
        {
            $result = $this->{'grade_'.$action}();
        }else
            {
            $grade_id = $this->get('grade_id');
            if($grade_id)
            {
                $data = $this->grade->get_one(array('grade_id'=>$grade_id));
                $grade_info = $this->grade->get_one(array('grade_id'=>$data['parent_id']));
                $data['parent_name'] = $grade_info['grade_name'];
                $result = array('success' => TRUE, 'data' => $data);
            }
            else
            {
                $start = $this->get('start') ? $this->get('start') : 0;
                $limit = $this->get('limit') ? $this->get('limit') : MAX_DISPLAY_SEARCH_RESULTS;
                $search = $this->get('search');
                $where = array();
                $records = array();
                if(!empty($search)){
                    $where['like'] = array('grade_name'=>$search);
                }
                $grade = $this->grade->get_all($where,'*','grade_id','desc', $limit,$start);
                $total = $this->grade->get_count($where);
               

                 if($grade!==NULL){
                    foreach($grade as $g)
                    {
                      $grade_info = $this->grade->get_one(array('grade_id'=>$g['parent_id']));
                      $records[] = array(
                                          'grade_name' => $g['grade_name'],
                                          'grade_level' => $g['grade_level'],
                                          'parent_id'=>$g['parent_id'],
                                          'parent_name'=>$grade_info['grade_name'],
                                          'order' => $g['order']
                                          
                              );     
                    }
                }
                $result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
            }
        }
        $this->response($result, REST_Controller::HTTP_OK);
     }


     /**
     * 保存职位信息（添加与修改）
     *
     * @access public
     * @return string
     */
     public function grade_post(){
        $grade_id = $this->post('grade_id');
        $grade_name = $this->post('grade_name');
        $grade_level = $this->post('grade_level');
        $parent_id = $this->post('parent_id');
        $order = $this->post('order');
        $error = FALSE;
        $feedback = array();
        $data =array('grade_id'=>$grade_id,
                     'grade_name'=>$grade_name,
                     'grade_level'=>$grade_level,
                     'parent_id'=>$parent_id,
                     'order'=>$order
                    );
        if($grade_id == $parent_id)
        {
          $error = TRUE;
          $feedback[] = "所属上级不能选自己！";
        }
        if ($error === FALSE)
        {
            if($this->grade->save((is_numeric($grade_id) ? array('grade_id'=>$grade_id) : NULL),$data))
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
     * 删除职位
     *
     * @access public
     * @param $id
     * @return string
     */
    public function grade_delete()
    {
        $grade_id = $this->delete('grade_id');
        //验证是否可以删除（存在下级部门不可删，存在职级不可删，存在员工不可删....）
        $children = $this->grade->get_all(array('parent_id'=>$grade_id));
        $this->load->model(array('admin_user_model','grade_to_department_model'));
        $admin_user = $this->admin_user_model->get_all(array('grade_id'=>$grade_id));
        $department = $this->grade_to_department_model->get_all(array('grade_id'=>$grade_id));
        if($children){
            $response = array('success' => FALSE, 'feedback' => '错误： 存在下属职级，不可以删除。');
        }else if($admin_user){
            $response = array('success' => FALSE, 'feedback' => '错误： 该职级有仍有用户，不可以删除。');
        }else if($department){
            $response = array('success' => FALSE, 'feedback' => '错误： 该职级有仍有所属部门，不可以删除。');
        }else{
            if ($this->grade->delete(array('grade_id'=>$grade_id)))
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

    private function grade_tree()
    {
        $node = $this->get('node');
        $result = $this->grade->get_tree($node);
        return $result;
    }
}
