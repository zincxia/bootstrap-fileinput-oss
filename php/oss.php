<?php

oss::start();
$req = $_REQUEST;


class oss
{
    const id = 'LTAIsHzVnLeNkvlt';
    const key = '1Mbk949BGZW1zlVSvJP4HsY8Q2WuAm';
    const host = 'http://qihancloud-download.oss-cn-shenzhen.aliyuncs.com/';
    const dir = 'download/test/';
//    const callbackUrl = "http://10.10.19.200/oss/php/callback.php";
    const callbackUrl = "http://oss-demo.aliyuncs.com:23450";

    private static function gmt_iso8601($time)
    {
        $dtStr = date("c", $time);
        $mydatetime = new DateTime($dtStr);
        $expiration = $mydatetime->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration . "Z";
    }

    public static function start()
    {
        $now = time();
        $expire = 300; //���ø�policy��ʱʱ����10s. �����policy���������Чʱ�䣬�����ܷ���
        $end = $now + $expire;
        $expiration = self::gmt_iso8601($end);

        //����ļ���С.�û������Լ�����
        $condition = array(0 => 'content-length-range', 1 => 0, 2 => 1048576000);
        $conditions[] = $condition;

        //��ʾ�û��ϴ�������,��������$dir��ʼ, ��Ȼ�ϴ���ʧ��,��һ�����Ǳ�����,ֻ��Ϊ�˰�ȫ���,��ֹ�û�ͨ��policy�ϴ������˵�Ŀ¼
        $start = array(0 => 'starts-with', 1 => '$key', 2 => self::dir);
        $conditions[] = $start;

        $arr = array('expiration' => $expiration, 'conditions' => $conditions);
        //echo json_encode($arr);
        //return;
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, self::key, true));
        //����callback
        $callback_param = [
            'callbackUrl' => self::callbackUrl,
            'callbackBody' => 'filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}',
            'callbackBodyType' => "application/x-www-form-urlencoded"
        ];
        $callback_string = json_encode($callback_param);
        $base64_callback_body = base64_encode($callback_string);
        //��Ϸ��ز���
        $response = array();
        $response['accessid'] = self::id;
        $response['host'] = self::host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        $response['callback'] = $base64_callback_body;
        //��������������û��ϴ�ָ����ǰ׺
        $response['dir'] = self::dir;
        echo json_encode($response);
    }
}

