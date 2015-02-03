<?php
namespace Ray\Wechat;
use App;
class WechatAuth{

	public function connect(){
		$signature = \Input::get("signature", '');
	    $timestamp = \Input::get("timestamp", '');
	    $nonce = \Input::get("nonce", '');
	    		
		$token = \Config::get('wechat::auth.token');

		$tmpArr = array($token, $timestamp, $nonce);
	    // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			$echoStr = \Input::get("echostr", '');
			echo $echoStr;
		}
	}

	public function getAccessToken(){
		$request = App::make('wechat.request');
		$request->sendRequest(__FUNCTION__, 'get', array(), true);
	}

	public function getWechatServerIp(){
		$request = App::make('wechat.request');
		$request->sendRequest(__FUNCTION__, 'get', array(), false);
	}	
}