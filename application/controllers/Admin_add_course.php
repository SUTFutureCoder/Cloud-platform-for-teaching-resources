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

    const BOS_USER   = 14604488844;
    const BOS_BUCKET = 146111988812;

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->load->library('session');
        $this->load->library('authorizee');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'course_add')){
            header("Content-type: text/html; charset=utf-8");
            echo '<script>alert("抱歉，您的权限不足");window.location.href="' . base_url() . '";</script>';
            return 0;
        }

        $this->load->view('admin_add_course_view');
    }



    public function setCourse(){
        $this->load->library('session');
        $this->load->library('authorizee');
        $this->load->library('util/Uuid');

        if (!$this->authorizee->CheckAuthorizee($this->session->userdata('user_role'), 'course_add')){
            echo json_encode(array('code' => -1, 'error' => '抱歉，您的权限不足'));
            exit;
        }

        $tempCourseName  = $this->input->post('course_name', true);
        if (empty($tempCourseName) || isset($tempCourseName[1024])){
            echo json_encode(array('code' => -2, 'error' => '抱歉，传入的课程名称为空或超过1024个字符'));
            exit;
        }

        $tempCourseIntro = $this->input->post('course_intro', true);
        if (empty($tempCourseIntro) || isset($tempCourseIntro[1024])){
            echo json_encode(array('code' => -3, 'error' => '抱歉，传入的课程介绍为空或超过1024个字符'));
            exit;
        }

        $tempLevelSum    = $this->input->post('course_level_sum', true);
        if (empty($tempLevelSum)){
            echo json_encode(array('code' => -4, 'error' => '抱歉，课程节数不能为空'));
            exit;
        }


        $tempLevel       = $this->input->post('course_level', true);
        if (empty($tempLevel) || $tempLevelSum != count($tempLevel)){
            echo json_encode(array('code' => -5, 'error' => '抱歉，课程节数有误'));
            exit;
        }

        //专辑uuid
        $clean['lesson_group_id'] = Uuid::genUUID(CoreConst::COURSE_UUID);

        if ('on' == $this->input->post('course_is_private', true)){
            $clean['lesson_is_private'] = 1;
        } else {
            $clean['lesson_is_private'] = 0;
        }

        //确定必须的文件是否上传
        for ($i = 1; $i <= $tempLevelSum; ++$i){
            if (empty($_FILES['course_res_upload']['name'][$i]) || 0 != $_FILES['course_res_upload']['error'][$i]){
                echo json_encode(array('code' => -6, 'error' => '抱歉，第' . $i . '个文件上传失败，请重新上传'));
                exit;
            }
        }

        //合并两个列表并上传资源
        $arrCourseResUrl = $this->uploadRes($_FILES['course_res_upload'], $_FILES['course_res_attach_upload']);

        //准备批量插入数据
        $arrLessonListToInsert[0] = array(
            'lesson_name'  => $tempCourseName,
            'lesson_id'    => Uuid::genUUID(CoreConst::COURSE_UUID),
            'lesson_intro' => $tempCourseIntro,
            'lesson_group_id'  => $clean['lesson_group_id'],
            'lesson_is_private' => $clean['lesson_is_private'],
            'user_id'      => $this->session->userdata('user_id'),
            'user_name'    => $this->session->userdata('user_name'),
            'lesson_ctime' => time(),
        );
        foreach ($tempLevel as $key => $tempLevelValue){
            $arrLessonListToInsert[$key] = array(
                'lesson_name' => $tempLevelValue['name'],
                'lesson_id'   => Uuid::genUUID(CoreConst::COURSE_UUID),
                'lesson_group_id'   => $clean['lesson_group_id'],
                'lesson_res'        => json_encode($arrCourseResUrl[$key]['main']),
            );
            if (isset($arrCourseResUrl[$key]['attach'])){
                $arrLessonListToInsert[$key]['lesson_res_attach'] = json_encode($arrCourseResUrl[$key]['attach']);
            }
        }
        //写入数据库
        $this->load->model('Course_model');
        $ret = $this->Course_model->addCourse($arrLessonListToInsert);

        print_r($_POST);
    }

    /**
     * 将文件列表合并并上传到BOS服务，返回url
     *
     *
     * @param $mainRes
     * @param $attachRes
     * @return mixed
     * @throws MException
     */
    private function uploadRes($mainRes, $attachRes){
        $this->load->library('util/BosClient');
        $arrBosConfig = $this->config->item('bos_bucket_list');
        $arrBosConfig = $arrBosConfig[self::BOS_BUCKET];

        //开始上传并记录结果
        foreach ($mainRes['name'] as $key => $mainResValue){
            //上传主资源
            $arrBosRet = BosClient::putObjectFromFile(self::BOS_BUCKET, $arrBosConfig['secret_key'], $mainRes['tmp_name'][$key], $mainRes['name'][$key]);
            if (0 != $arrBosRet['code']){
                //出错
                throw new MException(CoreConst::MODULE_BOS, ErrorCodes::ERROR_BOS_UPLOAD_ERROR);
            }

            //处理主资源
            $arrResRet[$key]['main'] = array(
                'url'  => $arrBosRet['data']['url'],
                'name' => $mainRes['name'][$key],
            );

            if (isset($attachRes['name'][$key]) && is_array($attachRes['name'][$key])){
                foreach ($attachRes['name'][$key] as $attachKey => $attachValue){
                    //上传附加资源
                    $arrBosRet = BosClient::putObjectFromFile(self::BOS_BUCKET, $arrBosConfig['secret_key'], $attachRes['tmp_name'][$key][$attachKey], $attachRes['name'][$key][$attachKey]);
                    if (0 != $arrBosRet['code']){
                        //出错
                        throw new MException(CoreConst::MODULE_BOS, ErrorCodes::ERROR_BOS_UPLOAD_ERROR);
                    }

                    //处理附加情况
                    $arrResRet[$key]['attach'][] = array(
                        'url'  => $arrBosRet['data']['url'],
                        'name' => $attachRes['name'][$key][$attachKey],
                    );
                }
            }
        }
        return $arrResRet;
    }
}


