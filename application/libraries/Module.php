<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Module
{
    /**
     * Cached data
     *
     * @access private
     * @var object
     */
    private $_ci = NULL;

    /**
     * Menu Group
     *
     * @access protected
     * @var string
     */
    protected $_group = 'misc';

    /**
     * Icon
     *
     * @access protected
     * @var string
     */
    protected $_icon = 'configure.png';

    /**
     * Title
     *
     * @access protected
     * @var string
     */
    protected $_title;

    /**
     * Module
     *
     * @access protected
     * @var string
     */
    protected $_module;

    /**
     * Sort Order
     *
     * @access protected
     * @var int
     */
    protected $_sort_order = 0;

    /**
     * Sub Group
     *
     * @access protected
     * @var int
     */
    protected $_subgroups;

    /**
     * Default constructor
     *
     * @access public
     */
    public function __construct()
    {
        // Set the super object to a local variable for use later
        $this->_ci = & get_instance();

        //get directory helpder
        $this->_ci->load->helper('directory');

        //get access model
        $this->_ci->load->model('admin_user_model','admin_user');

        //get admin data
        $admin_data = $this->_ci->session->userdata('admin_data');

        //get admin id
        $this->_user_id = $admin_data['user_id'];
    }


    /**
     * Get all modules
     *
     * @access public
     * @return array
     */
    public function get_levels()
    {
        $module = array();

        $this->_ci->load->helper('directory');

        $modules = $this->_ci->admin_user->get_user_levels($this->_user_id);

        $module = array();
        foreach($modules as $mod)
        {
            if ( file_exists(APPPATH . 'modules/' . $mod . '.php') )
            {
                $module_class = 'Module_' . ucfirst($mod);

                if ( !class_exists( $module_class ) ) {
                    include(APPPATH . 'modules/' . $mod . '.php');
                }

                $module_obj = new $module_class();

                $data = array('module' => $mod,
                              'icon' => $module_obj->get_icon(), 
                              'title' => $module_obj->get_title(), 
                              'subgroups' => $module_obj->get_sub_groups());

                if ( !isset( $module[$module_obj->get_group()][$module_obj->get_sort_order()] ) )
                {
                    $module[$module_obj->get_group()][$module_obj->get_sort_order()] = $data;
                }
                else
                {
                    $module[$module_obj->get_group()][] = $data;
                }
            }
        }

        ksort($module);
        foreach ( $module as $group => $links )
        {
            ksort($module[$group]);
        }

        return $module;
    }

    /**
     * Get group title
     *
     * @access public
     * @param $group
     * @return string
     */
    public function get_group_title($group)
    {
        $this->_ci->lang->load('uums');

        return $this->_ci->lang->line('group_title_'.$group);
    }

    /**
     * Get group title
     *
     * @access public
     * @return string
     */
    public function get_module()
    {
        return $this->_module;
    }

    /**
     * Get group
     *
     * @access public
     * @return string
     */
    public function get_group()
    {
        return $this->_group;
    }

    /**
     * Get icon
     *
     * @access public
     * @return string
     */
    public function get_icon()
    {
        return $this->_icon;
    }

    /**
     * Get title
     *
     * @access public
     * @return string
     */
    public function get_title()
    {
        return $this->_title;
    }

    /**
     * Get sort order
     *
     * @access public
     * @return string
     */
    public function get_sort_order()
    {
        return $this->_sort_order;
    }

    /**
     * Get sub groups
     *
     * @access public
     * @return string
     */
    public function get_sub_groups()
    {
        return $this->_subgroups;
    }
}
