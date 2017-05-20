<?php
$cookie_jar = tempnam('./tmp','cookie');
$account = isset($_POST['account']) ? $_POST['account'] : "";
$passwd = isset($_POST['passwd']) ? $_POST['passwd'] : "";
if(!($account && $passwd)){
	echo "数据异常，请联系管理员";
	die();
}
$login_url  = 'http://www.ecard.ldu.edu.cn/loginstudent.action';
$post_fields = "name=$account&passwd=$passwd&userType=1&loginType=2&rand=2&imageField.x=14&imageField.y=5";
$ch = curl_init($login_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
curl_exec($ch);
curl_close($ch);

//调取姓名，卡号
$url='http://www.ecard.ldu.edu.cn/accountcardUser.action';
//$url = 'http://www.ecard.ldu.edu.cn/getPhoto.action?uno=000000085395';
$ch1 = curl_init($url);
curl_setopt($ch1, CURLOPT_HEADER, 0);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch1, CURLOPT_COOKIEFILE, $cookie_jar);
$contents1 = curl_exec($ch1);
//header('Content-type: image/JPEG');
//preg_match("/<div align=\"left\">(.*)<\/div>/",$contents1,$arr);// 姓名
preg_match("/<div align=\"left\">([1-9]\d*|0)<\/div>/",$contents1,$arr2); // 校园卡号
preg_match("/([1-9]\d*\.\d*|0\.\d*[1-9]\d*)元（卡余额）/",$contents1,$arr); //卡余额

//echo $arr2['1'];
curl_close($ch1);

if(!$arr){
	echo "error";
	die();
}
$post_field2 = "account=".$arr2['1']."&passwd=".$passwd;
$url2 = "http://www.ecard.ldu.edu.cn/accountDoreLoss.action";
$ch3 = curl_init($url2);
curl_setopt($ch3, CURLOPT_HEADER, 0);
curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch3, CURLOPT_REFERER,"http://www.ecard.ldu.edu.cn/homeLogin.action");
curl_setopt($ch3, CURLOPT_POSTFIELDS, $post_field2);
curl_setopt($ch3, CURLOPT_COOKIEFILE, $cookie_jar);
$contents = curl_exec($ch3);
header("Content-type: text/html; charset=UTF-8");

$contents = iconv("gbk","UTF-8",$contents);

// if($contents)
// 	echo $contents."<em>".$arr2['1']."</em>"."<i>".$arr['1']."</i>";
// else
// 	echo "no-link";
echo $contents;
//unlink($cookie_jar);

?>