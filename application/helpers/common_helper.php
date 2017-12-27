<?php

function sqlite_escape_string($str) {
	return SQLite3::escapeString($str);
}

function get_combined_css($output_name,$array_of_css_links) {
	$ci = &get_instance();

	return $ci->minifier->get_url_combine_and_compress_links($output_name,"CSS",$array_of_css_links);
}

function get_combined_js($output_name,$array_of_js_links) {
	$ci = &get_instance();

	return $ci->minifier->get_url_combine_and_compress_links($output_name,"JS",$array_of_js_links);
}


function resource_url() {
	$ci = &get_instance();
	return $ci->config->item('resource_url');
}

function is_production() {
	$ci = &get_instance();
	return $ci->config->item('environment') === 'production';
}

function current_datetime() {
	return date('Y-m-d H:i:s');
}

function sql_datetime($epoch) {
	return date('Y-m-d H:i:s',$epoch);
}

function get_last_value($post_values,$search,$default = 0) {
	if(isset($post_values[$search])) {
		return $post_values[$search];
	} else {
		return $default;
	}
}

function array_flatten($in_array)
{
	$out = array();
	
	$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($in_array));
	foreach($it as $v) {
		$out[] = $v;
	}
	
	return $out;
}

function convert_array_to_in($array,$default_value = 0) {
	$array = (array)$array;
	
	if(empty($array)) {
		$array = array($default_value);
	}

	return implode(',',$array);
}

function get_key_from_value($full_list,$value) {
	
	$key = array_search($value,$full_list);
	if($key === FALSE) {
		return '';
	} else {
		return $key;
	}
}

function format_display_name($preferred_name,$first_name,$last_name) {
	if(!empty($preferred_name)) {
		return ucfirst($preferred_name).' '.ucfirst($last_name);
	} else {
		return ucfirst($first_name).' '.ucfirst($last_name);
	}
}

function get_array_from_formatted_string($separator,$string) {
	//takes a string using a defined separator and then returns an array after doing an explode.
	if(mb_strpos($string,$separator) > 0) {
		return explode($separator,$string);
	} else {
		return array($string);
	}
	
}

function get_uri_prefix_by_media_type($media_type) {
	if($media_type === MEDIA_MOVIE) {
		return 'movies/';
	} else {
		return '/';
	}
}
function get_uri_prefix_by_article_type($article_type) {
	if($article_type === POST_REVIEW) {
		return 'review/';
	} else if($article_type === POST_ARTICLE) {
		return 'article/';
	}
}

function encodeURIComponent($str) {
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return str_replace(array_values($revert),array_keys($revert), $str);
}


function get_criticowl_phrase() {

  return "We've shutdown! This website is no longer maintained ... - CriticOwl Staff";

	if(date('m-d') === '01-01') {
		return "Happy New Year! (From everyone at CriticOwl)";
	} elseif(date('m-d') === '12-24' || date('m-d') === '12-25' || date('m-d') === '12-26') {
		return "Happy Holidays! (From everyone at CriticOwl)";
	}
	
	$ci = &get_instance();	
	$ci->load->model('quotation_model');
	$quotations = $ci->quotation_model->get_quotations();
	
	if(count($quotations) === 0) {
		return "<br />";
	}
	
	$selected = $quotations[rand(0,count($quotations)-1)];
	
	$text = "“{$selected['quote']}” ({$selected['author']})";
	
	return $text;
}

function get_trending_articles($amount = 25) {
	$ci = &get_instance();	
	$ci->load->model('article_model');
	$articles = $ci->article_model->get_trending_articles($amount);

	// var_dump($articles); die();

	$trending_article_list = [];
	foreach ($articles as $a) {
		$trending_article_list[] = array(
			'title' => $a['title'],
			'url' => get_uri_prefix_by_article_type($a['article_type']).$a['uri_segment'],
		);
	}

	return $trending_article_list;
}

function convert_to_hashtag($text,$force_lowercase = FALSE) {
	$str = trim($text);
	$str = str_replace("$","s",$str); //any special characters
	$str = preg_replace("/[^A-Za-z0-9]/",'', $str);
	if($force_lowercase) {
		$str = strtolower($str);
	}
	
	return '#'.$str;
}

function get_all_media_types() {
	return array(MEDIA_MOVIE);
}

function _debug($var) {
	echo "<pre>";
	var_dump($var);
	echo "</pre><br /><br />";
}


function get_url_array_from_list($source_list) {
	
	$urls = explode(',',$source_list);
	
	$out = array();
	
	foreach($urls as $url) {
		$parsed = parse_url($url);
		
		if(isset($parsed['host'])) {
			$out[$parsed['host']] = $url;
		}
	}
	
	return $out;
}

function get_date_status($date) {
	$epoch = strtotime($date);
	if($epoch < 0 || empty($epoch)) {
		return DATE_UNAVAILABLE;
	} else if($epoch > time()){
		return DATE_PRERELEASE;
	} else {
		return DATE_POSTRELEASE;
	}
}

function format_date($date,$pattern_override = 'Y-m-d') {
	$epoch = strtotime($date);
	if($epoch < 0 || empty($epoch)) {
		return "unavailable";
	} else {
		return date($pattern_override,$epoch);
	}
}

function sql_format_release_date($epoch) {
	if($epoch === NULL) {
		return NULL;
	} else if($epoch < 0 || empty($epoch)) {
		return "0000-00-00 00:00:00";
	} else {
		return sql_datetime($epoch);
	}
}

function get_compressed_image_url($web_url) {
	$ci = &get_instance();
	return $ci->image_model->get_compressed_image($web_url);
}

function get_compressed_thumbnail_url($web_url) {
	$ci = &get_instance();
	return $ci->image_model->get_compressed_thumbnail_image($web_url);
}

function get_relative_days_left($days_left,$date_format='M d') {
	if($days_left === 0) {
		$out = "today";
	} elseif($days_left === 1) {
		$out = "tomorrow";
	} elseif($days_left < 10) {
		$out = "{$days_left} days";		
	} else {
		$out = date($date_format, strtotime("+{$days_left} day"));
	}

	return $out;
}

function get_short_url($url) {
	$ci = &get_instance();
	$owly_key = $ci->config->item('owly_key');

	$api = 'http://ow.ly/api/1.1/url/shorten';

	$ch = curl_init ("{$api}?apiKey={$owly_key}&longUrl={$url}");
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	$obj = curl_exec ($ch);

	$result = json_decode($obj,TRUE);

	if(	isset($result['results']) && 
		isset($result['results']['shortUrl'])
	) {
		$short_url = $result['results']['shortUrl'];
	} else {
		log_message("ERROR","Failed to get short url for: {$url}");
		$short_url = $url;
	}

	return $short_url;
}

function is_worth_watching($rating) {
	if($rating >= RATING_THRESHOLD_WORTH_WATCHING) {
		return TRUE;
	} else {
		return FALSE;
	}
}


function get_rating_label($demical_rating,$media_type) {
	$ci = &get_instance();
	$ci->load->model('media_model');
	return  $ci->media_model->get_rating_system_label($demical_rating,$media_type);
}


function process_rating_variants($raw_decimal_rating) {
	$out['decimal'] = round($raw_decimal_rating,1,PHP_ROUND_HALF_UP);
	$out['decimal'] = ($out['decimal'] > 1) ? 1 : $out['decimal'];
	$out['numerator_of_100'] = (int)(100*$out['decimal']);
	$out['numerator_of_10'] = (int)(10*$out['decimal']);
	return $out;
}

function get_should_i_watch_it_label($rating) {
	if($rating > 0 && $rating < 40) {
		$labels = array(
			"Don't waste your time with this one",
			"Unless your looking to torture yourself, this one is NOT recommended for viewing."
		);
	}
	elseif($rating > 0 && $rating < 60) {
			$labels = array(
				"Watch it if you're bored",
				"Watch this on the odd day where you feel that you have nothing else to do or if you feel like watching it in the background. There may be a few interesting moments but overall, you wouldn't want to pay full price for this one!"
			);		
	}
	elseif($rating > 0 && $rating < 90) {
		$labels = array(
			"It's worth a watch",
			"We suggest you watch this one. It will keep you engaged and entertained. Get your snacks, drinks and your comfort items. Kick back and enjoy this one!"
		);
	}
	elseif($rating > 0 && $rating <= 100) {
		$labels = array(
			"Definitely watch this one!",
			"Drop everything you are doing right now and go watch this! This one has successfully made it to our favorites collection. Definitely CriticOwl approved!"
		);
	}

	return $labels;
}

//amazon links
function get_amazon_search_link($affiliate_id,$domain,$search_term) {
	$search_term = urlencode($search_term);
	$url = "{$domain}?url=search-alias=aps&field-keywords={$search_term}&tag={$affiliate_id}&link_code=wql&_encoding=UTF-8";

	return $url;
}
function get_amazon_affiliate_ids() {
	$ci = &get_instance();
	return $ci->config->item('amazon_affiliate_ids');
}
//

function base64_url_encode($input) {
 return strtr(base64_encode($input), '+/=', '-_,');
}

function base64_url_decode($input) {
 return base64_decode(strtr($input, '-_,', '+/='));
}

function clean_xml_string($string) {
	return htmlspecialchars($string);
}