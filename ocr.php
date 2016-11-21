�ӡ�http://developer.baidu.com/forum/topic/show?ZopicId=4363���Ǹ����ӵ�Դ��ο��£�������С�޸ģ����Գɹ��������´��룬��������ܻ���THINKPHP��,
�׷�������ĵط���
1.ǩ���Ĳ���
2.ͼƬ�ĸ�ʽ��OCR�����ͼƬ��ʽ��ͼƬ��С�����ƣ���ʽ֧��jpg��png������ҪС��2048px��
3.�������Ϣ���ʽ

��ȡǩ���ķ�����
private function getSigner($host,$httpMethod, $path, $parms, $timestamp) {
   $AK = "";    //�滻��BCE��AK
   $SK = "";    //�滻��BCE��SK 
   $expirationPeriodInSeconds = "3600"; 
   $CanonicalHeaders1 = "host;" . "x-bce-date;";
   $CanonicalHeaders2 = "host:".$host."\n" . "x-bce-date:" . urlencode($timestamp); // 
   $httputil = new \Api2\Util\BaiduOcr\HttpUtil();
   $CanonicalString = $httputil->getCanonicalQueryString($parms);
   $CanonicalURI = $path;
   $Method = $httpMethod;
   $CanonicalRequest = $Method . "\n" . $CanonicalURI . "\n" . $CanonicalString . "\n" . $CanonicalHeaders2; 
   $authStringPrefix = "bce-auth-v1" . "/" . $AK . "/" . $timestamp . "/" . $expirationPeriodInSeconds; 
   $SigningKey = hash_hmac('SHA256', $authStringPrefix, $SK); 

   $Signature = hash_hmac('SHA256', $CanonicalRequest, $SigningKey); 
   $Authorization = "bce-auth-v1/{$AK}/" . $timestamp . "/{$expirationPeriodInSeconds}/{$CanonicalHeaders1}/{$Signature}"; 
   return $Authorization; 
}

ִ��OCR�ӿڵ��õķ�����
public function bdocr(){
   //��ѯý����Ϣʾ��
   $http=new \Api\Util\BaiduOcr\HttpUtil();
   $http->__init();
   $host= "word.bj.baidubce.com";
   $path = "/api/v1/ocr/general";
   $url = "http://".$host.$path;
   $method = "POST";
   $parms = array();

   date_default_timezone_set('UTC');
   $timestamp = date("Y-m-d") . "T" . date("H:i:s") . "Z";
   $Authorization = $this->getSigner($host,$method,$path, $parms, $timestamp); 
   //��Ϣ�塣������ֱ�������Լ��ı����ļ�����Ȼ����ƴ�ӵĲ������Բο��¹ٷ��ĵ���ƴ�ӷ�ʽ�ǡ�&�����ӣ��硰&detect_direction=true��
   $data_string='image='.urlencode(base64_encode(file_get_contents('./Uploads/2016-10-20/test.jpg'))).'&detect_direction=true';
   //$data_string=urlencode($data_string);
   $head = array(
      "x-bce-date:{$timestamp}",
      "connection: keep-alive",
      "accept: */*",
      "Host: word.bj.baidubce.com",
      "authorization:{$Authorization}",
   );
   $curlp = curl_init();
   curl_setopt($curlp, CURLOPT_URL, $url);
   curl_setopt($curlp, CURLOPT_HTTPHEADER, $head);
   curl_setopt($curlp, CURLOPT_POSTFIELDS, $data_string);
   curl_setopt($curlp, CURLOPT_POST, 1);
   curl_setopt($curlp, CURLOPT_RETURNTRANSFER, 1);
   $output = curl_exec($curlp);
   curl_close($curlp);
   //echo $output;
   Header('Content-type:text/html;charset=utf8');
   dump(json_decode($output,true));
   print "\n";
}
���صĸ�ʽ��
array(4) {
  ["log_id"] => int(581406933)
  ["direction"] => int(0)
  ["words_result"] => array(2) {
    [0] => array(2) {
      ["location"] => array(4) {
        ["left"] => int(36)
        ["top"] => int(91)
        ["width"] => int(112)
        ["height"] => int(14)
      }
      ["words"] => string(32) " e 4 a 4 a 9 6 a 6 9 a 1 b 2 b 5"
    }
    [1] => array(2) {
      ["location"] => array(4) {
        ["left"] => int(31)
        ["top"] => int(105)
        ["width"] => int(121)
        ["height"] => int(15)
      }
      ["words"] => string(28) " 3 0 b 3 bec 6 7 3 4 cdf 5 2"
    }
  }
  ["words_result_num"] => int(2)
}

