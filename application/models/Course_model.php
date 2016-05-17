<?php
/**
 * 课程相关数据库交互
 *
 * Created by PhpStorm.
 * User: lin
 * Date: 16-5-16
 * Time: 下午8:26
 */
class Course_model extends CI_Model{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 向数据库中添加课程
     *
     * @param $courseInfo
     * @return bool
     */
    public function addCourse($courseInfo){
        $this->load->database();
        //此处需要改进
        $this->db->trans_start();
        foreach ($courseInfo as $courseInfoValue){
            $this->db->insert('lesson', $courseInfoValue);
        }
        $this->db->trans_complete();
        if (!$this->db->trans_status()){
            return false;
        } else {
            return true;
        }

    }

}