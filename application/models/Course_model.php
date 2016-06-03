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
     * @param $page
     * @param $limit
     */
    public function getCourseList($normalUser, $userSchool = array(), $page = 0, $limit = 20){
        $this->load->database();

        $this->db->select('l.lesson_group_id, l.lesson_image, l.lesson_name, l.user_name, l.lesson_is_private, l.lesson_is_deleted, l.lesson_ctime');
        $this->db->from('lesson as l');
        $this->db->where('l.lesson_level', 0);
        $this->db->where('l.lesson_is_deleted', 0);
        $this->db->limit($limit, $page * $limit);

        if ($normalUser){
            $this->db->join('lesson_school s', 'l.lesson_group_id=s.lesson_group_id', 'left');
            $strWhere = '(l.lesson_is_private = 0 OR (l.lesson_is_private = 1 AND s.school_name IN ("' . implode('","', $userSchool) . '")))';
            $this->db->where($strWhere);
        }

        $this->db->order_by('l.lesson_ctime', 'desc');
        return $this->db->get()->result_array();
    }

    /**
     * 获取课程信息
     *
     * @param $intGroupId
     * @param $intLevelId
     * @param $normalUser
     * @param array $userSchool
     * @return bool
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

        //没有这个level
        if (count($courseInfo) < ($intLevelId + 1)){
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


    /**
     * 模糊搜索课程
     *
     * @param $searchCourse
     * @param $arrSchoolName
     * @param bool $all 用于管理员后台无视学校名称，显示全部
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function searchCourse($searchCourse, $arrSchoolName, $all = false, $page = 0, $limit = 20){
        $this->load->database();

        $this->db->select('l.lesson_id, l.lesson_group_id, l.lesson_level, l.lesson_name, l.lesson_intro, l.lesson_is_private, l.lesson_is_deleted, l.lesson_ctime, l.user_name');
        $this->db->from('lesson as l');
        $this->db->where('l.lesson_is_deleted', 0);
        if (false === $all){
            $this->db->where('(l.lesson_is_private = 0 OR (l.lesson_is_private = 1 AND lesson_school.school_name IN ("' . implode('","', $arrSchoolName) . '")))');
            $this->db->join('lesson_school', 'lesson_school.lesson_group_id=l.lesson_group_id', 'left');
        }
        $this->db->like('l.lesson_name', $searchCourse, 'both');
        $this->db->limit($limit, $page * $limit);

        $arrCourseRet = $this->db->get()->result_array();

        return $arrCourseRet;
    }

    /**
     * 根据课程组id列表获取level 0主课程
     *
     * 请务必在筛选一圈后使用
     *
     * @param $courseGroupIdList
     */
    public function getMainCourse($courseGroupIdList){
        $this->load->database();

        $this->db->where_in('lesson_group_id', $courseGroupIdList);
        $this->db->where('lesson_level',       0);

        return $this->db->get('lesson')->result_array();
    }

    /**
     * 通过课程组id进行删除整个课程
     *
     * 删除子课通过修改界面
     *
     * @param $intCourseGroupId
     */
    public function deleteCourse($intCourseGroupId){
        $this->load->database();
        $this->db->where('lesson_group_id', $intCourseGroupId);
        $this->db->update(self::TABLE_NAME, array('lesson_is_deleted' => $intCourseGroupId));

        return $this->db->affected_rows();
    }
}