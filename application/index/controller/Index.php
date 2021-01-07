<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\facade\Cache;
use think\Request;
use think\Facade\Cookie;
use think\Facade\Session;
use PHPExcel;

use PHPExcel_IOFactory;
class Index extends Controller
{
    //登录界面
    public function index()
    {
        return $this->fetch('login');
    }

    //判断登录
    public function login(request $request)
    {
        $user_name = $request->param('user_name');
        $password = $request->param('password');
        $password = md5($password);
        $is_user = Db::table('user')->where('user_name', $user_name)->find();
        if ($is_user) {
            if ($password == $is_user['password']) {
                //登录成功
                Cookie::set('id', $is_user['id']);
                return json(['status' => 'success', 'msg' => '登录成功']);
            } else {
                return json(['status' => 'error', 'msg' => '密码错误']);
            }
        } else {
            return json(['status' => 'error', 'msg' => '用户名不存在']);
        }
    }

    //退出登录
    public function loginout()
    {
        Cookie::set('id', null);
        return $this->index();
    }

    //后台首页
    public function addTheme()
    {
        return $this->fetch('index');
    }

    //主题列表
    public function ThemeList()
    {
        return $this->fetch('theme-list');
    }

    //添加主题
    public function product_add()
    {
        return $this->fetch('product-add');
    }

    //上传图片资源
    public function uploadimg(Request $request)
    {
        $file = $request->file('file');

        return json(['msg' => 'success', 'url' => '111']);
    }

    //主题图标上传
    public function addicon(Request $request)
    {
        $file = $request->file('file');
        $info = $file->move('../public/uploads/icons');
        //文件路径
        $filename = $info->getSaveName();
        Session::set('icon', $filename);

    }
    //主题资源包上传
    public  function  addpackage(Request $request){
        $file = $request->file('file');
        $info = $file->move('../public/uploads/package');
        //文件路径
        $filename = $info->getSaveName();
        Session::set('package', $filename);
    }
    //主题预览图片上传
    public  function  addpics(Request  $request){
        $file = $request->file('file');
        $info = $file->move('../public/uploads/pics');
        //文件路径
        $filename = $info->getSaveName();
        $file=Session::get('pics');
        $files=$file.','.$filename;
        Session::set('pics', $files);
    }
    //提交主题
    public function submit_theme(Request $request)
    {
        $data=array(
            'version'=>$request->param('version'), //版本号
            'name'=>$request->param('name'),  //主题名称
            'icon'=>Session::get('icon'),
            'pics'=>Session::get('pics'),
           'package'=>Session::get('package'),
           'addtimes'=>date('Y-m-d H:i:s')
        );
        if(isset($data)){

        }
        Session::clear();
        Db::table('themes')->insert($data);
    }

//物品管理excel
 public function data(){
    $res=Db::table('wp_goods_belong')
         ->alias('a1')
         ->join('wp_user a2','a1.userid=a2.userid')
         ->join('wp_goods a3','a1.gid=a3.id')
         ->field('a1.id,a1.userid,a2.name,a3.title,a1.bianhao,a1.xinghao,a1.neicun,a1.banben,a1.xitong,a1.note,a1.usingtime,a1.status') 
         ->where('a1.status',3)
         ->select()
    ;
   echo  Db::getLastSql();
 }
 //物品管理日志excel
 public function data_log(){
    $res=Db::table('wp_log_goods')
         ->alias('a1')
         ->join('wp_user a2','a1.userid=a2.userid')
         ->join('wp_user a3','a1.need_userid=a3.userid')
         ->field('a1.id,a2.name,a3.name as need_name,a1.note,a1.time,a1.status') 
         ->select();
   echo  Db::getLastSql();
 }
//禅道日志
 public function log(){
 $res=Db::table('zt_effort')
     ->alias('e')
     ->where('date','between','2020-04-01,2020-04-30')
     ->join('zt_user u','e.account=u.account')
     ->join('zt_dept d','d.id=u.dept')
    /* ->join('zt_product pt','e.product=pt.id')*/
     ->join('zt_project pj','e.project=pj.id')
     
     ->field('e.id,e.date,u.realname,e.work,e.consumed,e.left,pj.name')
     ->select();
     dump($res);
    /* echo Db::table('zt_bug') ->getLastSql();*/
    

 }
 public function email(){
   $res= Db::table('wp_user')->select();
    //dump($res);
  
   foreach ($res as $key => $value) {
     $isset=Db::table('zentao_user')->where('realname',$value['name'])->find();
     if($isset){
          Db::table('wp_user')->where('name',$isset['realname'])->update(['email'=>$isset['email']]);
     }
   }
 }
    public function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $expTitle;//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);

        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

        $objPHPExcel->getActiveSheet(0)->mergeCells('B1:'.$cellName[$cellNum-1].'1');//合并单元格
        // 合并
        for($x=3;$x<=5800;$x++){
            $objPHPExcel->getActiveSheet()->mergeCells('B'.$x.':Z'.$x);
        }


        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
  //数据比对

   //导出bug数据
   public  function  bugdata(){

    $res=Db::table('zt_bug')
        ->alias('b')

        ->where('b.product','in','36')
        //->where('b.id',8674)
        ->leftJoin('zt_action a','b.id=a.objectID')
        ->where('a.objectType','in','bug,problem,effort')
        ->where('b.deleted','0')
        ->field('b.id,a.date,a.actor,a.action,a.extra,a.comment')
        //->field('b.id')
        ->order('b.id desc')
        ->select();


       //$res=array_unique($res, SORT_REGULAR);
       //dump($res);die;
       //echo Db::getLastsql();die;
       $data=array();
      foreach($res as $k=>$v){
          $data[$k]=array(
              'id'=>$v['id'],
              'msg'=>$this->action($v['action'],$v['date'],$v['actor'],$v['extra'])
                  ."\n".$v['comment']
          );

      }
       //dump($data);die;
       $result=array();
    foreach($data as $k=>$v){
        $result[$v['id']][]=$v;
    }
       //dump($result);
       $json=array();
      foreach($result as $k=>$v){
          foreach($v as $key=>$value){
              $json[$k][$key] =$key.'. '.$value['msg'];
              //dump($value);die;
          }
      }
       //dump($json);die;
       foreach($json as $k=>$v){
           unset($json[$k][0]);
       }
       //dump($json);die;
       $bugs=array();
     foreach($json as $k=>$v){
         $data=implode("\n",$v);
         $bugs[$k]=$data;
     }
       $argc=array();
    $bugs=array_values($bugs);
       foreach($bugs as $k=>$v){
           $argc[$k]['pid']=$k;
           $argc[$k]['res']=$v;
       }
      // dump($argc);die;
       $xlsName  = "bugs";
       $xlsCell  = array(
           array('pid','序列'),
           array('res','数据')
       );

       $this->exportExcel($xlsName,$xlsCell,$argc);

       //dump($bugs);


   }

 public  function  action($type,$date,$actor,$extra){
     $arr=array(
         'opened'=> $date.', 由'.$actor.' 创建。' ,
        'created'=> $date.', 由'.$actor.' 创建。' ,
         'changed'=>$date.', 由'.$actor.' 变更。' ,
         'edited'=>$date.', 由'.$actor.' 编辑。' ,
         'assigned'=>$date.',由'.$actor.' 指派给'.$extra.'。' ,
         'closed'=>$date.', 由'.$actor.' 关闭。' ,
         'deleted'=>$date.', 由'.$actor.' 删除。' ,
         'deletedfile'=>$date.', 由'.$actor.' 删除了附件<i>'.$extra.'</i>。' ,
         'editfile'=>$date.', 由'.$actor.' 编辑了附件<i>'.$extra.'</i>。',
         'erased'=> $date.', 由'.$actor.' 删除。' ,
         'undeleted'=> $date.', 由'.$actor.' 还原。' ,
         'hidden'=>$date.', 由'.$actor.' 隐藏。' ,
         'commented'=>$date.', 由'.$actor.' 添加备注。',
          'activated'=>$date.', 由'.$actor.' 激活。',
         'blocked'=>$date.', 由'.$actor.' 阻塞。' ,
         'moved'=>$date.', 由'.$actor.' 移动，之前为 "'.$extra.'"。' ,
         'confirmed'=>$date.', 由'.$actor.' 确认需求变动，最新版本#'.$extra.'。' ,
         'caseconfirmed'=>$date.', 由'.$actor.' 确认用例变动，最新版本#'.$extra.'。',
         'bugconfirmed'=>$date.', 由'.$actor.' 确认Bug。',
         'frombug'=>$date.', 由'.$actor.' Bug转化而来，Bug编号为'.$extra.'。',
         'buganalysis'  =>$date.', 由'.$actor.' 分析了 BUG',
         'linked2bug'  =>$date.',由'.$actor.' 关联到版本'.$extra.'',
         'recordestimate' => $date.', 由'.$actor.' 记录工时，消耗'.$extra.' 小时。',
         'resolved'=> $date.', 由'.$actor.' 解决。,',
         'linkrelatedbug'=>$date.', 由'.$actor.' 关联了相关Bug。,',
         'unlinkrelatedbug'=>$date.', 由'.$actor.' 移除了相关Bug。,'
     );
  return $arr[$type];



  }
  public  function  sql(){
     Db::table('wp_class')
           ->where('gid','<',216)
           ->delete();

  }
   public  function   wp_log(){
       $id=Db::table('wp_goods')
            ->where('cid',40)
            ->field('id')
            ->select();
       //获取到对应分类的物品id
       $ids=array();
       foreach($id as $key=>$value){
           $ids[$key]=$value['id'];
       }
      Db::table('wp_goods_belong')
           ->where('gid','in',$ids)
           ->delete();

   }
   //更新物品责任人
    public function wp_user(){
        $user=Db::table('wp_user')
             ->field('name,userid')
             ->select();
        $users=array();
        foreach($user as $key=>$value){
           $users[$value['name']]=$value['userid'];
        }
       //dump($users['李志立']);die;
        $res=Db::table('excel')
            ->select();
        foreach($res as $key=>$value){
            Db::table('wp_goods_belong')
               ->where('bianhao',$value['bianhao'])
               ->data(['userid'=>$users[$value['user']],'usingtime'=>$value['time'],'note'=>$value['note']])
               -> update();
        }

    }
    //更新分类归属
    public   function  sort(){
      $res=Db::table('excel')
         ->select();
        //dump($res);

      foreach($res as $key=>$value){
        $goods_id=Db::table('wp_goods')
            ->where('title',$value['name'])
            ->field('id')
            ->find();
         // dump($goods_id['id']);
       Db::table('wp_goods_belong')
           ->where('bianhao',$value['bianhao'])
           ->data(['gid'=>$goods_id['id']])
           ->update();
      }
    }
    public  function  wj(){
       Cache::store('redis')->set('wj',12345);
    }
    public  function  kpi(){
        $res=Db::table('zt_kpitotal')
            ->where('account','in','shiguoren,hangqi,chenxiaoxia,hejun,xiehuan,liangchao')
            ->where('edittime','between','2020-11-01,2020-12-31')
            ->select();

        foreach($res as $key=>$value){
            //dump($value);die;
           if(strpos($value['type'],'task')!==false){
               $arr['task'][]=$value;
           }elseif(strpos($value['type'],'bug')!==false){
               $arr['bug'][]=$value;
           }
        }
        //dump($arr);die;
        //dump($arr['task']);
        $tasks=array();
        foreach($arr['bug'] as $key=>$value){
            $task=Db::table('zt_bug')->where('id',$value['targetID'])->find();
            //dump($task);die;
            if($task['deleted']=='1'){
                $tasks[]=$value['targetID'];
            }
        }
        $tasks_arr=implode(" ",$tasks);
        echo $tasks_arr;
    }
    public function baidu(){
      $urls = array(
    'http://www.citos.cn/',    
);
$api = 'http://data.zz.baidu.com/urls?site=www.citos.cn&token=3RNfoNsob7xDEFvL';
$ch = curl_init();
$options =  array(
    CURLOPT_URL => $api,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => implode("\n", $urls),
    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
); 

    curl_setopt_array($ch, $options);
   $result = curl_exec($ch);
   echo $result;
  
   
    }
}
