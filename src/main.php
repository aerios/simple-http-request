<?php namespace Simplehttp;

class SimpleHTTPRequest {

	private static function doConnect($method,$url,$data,$option = array()){

		$is_multipart = isset($option['is_multipart']) ? $option['is_multipart'] : false;
		$method = strtolower($method);
		$ch = curl_init();
        $oldurl = $url;
        $url = explode(":", $url);
        $port = false;
        if (count($url) > 2) {
            $port = $url[2];
        }
        $url = $oldurl;        
        $postdata = $data;
        
            
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);        
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:11.0) Gecko/20100101 Firefox/11.0');
        if ($port) {
            curl_setopt($ch, CURLOPT_PORT, $port);
        }
        
        if (is_array($data) && count($data) > 0 && !$is_multipart) {
            $postdata = (http_build_query($data, null, "&"));
        }
        if ($method == 'get') {
            if($postdata)
            	$url.="?" . ($postdata);
        } else if ($method == 'post') {//echo $postdata;            
            curl_setopt($ch, CURLOPT_POST, TRUE);
            @curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        } else if ($method == 'put') {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PUT'));
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        } else if ($method == 'delete') {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: DELETE'));
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        }
        //echo $is_multipart;
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        return $response;
	}


	public static function get($url,$data = array()){
		return SimpleHTTPRequest::doConnect('get',$url,$data);
	}

	public static function post($url,$data){
		return SimpleHTTPRequest::doConnect('post',$url,$data);
	}

}	

?>