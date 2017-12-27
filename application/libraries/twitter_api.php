<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
require_once('./resources/twitter-api-php-master/TwitterAPIExchange.php');

class Twitter_api {

	public function __construct() {
		$this->ci = &get_instance();
		$this->settings['consumer_key'] = $this->ci->config->item('consumerKey');
		$this->settings['consumer_secret'] = $this->ci->config->item('consumerSecret');
		$this->settings['oauth_access_token'] = $this->ci->config->item('accessToken');
		$this->settings['oauth_access_token_secret'] = $this->ci->config->item('accessTokenSecret');
	}
	
	public function get_latest_tweets($count=10,$use_cached=TRUE) {
		$count = (int)$count;

		$cache_id = __FUNCTION__.md5($count);
		
		if($use_cached && $out = get_cached_item($cache_id)) {
			return $out;
		}

		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$getfield = "?screen_name=criticowl&count={$count}";
		$requestMethod = 'GET';

		$twitter = new TwitterAPIExchange($this->settings);
		$out = 	$twitter->setGetfield($getfield)
						->buildOauth($url, $requestMethod)
						->performRequest();
		unset($twitter);
		
		$out = json_decode($out,TRUE);

		set_cached_item($cache_id,$out,CACHE_TTL_SHORT);

		return $out;
	}

	public function search_tweets($search_term,$count=15,$since_id=0) {
		$search_term = urlencode($search_term);
		$url = 'https://api.twitter.com/1.1/search/tweets.json';
		$getfield = "?q={$search_term}&count={$count}&since_id={$since_id}";
		$requestMethod = 'GET';

		$twitter = new TwitterAPIExchange($this->settings);
		$out = 	$twitter->setGetfield($getfield)
						->buildOauth($url, $requestMethod)
						->performRequest();
		unset($twitter);
		
		return json_decode($out,TRUE);
	}

	public function get_latest_tweet_ids($count=10) {
		$tweets = $this->get_latest_tweets($count);

		$out = array();
		foreach ((array)$tweets as $key => $tweet_obj) {
			$tweet = (array)$tweet_obj;
			if(isset($tweet['id'])) {
				$out[] = $tweet["id"];
			}
		}

		return $out;
	}
	
}

