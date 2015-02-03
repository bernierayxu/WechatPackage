<?php namespace Ray\Wechat;
use App;
use Illuminate\Support\ServiceProvider;

class WechatServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('ray/wechat');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		App::bind('wechat.request', function(){
			return new WechatRequest;
		});
		App::bind('wechat.auth', function(){
			return new WechatAuth;
		});
		App::bind('wechat.file', function(){
			return new WechatFile;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('wechat.request', 'wechat.auth', 'wechat.file');
	}

}
