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

    const TABLE_NAME = 'lesson';

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
        foreach ($courseInfo as $key => $courseInfoValue){
            if (0 == $key && 1 == $courseInfoValue['lesson_is_private']){
                //向私有学校表填入数据
                $this->load->library('session');
                $arrPrivateSchool = json_decode($this->session->userdata('user_school'), true);
                if (is_array($arrPrivateSchool)){
                    foreach ($arrPrivateSchool as $arrPrivateSchoolData){
                        $this->db->insert('lesson_school', array(
                            'lesson_group_id' => $courseInfoValue['lesson_group_id'],
                            'school_name'     => $arrPrivateSchoolData,
                        ));
                    }
                }
            }

            $this->db->insert(self::TABLE_NAME, $courseInfoValue);
        }
        $this->db->trans_complete();
        if (!$this->db->trans_status()){
            return false;
        } else {
            return true;
        }
    }

    /**
     * 非普通用户则能看到全部
     *
     * @param $normalUser
     * @param $userSchool
     */
    public function getCourseList($normalUser, $userSchool = array()){
        $this->load->database();

        $this->db->where('lesson_level',      0);
        $this->db->where('lesson_is_deleted', 0);

        if ($normalUser){
            $strWhere = ' (lesson_is_private = 0 OR (lesson_is_private = 1 AND lesson_school.school_name IN ("' . implode('","', $userSchool) . '")))';
            $this->db->where($strWhere);
        }

        $this->db->order_by('lesson_ctime', 'desc');
        return $this->db->get(self::TABLE_NAME)->result_array();
    }

    /**
     * 获取课程信息
     *
     * @param $intGroupId
     * @param $intLevelId
     * @param $normalUser
     * @param array $userSchool
     */
    public function getCourseInfo($intGroupId, $intLevelId, $normalUser, $userSchool = array()){
        $this->load->database();

        //1.获取课程基本信息
        $this->db->where('lesson_group_id', $intGroupId);
//        $this->db->where('(lesson_level = 0 OR lesson_level = ' . $intLevelId . ')');
        $this->db->order_by('lesson_level');

        $courseInfo = $this->db->get(self::TABLE_NAME)->result_array();

        if (empty($courseInfo)){
            return false;
        }

        //2.获取课程私有情况
        if (0 == $courseInfo[0]['lesson_level'] && 1 == $courseInfo[0]['lesson_is_private'] && $normalUser){
            $this->db->where('school_name IN ("' . implode('","', $userSchool) . '")');
            if (0 == ($arrPrivateSchoolList = $this->db->get('lesson_school')->num_rows())){
                return false;
            }
        }

        return $courseInfo;
    }

}