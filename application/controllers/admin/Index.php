<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Index extends Admin_Controller
{
    /**
     * Constructor
     *
     * @access public
     * @param string
     */
    public function __construct()
    {
        parent::__construct();
		$this->load->library('desktop_setting');
    }

    /**
     * Default Controller
     * 
     * @access public
     * @return string
     */
    public function index()
    {
        //If it is an ajax request, get the ajax response and add it into the output
        $module = $this->input->get_post('module');
        $action = $this->input->get_post('action');
        if ((!empty($module) && !empty($action)) || (!empty($action)))
        {
            $response = $this->ajax($module, $action);
            
            if (!empty($response)) {
              $this->set_output($response);
            }
        }
        else
        {
            if ($this->admin->is_logged_on())
            {
                $data = array();
				$this->twig->render('index');
            }
            else
            {
                redirect('admin/login');
            }
        }
    }

    /**
     * Handle ajax request generated by extjs component
     *
     * Run the action in one module and the response must be an array
     *
     * @access  private
     * @return  array
     */

    public function ajax($module, $action)
    {
       // $this->lang->ini_load($module . '.php');

        //$this->lang->db_load('modules-boxes');
        //$this->lang->db_load('modules-content');

        if (!empty($module) && !empty($action))
        {
            require_once 'system/modules/' . $module . '/controllers/' . $module . '.php';
            $this->$module = new $module();

            //$this->load->module($module);

            if (is_callable(array($this->$module, $action)))
            {
                $response = $this->$module->$action();
            }
        }
        else
        {
            $action = $this->input->get_post('action');

            if (method_exists($this, $action))
            {
                $response = $this->$action();
            }
        }

        return $response;
    }

    /**
     *
     */
    public function desktop()
    {
		$data = array();
		$data['username'] = $this->desktop_setting->get_username();
        $data['realname'] = $this->desktop_setting->get_realname();
        $data['modules'] = $this->desktop_setting->get_modules();
        $data['launchers'] = $this->desktop_setting->get_launchers();
        $data['styles'] = $this->desktop_setting->get_styles();
        $data['output'] = $this->desktop_setting->output_modules();
        $a = $this->twig->parse('desktop.tpl', $data, TRUE);
        echo $a;
    }

    // --------------------------------------------------------------------

    //Following Commands are used to handle the ajax requests generated from the desktop setting

    /**
     *
     */
    public function logoff()
    {
        $this->admin->reset();

        return array('success' => TRUE);
    }

    /**
     *
     */
    private function list_wallpapers()
    {
        $wallpapers = $this->desktop_setting->get_wallpapers();

        return array('wallpapers' => $wallpapers);
    }

    /**
     *
     */
    private function load_modules()
    {
        $desktop_Settings = $this->desktop_setting->get_settings();
         
        $modules = $this->desktop_setting->list_modules($desktop_Settings);

        return $modules;
    }

    /**
     *
     */
    private function save_settings()
    {
        $data = array();
        $response = array();

        $data['autorun'] = $this->input->post('autorun');
        $data['quickstart'] = $this->input->post('quickstart');
        $data['contextmenu'] = $this->input->post('contextmenu');
        $data['shortcut'] = $this->input->post('shortcut');

        $data['theme'] = $this->input->post('theme');
        $data['wallpaper'] = $this->input->post('wallpaper');
        $data['transparency'] = $this->input->post('transparency');
        $data['backgroundcolor'] = $this->input->post('backgroundcolor');
        $data['fontcolor'] = $this->input->post('fontcolor');
        $data['wallpaperposition'] = $this->input->post('wallpaperposition');

        $data['sidebaropen'] = $this->input->post('sidebaropen');
        $data['sidebartransparency'] = $this->input->post('sidebartransparency');
        $data['sidebarbackgroundcolor'] = $this->input->post('sidebarbackgroundcolor');
        $data['sidebargadgets'] = $this->input->post('sidebargadgets');

        if ( $this->desktop_setting->save_desktop($data) )
        {
            $response = array('success' => TRUE, 'feedback' => '成功： 此项操作成功。');
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => '错误： 操作失败。');
        }

        return $response;
    }

    /**
     * Load module view
     * 
     * @access public
     * @param module
     * @return string
     */
    public function load_module_view($module = NULL) 
    {
        $tmp = explode('-', $module);
        $module = $tmp[0];
		if($module){
			$output = $this->twig->parse($module . '/main.tpl');
			$this->output->set_output($output);
		}
    }

	public function cache_initialize()
	{
		if ( ! $data = $this->cache->get('admin_user_list'))
		{
			$this->load->model('admin_user_model','admin_user');
			$admin_user_list = $this->admin_user->get_all();
			$data = array();
			foreach($admin_user_list as $admin)
			{
				$data[$admin['user_id']] = $admin;
			}
			$this->cache->save('admin_user_list', $data, 28800);
		}
		if ( ! $data = $this->cache->get('new7_system_list'))
		{
			$this->load->model('new7_system_model','new7_system');
			$new7_system_list = $this->new7_system->get_all();
			$data = array();
			foreach($new7_system_list as $system)
			{
				$data[$system['sys_code']] = $system;
			}
			$this->cache->save('new7_system_list', $data, 28800);
		}
		if ( ! $data = $this->cache->get('privilege_list'))
		{
			$this->load->model('privilege_model','privilege');
			$privilege_list = $this->privilege->get_all();
			$data = array();
			foreach($privilege_list as $privilege)
			{
				$data[$privilege['privilege_id']] = $privilege;
			}
			$this->cache->save('privilege_list', $data, 28800);
		}
		print_d( $data );
	}
}
