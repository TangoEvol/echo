<?php

namespace Home\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
use Com\WechatJssdk;


class WeixinController extends Controller {
    //要处理  菜单用这里
//    public function menu(){
//        $appid = C('APPID');
//        $appsecret = C('APPSECRET');
//        $wechat = new WechatAuth($appid, $appsecret);
//        $wechat->getAccessToken();
//
//        $button = ' {
//     "button":[
//     {
//          "type":"click",
//          "name":"👙今日歌曲",
//          "key":"V1001_TODAY_MUSIC"
//      },
//      {
//           "name":"🍔自助查询",
//           "sub_button":[
//           {
//               "type": "view",
//                "name": "☀天气预报",
//                "url": "http://m.weather.com.cn/",
//                "sub_button": [ ]
//            },
//            {
//                "type": "view",
//                        "name": "📦快递查询",
//                        "url": "http://www.kuaidi100.com/",
//                        "sub_button": [ ]
//            },
//            {
//
//                        "type": "view",
//                        "name": "🍎苹果产品",
//                        "url": "http://apple.com/",
//                        "sub_button": [ ]
//            }]
//       }]
// }';
//    $q=json_decode($button,true);
//    var_dump($q);
//        $a=$wechat -> menuCreate($q);
//        var_dump($a);
//    }
    public function jssdk(){
        $appid = C('APPID');
        $appsecret = C('APPSECRET');
        $jssdk = new WechatJssdk($appid, $appsecret);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage',$signPackage);
        $this->display();

    }
    //oauth2.0 认证  并储存数据库
    public function auth(){
        $appid = C('APPID'); //AppID(应用ID)
        $secret=C('APPSECRET');
        $cod= $_GET['code'];
        //打印url
        // var_dump("http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);exit;
        $wechat = new WechatAuth($appid, $secret);
        if(!empty($cod)){
           $b=$wechat->getAccessToken('code',$cod);
        }else{
            $this->error('信息错误');
        }
        //获取微信信息
        $info=$wechat->getUserInfo($b['openid']);
        $data['openid']=$info['openid'];
        $data['head_url']=$info['headimgurl'];
        $data['sex']=$info['sex'];
        $data['nickname']=base64_encode($info['nickname']);//用base64_encode   变换过来的头像具体值  用base64_decode还原
        $data['subscribe_time']=time();
        //可以执行存入数据库   
        $wx=M('wx_information');
        $where->openid=$info['openid'];
        $sql=$wx->where($where)->find();
        if(is_array($sql)){
            $wx->where($where)->save($data); 
        }elseif($sql===null){
            $wx->add($data);
             
        }else{
            $this->error('错误');
        }
        // if(isset($doc_id)){
        // header("Location:http://hao.muwu.net/index.php/Home/Hospital/guaHao?doc_id=$doc_id");
        // }elseif(isset($doc1_id)){
        //     header("Location:http://hao.muwu.net/index.php/Home/Doctor/call?doc_id=$doc1_id");
        // }else{
        //     header('Location:http://hao.muwu.net/index.php/Home/Index/index');
        // }

        
        
        
        
        
    }

    //空操作是指系统在找不到指定的操作方法的时候，会定位到空操作（_empty）方法来执行，利用这个机制，我们可以实现错误页面和一些URL的优化。
    public function _empty(){
        redirect(SITE_URL);
    }
    //填写URL认证    以及自动回复几项
    public function index(){

        $appid = 'wx9f2a808ec59f8153'; //AppID(应用ID)
        $token = 'wechat'; //微信后台填写的TOKEN
        $crypt = 'euI6A44wPw2DejBnRZSctJ5vU6Sa8S7tokPypJYDrfx'; //消息加密KEY（EncodingAESKey）
        
        /* 加载微信SDK */
        $wechat = new Wechat($token, $appid, $crypt);
        
        /* 获取请求信息 */
        $data = $wechat->request();
        
        if($data && is_array($data)){
            /**
             * 你可以在这里分析数据，决定要返回给用户什么样的信息
             * 接受到的信息类型有10种，分别使用下面10个常量标识
             * Wechat::MSG_TYPE_TEXT       //文本消息
             * Wechat::MSG_TYPE_IMAGE      //图片消息
             * Wechat::MSG_TYPE_VOICE      //音频消息
             * Wechat::MSG_TYPE_VIDEO      //视频消息
             * Wechat::MSG_TYPE_SHORTVIDEO //视频消息
             * Wechat::MSG_TYPE_MUSIC      //音乐消息
             * Wechat::MSG_TYPE_NEWS       //图文消息（推送过来的应该不存在这种类型，但是可以给用户回复该类型消息）
             * Wechat::MSG_TYPE_LOCATION   //位置消息
             * Wechat::MSG_TYPE_LINK       //连接消息
             * Wechat::MSG_TYPE_EVENT      //事件消息
             *
             * 事件消息又分为下面五种
             * Wechat::MSG_EVENT_SUBSCRIBE    //订阅
             * Wechat::MSG_EVENT_UNSUBSCRIBE  //取消订阅
             * Wechat::MSG_EVENT_SCAN         //二维码扫描
             * Wechat::MSG_EVENT_LOCATION     //报告位置
             * Wechat::MSG_EVENT_CLICK        //菜单点击
             */
        
        
            /* 响应当前请求(自动回复) */
            //$wechat->response($content, $type);
        
            /**
             * 响应当前请求还有以下方法可以使用
             * 具体参数格式说明请参考文档
             *
             * $wechat->replyText($text); //回复文本消息
             * $wechat->replyImage($media_id); //回复图片消息
             * $wechat->replyVoice($media_id); //回复音频消息
             * $wechat->replyVideo($media_id, $title, $discription); //回复视频消息
             * $wechat->replyMusic($title, $discription, $musicurl, $hqmusicurl, $thumb_media_id); //回复音乐消息
             * $wechat->replyNews($news, $news1, $news2, $news3); //回复多条图文消息
             * $wechat->replyNewsOnce($title, $discription, $url, $picurl); //回复单条图文消息
             *
             */
            $type  = $data['MsgType'];
            
            if ($type == 'event') {
                $keyword = $data['Event'];
            } else if($type == 'text') {
                $keyword = $data['Content'];
                
            } else if($type == 'image'){
            
        	 	$result = $this->responseMsg();
            }
            // $wechat->replyText($keyword);
            
            if($keyword=='subscribe'){
                $content='欢迎关注刘润峰微信号，回复"导航"让欧巴为您导航';
            
                $wechat->replyText($content);
            }else if(trim($keyword)){
                if($keyword == '刘润峰' || $keyword == '峰哥' || strtolower($keyword) == 'liurunfeng'){
                    $content="刘润峰（又名：Evol），性别男，爱好女；非著名北漂挨踢男、程序猿，伪文艺2B青年，资深屌丝； 本来名字很独特，但很无辜地被叫疯子，瞬间被萌化了。据说爱美女，爱网络，爱旅游，各种控，各种宅，不纠结会死星人，不折腾会死星人。此人纯属虚构，如有雷同，纯属被抄袭…… ";
                    
                    $wechat->replyText($content);
                    return;
                }
                
                if($keyword == '网址' || $keyword == '官网' || $keyword == '够过瘾'){
                    // $content="";
                    $line_size = 8;
                    $input_txt = $keyword;
                    $len = mb_strlen($input_txt,'utf8');
                    // 判断长度
                    if($len > $line_size * 5){
                        $wechat->replyText("太长了,敢短点不?");
                    }
                    $img_serial = 0;
                    // 正则判断
                    $regex = '/^(\d+)-([\s\S]+)/';
                    $count = preg_match($regex, $input_txt, $matches);

                    if($count){
                        $img_serial = intval($matches[1]);
                        $input_txt = $matches[2];
                        
                    }

                    // $img_serial=1;
                   
                    
                    $url = "http://www.weixin.com/wepic/make_pic.php?img_serial={$img_serial}&mark_text={$input_txt}";
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_HEADER, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $response = curl_exec($ch);
                    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    
                    if ('200' == $code) {
                        list($header, $body) = explode("\r\n\r\n", $response, 2);
                        
                        // img content type
                        $regex = '/Content-Type:\s(.+?)\s/';
                        $count = preg_match($regex, $header, $matches);
                        $content_type = isset($matches[1]) ? $matches[1] : 'unknown';
                        // img ext
                        $regex = '/Content-Type:\simage\/(.+?)\s/';
                        $count = preg_match($regex, $header, $matches);
                        $ext = isset($matches[1]) ? $matches[1] : 'unknown';

                        
                        if ('unknown' == $content_type || 'unknown' == $ext) {
                            $wechat->replyText("api error\n" . $body);
                        }

                        
                        $new_file_name = date("ymdHis") . "." . $ext;
                        $key = "media\"; filename=\"$new_file_name\r\nContent-Type: $content_type\r\n";
                        
                        $fields = array();
                        $fields[$key] = $body;
                        

                        $model=new \Org\AccessToken\AccessToken;
                        $token_info=$model->access_token;
                        
                        
                        // $token_info = getToken();
                        // if ($token_info['error'] != 0) {
                        //     $this->replyText("get token error\n" . $token_info['msg']);
                        // }
                        $token = $token_info;
                        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$token}&type=image";

                        $ch = curl_init($url);  // 准备POST
                        curl_setopt($ch, CURLOPT_HEADER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                        curl_setopt($ch, CURLOPT_POST,1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                        $response = curl_exec($ch); // 执行POST
                        curl_close($ch);

                        $result = json_decode($response);
                        $a='dFtcuIN-pEfTVf0Io336n1hRJKwXMTAeFX4T4-xGEBpQOmWUhaTwIchZy7xC9HfJ';
                       	$wechat->replyImage($a);
                        return;
                		 
                        
                        
                        if(isset($result->media_id)){
                            $media_id = $result->media_id;
                           
                            $result=$wechat->replyText($media_id);
                           
                            // $wechat->replyImage($result->media_id);
                            // return;
                        }
                        else{
                            $wechat->replyText("上传文件错误\n" . $response);
                        }
                    }else{
                        $wechat->replyText('获得文件错误');
                    }
                }   
                
                if($keyword == '导航' || $keyword == '菜单'){
                    $nav1 = "1、<a href='".SITE_URL."/Home/Index/index'>首页</a>\n";
                    $nav2 = "2、<a href='".SITE_URL."/Home/Weixin/jssdk'>jssdk</a>\n";
                    $nav3 = "3、<a href='".SITE_URL."/Home/Index/menu'>菜单</a>\n";
                    $nav4 = "4、<a href='".SITE_URL."/js.html'>Javascript/Jquery</a>\n";
                    $nav5 = "5、<a href='".SITE_URL."/html5.html'>HTML5/移动WEB</a>\n";
                    $nav6 = "6、<a href='".SITE_URL."/flink/add.html'>申请友情链接</a>\n";
                    $content= $nav1.$nav2.$nav3.$nav4.$nav5.$nav6;
                
                    $wechat->replyText($content);
                    return;
                }else{
                    $id='1f9bf5f3469364368fc2eadd0179d2f4';
                    $url="http://www.tuling123.com/openapi/api?key=$id&info=$keyword";
                    $res=json_decode(file_get_contents($url),true);
                    if($res['list']) {
                        if ($keyword == '菜谱') {

                            $list = array();
                            foreach ($res['list'] as $key => $value) {
                                $list[$key] = array($value['name'], $value['info'], $value['detailurl'], $value['icon']);

                            }

                            $wechat->replyNews($list);
                            return;
                        }elseif($keyword=='新闻') {
                            $list = array();
                            foreach ($res['list'] as $key => $value) {
                                $list[$key] = array($value['article'], $value['article'], $value['detailurl'], 'http://img1.cache.netease.com/f2e/www/index2014/images/bg_sprites_v16.png');
//                                $l .= "$list[$key],";
                            }
                            $wechat->replyNews($list);
                            return;
                        }

                    }
                    $wechat->replyText($res['text']);
                    return;
                }
                
                // $filter = array();
            
                // $filter['status'] = 1;
                // $filter['title'] = array('like', "%$keyword%");

                // $article_info = M('article')->where($filter)->order('rand()')->find();
                
                // if ($article_info) {
                //     $title = $article_info['title'];
                //     $description = $article_info['intro'];
                //     $url = \article_helper::get_article_url($article_info['id']);
                //     if ($article_info['cover']) {
                //         $cover = SITE_URL.thumb($article_info['cover']);
                //     } else {
                //         $cover = IMAGE_PATH.'/default_cover.png';
                //     }
                //     $wechat->replyNewsOnce($title, $description, $url, $cover);
                // } else {
                //     $wechat->replyText('暂无相关搜索内容，回复"导航"让勾勾为您导航');
                // }

                
            }
            
        }
        
    }
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];//接收XML数据

        /***
 <xml>
 <ToUserName><![CDATA[toUser]]></ToUserName>
 <FromUserName><![CDATA[fromUser]]></FromUserName> 
 <CreateTime>1348831860</CreateTime>
 <MsgType><![CDATA[text]]></MsgType>
 <Content><![CDATA[this is a test]]></Content>
 <MsgId>1234567890123456</MsgId>
 </xml>


        **/

        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;    //用户的open_id
            $toUsername = $postObj->ToUserName;  //开发者的微信账号
            $keyword = trim($postObj->Content);  //用户在手机端输入的文字内容
            $media_id = $postObj->MediaId;
            $msgType = 'text';
            $time = time();
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
            
                        </xml>";
                        
            $imgTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Image>
                        <MediaId><![CDATA[%s]]></MediaId>
                        </Image>
                        </xml>";





// $media_id = "nzWcf26JjiWLqrqn-Eev3Sgga965l12EarUIbAW38Wbj0QdI46o38hmJgFRrPkjR";

          
                
                if ($msgType == "image") {
                    $msgType = "image";
                    $resultStr = sprintf($imgTpl, $fromUsername, $toUsername, $time, $msgType,$media_id);
                } else {
                    $msgType = "text";
                    $contentStr =  $media_id;
                     $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                }
                
               // file_put_contents("tuwenxml.debug",$media_id);
                echo  $resultStr;
          
        }else{
            echo "";
            exit;
        }
    }
        






}