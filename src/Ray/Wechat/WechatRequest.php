<?php
namespace Ray\Wechat;

class WechatRequest{

	private $request_url = array();
	public function __construct(){
		$this->request_url['getAccessToken'] = 'https://api.weixin.qq.com/cgi-bin/token';
		$this->request_url['getWechatServerIp'] = 'https://api.weixin.qq.com/cgi-bin/getcallbackip';
		$this->request_url['uploadFile'] = 'http://file.api.weixin.qq.com/cgi-bin/media/upload';	
		$this->request_url['test'] = 'http://requestb.in/yr9zmryr';	 
	}

	public function sendRequest($request_url_tag = 'getAccessToken', $method = 'get', 
		$parameter = array(), $force_refresh_token = false, $file = array()){

		//accessing token does not require token in the first place
		if(!\Cache::has('access_token') || $force_refresh_token){
			$token_parameter = array(
				'grant_type' => 'client_credential', 
				'appid' => \Config::get('wechat::auth.appid'), 
				'secret' => \Config::get('wechat::auth.secret'));
			$this->sendRequest('getAccessToken', 'get', $token_parameter);
		}


		$parameter['access_token'] = \Cache::get('access_token');
//dd($parameter['access_token']);

		$client = new \GuzzleHttp\Client();

		switch ($method) {
			case 'post':
			case 'put':
				$response = $client->$method(
					$this->request_url[$request_url_tag], 
					array('query' => $parameter, 
						'body' => $file
						));
				break;			
			default:
				$response = $client->get(
					$this->request_url[$request_url_tag], 
					array('query' => $parameter));
				break;
		}

		if($response->getStatusCode() == 200){
			$result = $response->json();
			if(isset($result['errcode'])){
				$this->handleError($result['errcode'], $result['errmsg']);
			}
			//store them in cache or db, but for now only cache is considered
			$this->saveInCache($result, $request_url_tag);
			
		}else{
			$this->handleError($response->getStatusCode());
		}
	}

	public function saveInCache($result, $request_url_tag){
var_dump($result);
		switch ($request_url_tag) {
			case 'getAccessToken':
				\Cache::put('expires_in', $result['expires_in'], $result['expires_in']);
				\Cache::put('access_token', $result['access_token'], $result['expires_in']);
				break;
			case 'getWechatServerIp':
				\Cache::put('ip_list', $result['ip_list'], \Config::get('wechat::auth.cache'));
				break;
			default:
				# code...
				break;
		}

	}

	public function handleError($errcode, $errmsg = ''){
		if($errcode == 42001){
			//token expires
			//so we update token by hand
			$this->sendRequest('getAccessToken', 'get', array(), true);
		}

		if($errmsg != ''){
			dd($errcode.' '.$errmsg);
		}
		
	}
}