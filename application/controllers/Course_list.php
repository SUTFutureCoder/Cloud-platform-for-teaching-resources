<?php
/**
 * 用于显示课程列表及搜索结果
 *
 * Created by PhpStorm.
 * User: lin
 * Date: 16-6-3
 * Time: 上午11:36
 */
class Course_list extends CI_Controller{

    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $this->load->library('session');
        $this->load->model('Course_model');

        $intPage  = $this->input->get('page',  true) ? $this->input->get('page',  true) : 0;
        $intLimit = $this->input->get('limit', true) ? $this->input->get('limit', true) : 20;

        $arrSchoolList = array();
        if ($this->session->userdata('user_school')){
            $arrSchoolList = json_decode($this->session->userdata('user_school'), true);
        }

        //获取get
        $strLessonSearch = $this->input->get('lesson_search', true);
        if (!empty($strLessonSearch)){
            $arrCourseList = array();
            $arrCourseRet  = $this->Course_model->searchCourse($strLessonSearch, $arrSchoolList, false, $intPage, $intLimit);
            //合并列表
            foreach ($arrCourseRet as $arrCourseData){
                //以group_id为key
                $arrCourseList[$arrCourseData['lesson_group_id']][$arrCourseData['lesson_level']] = $arrCourseData;
            }

            //需要补充获取level0列表，即上面的查询没有查到level 0主课内容
            $arrGetLevel0List   = array();
            foreach ($arrCourseList as $intGroupId => $arrCourseGroupDataData){
                if (!isset($arrCourseGroupDataData[0])){
                    $arrGetLevel0List[] = $intGroupId;
                }
            }

            //合并列表
            if (!empty($arrGetLevel0List)){
                $arrRetLevel0 = $this->Course_model->getMainCourse($arrGetLevel0List);
                foreach ($arrRetLevel0 as $arrRetLevel0Data){
                    $arrCourseList[$arrRetLevel0Data['lesson_group_id']][0] = $arrRetLevel0Data;
                }
            }

            //查询标记
            $boolSearch = true;
        } else {
            $arrCourseList = $this->Course_model->getCourseList(1, $arrSchoolList, $intPage, $intLimit);
            $boolSearch = false;
        }

        $this->load->view('course_list_view', array(
            'bool_search' => $boolSearch,
            'course_list' => $arrCourseList,
        ));
    }
}