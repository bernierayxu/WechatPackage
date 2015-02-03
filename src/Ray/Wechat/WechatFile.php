<?php
namespace Ray\Wechat;
use App;
use GuzzleHttp\Post\PostFile;
class WechatFile{

	public function uploadFile($type = 'image', $media = array()){

		$media['media'] =  fopen('F:/wamp/www/wechatHelper/hey.jpg', 'r');
		$media['field_name'] = 'medias';

		if(in_array($type, ['voice', 'video', 'thumb', 'image'])){
			$request = App::make('wechat.request');
			$request->sendRequest('uploadFile', 'post', array('type' => $type), false, $media);
		}
		//fopen('F:/wamp/www/wechatHelper/090234hnkhaiglhzsikkzh.jpg', 'r')
	}
	
}