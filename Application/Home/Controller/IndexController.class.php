<?php
namespace Home\Controller;
use Think\Controller;
use Org\Util\ExcelToArrary;
class IndexController extends Controller {
    public function index(){
        $count = 0;
        if(S('excel_data')){//上一次的上传任务还没有完成
            $count = count(S('excel_data'));
        }
        $this->excel_count = $count;
        $this->display('test');
    }

    public function upload(){
        $this->writeExcelData(S('excel_data'));
    }

    public function add()
    {
        C('UPLOAD_DIR','./upload/');
        $tmp_file = $_FILES ['file_stu'] ['tmp_name'];
        $file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
        $file_type = $file_types [count ( $file_types ) - 1];

        /*判别是不是.xls文件，判别是不是excel文件*/
        if (strtolower ( $file_type ) != "xlsx" && strtolower ( $file_type ) != "xls")
        {
            $this->error ( '不是Excel文件，重新上传' );
        }

        /*设置上传路径*/
        $savePath = C('UPLOAD_DIR');

        /*以时间来命名上传的文件*/
        $str = date ( 'Ymdhis' );
        $file_name = $str . "." . $file_type;

        /*是否上传成功*/
        if (! copy ( $tmp_file, $savePath . $file_name ))
        {
            $this->error ( '上传失败' );
        }
        $ExcelToArrary=new ExcelToArrary();//实例化
        $res=$ExcelToArrary->read(C('UPLOAD_DIR').$file_name,"UTF-8",$file_type);//传参,判断office2007还是office2003
        /*删除刚才上传的缓存*/
        S('excel_data_true',null);
        S('excel_data_false',null);
        foreach ( $res as $k => $v ) //循环excel表
        {
            $data = array(
                $k => array(
                    'a' => $v [0] ? :'',
                    'b' => $v [1] ? :'',
                    'c' => $v [2] ? :'',
                    'd' => $v [3] ? :'',
                    'e' => $v [4] ? :'',
                )
            );

            /*验证通过则写入缓存*/
            if($this->validate($data[$k])){
                S('excel_data_true',S('excel_data_true')?S('excel_data_true')+$data:$data);
            }else{
                S('excel_data_false',S('excel_data_false')?S('excel_data_false')+$data:$data);
            }
        }

        S('excel_data',S('excel_data')?S('excel_data')+S('excel_data_true'):S('excel_data_true'));//把成功通过验证的数据写入缓存

        if(S('excel_data_false')){//如果有未通过验证的数据
            $this->redirect('index/download');
        }

        $this->writeExcelData(S('excel_data'));
    }

    public function download()
    {
        $this->data_true_length = count(S('excel_data_true'));
        $this->data_false_key = array_keys(S('excel_data_false'));
        $this->display('download');
    }

    public function downloadExcel()
    {

    }

    public function d()
    {
        $this->ddd();
        $this->redirect('index/index');
    }

    private function writeExcelData($data)
    {
        $test=M('test');//M方法

        $result=$test->addAll(array_values($data));//addAll方法要求数组必须有0索引
        if(! $result)
        {
            $this->error('导入数据库失败');
            exit();
        }
        else
        {
            $this->success ( '导入成功' );
            /*导入成功清除缓存*/
            $this->ddd();
        }
    }

    private function ddd()
    {
        S('excel_data',null);
        S('excel_data_true',null);
        S('excel_data_false',null);
    }

    private function validate($data)
    {
        foreach($data as $v){
            if($v == '')
            {
                return false;
            }
        }
        return true;
    }
}