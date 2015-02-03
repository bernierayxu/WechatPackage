<?php
namespace Ray\Wechat;
use App;
class WechatMessage{
	public $from = '';
	public $to = '';
	public $created_at = '';
	public $content = '';
	public $message_id = '';
	public $pic_url = '';
	public $media_id = '';
	public $format = '';
	public $thumb_media_id = '';
	public $type = 'unknown';
	public $location_x = '';
	public $location_y = '';
	public $scale = '';
	public $label = '';
	public $title = '';
	public $description = '';
	public $url = '';
	public $event_key = '';
	public $ticket = '';
	public $latitude = '';
	public $longitude = '';
	public $precision = '';
	public $event = '';

	public function __construct(){

		$message = simplexml_load_string(Request::getContent());
		if($message && isset($message->MsgType)){

		    switch ($message->MsgType) {
		    	case 'text':
				   	$this->content = $message->Content;
		    		break;
		    	case 'image':
		    		$this->pic_url = $message->PicUrl;
		    		$this->media_id = $message->MediaId;			    		
		    		break;	    	
		    	case 'voice':
		    		$this->format = $message->format;
		    		$this->media_id = $message->MediaId;			    		
		    		break;	    	
		    	case 'video':
		    		$this->thumb_media_id = $message->ThumbMediaId;
		    		$this->media_id = $message->MediaId;		    		
		    		break;
		    	case 'location':
		    		$this->location_x = $message->Location_X;
		    		$this->location_y = $message->Location_Y;
		    		$this->scale = $message->Scale;
		    		$this->label = $message->Label;		    		
		    		break;		    	
		    	case 'link':
		    		$this->title = $message->Title;
		    		$this->description = $message->Description;
		    		$this->url = $message->Url;		    		
		    		break;
		    	case 'event':
		    		//handle event cases
		    		switch ($message->Event) {
		    			case 'subscribe': //用户未关注时，进行关注后的事件推送
		    			case 'SCAN': //用户已关注时的事件推送
		    				$this->event_key = isset($message->EventKey)? $message->EventKey : "";
		    				$this->ticket = isset($message->Ticket)? $message->Ticket : "";
		    				break;
		    			case 'unsubscribe':
		    				break;		    			
		    			case 'LOCATION': //上报地理位置事件
		    				$this->latitude = $message->Latitude;
		    				$this->longitude = $message->Longitude;
		    				$this->precision = $message->Precision;
		    				break;
		    			case 'CLICK': //点击菜单拉取消息时的事件推送
		    			case 'VIEW': //点击菜单跳转链接时的事件推送
		    				$this->event_key = $message->EventKey;
		    				break;
		    			default:
		    				return ;
		    				break;
		    		}
		    		$this->event = $message->Event;
		    		break;
		    	default:
		    		return ;
		    		break;
	    	}		

	    	//shared variables
			$this->from =  $message->FromUserName;
		    $this->to = $message->ToUserName;
		    $this->created_at = $message->CreateTime;
		    $this->message_id = $message->MsgId; 
		    $this->type = $message->MsgType;

		
		}
	}
	
}