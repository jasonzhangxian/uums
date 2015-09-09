<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Quarters extends REST_Controller {


    function __construct() {
        parent::__construct();
        $this->load->model('quarters_model','quarters');
    }

    /**
     * 获取岗位信息
     * 根据ID获取单条岗位信息、获取列表
     * @access public
     * @return string
     */
    public function quarters_get()
    {
        $action = $this->get('action');
        if(!empty($action))
        {
            $result = $this->{'quarters_'.$action}();
        }else
        {
            $quarters_id = $this->get('quarters_id');
    		if($quarters_id)
    		{
    			$data = $this->quarters->get_one(array('quarters_id'=>$quarters_id));
    			$result = array('success' => TRUE, 'data' => $data);
    		}else
    		{
    			$start = $this->get('start') ? $this->get('start') : 0;
    			$limit = $this->get('limit') ? $this->get('limit') : MAX_DISPLAY_SEARCH_RESULTS;
    			$search = $this->get('search');
    			$where = array();
    			$records = array();
                if(!empty($search)){
                    $where['like'] = array('quarters_name'=>$search);
                }
    			$quarters = $this->quarters->get_all($where,'*','quarters_id','desc', $limit,$start);
    			$total = $this->quarters->get_count($where);
    			if ($quarters !== NULL)
    			{
    				foreach($quarters as $q)
    				{
    					$records[] = array(
    						'quarters_id' => $q['quarters_id'],
    						'quarters_name' => $q['quarters_name'],
    						'quarters_desc' => $q['quarters_desc'],
    						'status' => $q['status']);     
    				}
    			}
    			$result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
    		}
        }
		$this->response($result, REST_Controller::HTTP_OK);
    }

    /**
     * 保存岗位信息（添加与修改）
     *
     * @access public
     * @return string
     */
    public function quarters_post()
    {
        $quarters_id = $this->input->post('quarters_id');
        $quarters_name = $this->input->post('quarters_name');
        $quarters_desc = $this->input->post('quarters_desc');
        $status = $this->input->post('status');

        $data = array('quarters_name' => $quarters_name,
						'quarters_desc' => $quarters_desc,
						'status' => ( ! empty($status) && ($status == 'on') ? '1' : '0') );

        $error = FALSE;
        $feedback = array();

        //customer firstname
        if (empty($quarters_name))
        {
            $error = TRUE;
            $feedback[] = '岗位名称不能为空';
        }

        //save customer data
        if ($error === FALSE)
        {
            if ($this->quarters->save((is_numeric($quarters_id) ? array('quarters_id'=>$quarters_id) : NULL),$data))
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
     * 修改岗位状态
     *
     * @access public
     * @return string
     */
    public function quarters_put()
    {
        $flag = $this->put('flag');
        $quarters_id = $this->put('quarters_id');

        if ($this->quarters->update(array('status'=>$flag),array('quarters_id'=>$quarters_id)))
        {
            $response = array('success' => TRUE, 'feedback' => '成功： 此项操作成功。');
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => '错误： 操作失败。');
        }

		$this->response($response, REST_Controller::HTTP_OK);
    }

    /**
     * 删除岗位
     *
     * @access public
     * @param $quarters_id
     * @return string
     */
    public function quarters_delete($quarters_id = '')
    {
        $quarters_id = $this->delete('quarters_id');
		//验证是否可删。。
		
        if ($this->quarters->delete(array('quarters_id'=>$quarters_id)))
        {
            $response = array('success' => TRUE, 'feedback' => '成功： 此项操作成功。');
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => '错误： 操作失败。');
        }

		$this->response($response, REST_Controller::HTTP_OK);
    }

    /**
     * 获取全部岗位
     *
     * @access public
     * @param $quarters_id
     * @return string
     */
    private function quarters_tree()
    {
        $quarters = $this->quarters->get_all(array('status'=>1),'*','quarters_id','asc');
        $user_id = $this->get('user_id');
        $user_id = intval($user_id);
        $this->load->model('User_to_quarters_model','user_to_quarters');
        $user_quarters = $this->user_to_quarters->get_all(array('user_id'=>$user_id));
        $user_quarters_arr = array();
        if(!empty($user_quarters)){
            foreach($user_quarters as $val){
                $user_quarters_arr[] = $val['quarters_id'];
            }
        }
        $result = array();
        if ($quarters !== NULL)
        {
            foreach($quarters as $q)
            {
                $result[] = array(
                    'id' => $q['quarters_id'],
                    'text' => $q['quarters_name'],
                    'leaf' => true,
                    'checked' => in_array($q['quarters_id'],$user_quarters_arr)?true:false,
                    );     
            }
        }
        return $result;
    }

}
