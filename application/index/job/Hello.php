<?php
/**
 * Created by PhpStorm.
 * User: CitoS
 * Date: 2020/10/29
 * Time: 15:46
 */
namespace  app\index\job;
use think\Exception;
use think\facade\Log;
use think\Queue;
use think\Db;
use think\queue\Job;

Class Hello {

    public function fire(Job $job,$data)
    {
        //dump($job);die;
        // 有些消息在到达消费者时,可能已经不再需要执行了
        $isJobStillNeedToBeDone = $this->checkDatabaseToSeeIfJobNeedToBeDone($data);
        if(!$isJobStillNeedToBeDone){
            $job->delete();
            return;
        }

        $isJobDone = $this->doHelloJob($data);
        //$isJobDone='';
        if ($isJobDone) {
            // 如果任务执行成功， 记得删除任务
            $job->delete();
            print("<info>Hello Job has been done and deleted"."</info>\n");
        }else{
            if ($job->attempts() > 3) {
                //通过这个方法可以检查这个任务已经重试了几次了
                print("<warn>Hello Job has been retried more than 3 times!"."</warn>\n");

                $job->delete();

                // 也可以重新发布这个任务
                //print("<info>Hello Job will be availabe again after 2s."."</info>\n");
                //$job->release(2); //$delay为延迟时间，表示该任务延迟2秒后再执行

            }
        }
    }

    /**
     * 有些消息在到达消费者时,可能已经不再需要执行了
     * @param array|mixed    $data     发布任务时自定义的数据
     * @return boolean                 任务执行的结果
     */
    private function checkDatabaseToSeeIfJobNeedToBeDone($data){
        return true;
    }
    /**
     * 根据消息中的数据进行实际的业务处理
     */
    private function doHelloJob($data)
    {
        // 实际业务流程处理
        //return true;
        $data=array(
            'userid'=>123456,
            'name'=>'队列测试',
            'isAdmin'=>0,
            'isBoss'=>0,
            'mobile'=>123456,
            'is_show'=>0,
            'email'=>date('Y-m-d H:i:s')
        );
        Db::table('wp_user')->insert($data);
        return true;
    }
    private function doJob($data)
    {

    }





}