/**
 * Created by admin on 2017/9/5.
 */
var accessid = '';
var host = '';
var policyBase64 = '';
var signature = '';
var callbackbody = '';
var filename = '';
var key = '';
var expire = 300;
var now;
var timestamp;
now = timestamp = Date.parse(new Date()) / 1000;

//��������
function send_request() {
    var xmlhttp = null;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (xmlhttp != null) {
        var serverUrl = './php/oss.php';
        xmlhttp.open("GET", serverUrl, false);
        xmlhttp.send(null);
        return xmlhttp.responseText;
    } else {
        alert("Your browser does not support XMLHTTP.");
    }
}
//��ȡ�ϴ�ǩ��
function get_signature()
{
    //�����жϵ�ǰexpire�Ƿ񳬹��˵�ǰʱ��,��������˵�ǰʱ��,������ȡһ��.3s ��Ϊ����
    now = timestamp = Date.parse(new Date()) / 1000;
    if (expire < now + 3)
    {
        var body = send_request();
        var obj = eval ("(" + body + ")");
        console.dir(obj);
        host = obj['host'];
        policyBase64 = obj['policy'];
        accessid = obj['accessid'];
        signature = obj['signature'];
        expire = parseInt(obj['expire']);
        callbackbody = obj['callback'];
        key = obj['dir'];
        return true;
    }
    return false;
}


