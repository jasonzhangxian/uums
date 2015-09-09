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
    	$this->db->where(array('quarters.status'=>1));
    	$this->db->where(array('privilege.status'=>1));
    	if($sys_code)
    		$this->db->where(array('privilege.sys_code'=>$sys_code));
    	$data = $this->db->get()->result_array();
    	return ($data);
    }
    /**
    * Get the settings
    * Don't retrieves the language depending settings
    *
    * @return  The settings array
    */
    public function get_settings($username)
    {
        $Qsettings = $this->get_one(array('username' => $username),'user_settings');

        if (count($Qsettings) > 0) {
            $settings = unserialize($Qsettings['user_settings']);

            return $settings;
        }

        return FALSE;
    }

    public function save_settings($username, $data)
    {
        $settings = $this->get_settings($username);

        if (is_array($data) && !empty($settings['desktop']))
        {
            if(serialize($settings['desktop']) == serialize($data))
                return TRUE;
            else
                $settings['desktop'] = array_merge($settings['desktop'] ,$data);
        }
        else
        {
            $settings['desktop'] = $data;
        }

        $update_data = array('user_settings' => serialize($settings));

        $affected_rows = $this->update($update_data,array('username' => $username));

        if ($affected_rows > 0)
        {
            return TRUE;
        }

        return FALSE;
    }    
    /**
     * Get the user levels
     *
     * @access public
     * @param $user_id
     * @return array
     */
    public function get_user_levels($user_id)
    {
        $modules = array();
        
        $result = $this->get_one(array('user_id' => $user_id),'module');
        
        if (count($result) > 0)
        {
            $modules[]= $result['module'];
        }
        
        
        if ( in_array('*', $modules) )
        {
            $modules = array();
            
            $access_DirectoryListing = directory_map(APPPATH . 'modules', 1);
            if(empty($access_DirectoryListing))
                return $modules;
            foreach($access_DirectoryListing as $file)
            {
                $modules[] = substr($file, 0, strrpos($file, '.'));
            }
        }
        
        return $modules;
    }
}

/* End of file access.php */
/* Location: ./application/models/access.php */
