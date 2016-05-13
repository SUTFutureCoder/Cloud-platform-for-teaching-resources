<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 16-5-13
 * Time: 上午8:38
 *
 * 添加课程
 *
 */
class Admin_add_course extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->load->library('session');
        

        $this->load->view('admin_add_course_view');
    }

}


