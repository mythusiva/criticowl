<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article_model extends CI_Model {
	
	function get_article_by_pk($article_pk,$live_only = FALSE) {
		
		$sql = "select 	a.article_pk, a.title,COALESCE(a.formatted_content,a.content) as content,a.content as unformatted_content,a.date_created as date_posted,a.image_link,a.article_type,
						m.name as media_title,m.release_date, a.preview_text, a.media_fk, a.is_live, a.is_approved, a.uri_segment, a.media_type,
						m.uri_segment as media_uri_segment, a.is_tweet, a.sources, u.first_name as author, a.expiry_date
				from article a
				join media m
				on a.media_fk = m.media_pk AND m.is_enabled = 1
				left join user u
				on a.user_fk = u.user_pk
				where a.article_pk = ?";
		if($live_only) {
			$sql .= " and a.is_live = 1";
		}
		
		$out = $this->db->query($sql,array($article_pk))->row_array();
		
		return $out;
	}

	function get_trending_articles($amount) {
		$amount = (int)$amount;

		$cache_id = __FUNCTION__.md5($amount);
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}

		$sql = "SELECT a.* 
				FROM article a
				JOIN media m 
				ON a.media_fk = m.media_pk AND m.is_enabled = 1
				WHERE is_live = 1
				ORDER BY date_created DESC
				LIMIT {$amount};";

		$out = $this->db->query($sql,array())->result_array();
		
		set_cached_item($cache_id,$out,CACHE_TTL_SHORT);

		return $out;
	}
	
	function get_articles_by_type_and_media_fk($media_fk,$article_type) {
		$sql = "SELECT * 
				FROM article
				WHERE media_fk = ?
				AND article_type = ?";

		return $this->db->query($sql,array($media_fk,$article_type))->result_array();
	}

	function get_article_by_uri_segment($uri_segment,$type,$live_only = FALSE) {
		$cache_id = __FUNCTION__.md5($uri_segment).(string)$type.(string)$live_only;
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		$sql = "select 	a.article_pk, a.title,COALESCE(a.formatted_content,a.content) as content,a.content as unformatted_content,a.date_created as date_posted,a.image_link,a.article_type,
						m.name as media_title,m.release_date, a.preview_text, a.media_fk, a.is_live, a.is_approved, a.media_type,
						m.uri_segment as media_uri_segment, a.uri_segment, a.is_tweet, a.sources, u.first_name as author, a.expiry_date
				from article a
				join media m 
				on a.media_fk = m.media_pk AND m.is_enabled = 1
				left join user u
				on a.user_fk = u.user_pk
				where a.uri_segment = ? AND a.article_type = ?";
		if($live_only) {
			$sql .= " and a.is_live = 1";
		}
		
		$out = $this->db->query($sql,array($uri_segment,$type))->row_array();
		
		set_cached_item($cache_id,$out,CACHE_TTL_SHORT);
		
		return $out;
	}

	function get_articles_by_type($type,$start = 0, $end = 5, $filters = array()) {
		$cache_id = __FUNCTION__.md5("{$type}-{$start}-{$end}-".json_encode($filters));
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		$sql = "select 	a.article_pk as id,a.title,COALESCE(a.formatted_content,a.content) as content,a.content as unformatted_content,a.date_created as date_posted,a.image_link,a.article_type,
						m.name as media_title,m.release_date, a.preview_text, a.is_live, a.uri_segment, a.media_type,
						m.uri_segment as media_uri_segment, a.is_tweet, a.sources, a.expiry_date
				from article a
				join media m
				on a.media_fk = m.media_pk AND m.is_enabled = 1
				where a.is_live = 1 and a.media_type = ? and m.type = a.media_type ";
		
		if(count($filters) > 0) {
			$filter_list = "'".implode("','",$filters)."'";
			$sql = $sql." AND a.article_type IN ($filter_list) ";
		}
		
		$sql = $sql."order by a.date_created desc
						LIMIT ?,?";
						
		$out = $this->db->query($sql,array($type,$start,$end))->result_array();
		
		set_cached_item($cache_id,$out,CACHE_TTL_SHORT);
		
		return $out;
	}
	
	function get_articles_by_media_fk($media_fk,$start = 0, $end = 5) {
		$cache_id = __FUNCTION__.md5("{$media_fk}-{$start}-{$end}");
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		$sql = "select 	a.article_pk as id,a.title,COALESCE(a.formatted_content,a.content) as content,a.content as unformatted_content,a.date_created as date_posted,a.image_link,a.article_type,
						m.name as media_title,m.release_date, a.preview_text, a.is_live, a.uri_segment, a.media_type,
						m.uri_segment as media_uri_segment, a.is_tweet, a.sources, a.expiry_date
				from article a
				join media m
				on a.media_fk = m.media_pk and m.media_pk = ? AND m.is_enabled = 1
				where a.is_live = 1
				order by a.date_created desc
				LIMIT ?,?";
						
		$out = $this->db->query($sql,array($media_fk,$start,$end))->result_array();
		
		set_cached_item($cache_id,$out,CACHE_TTL_SHORT);
		
		return $out;
	}

	function get_latest_articles_exclude_active_exclusives($start = 0, $end = 5) {
		$all_posts = $this->get_latest_articles($start,$end, TRUE);
		return $all_posts;
	}
	
	function get_latest_articles($start = 0, $end = 5, $ignore_active_exclusives = FALSE) {
		$cache_id = __FUNCTION__.md5("{$start}-{$end}");
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		$sql = "select 	a.article_pk as id,a.title,COALESCE(a.formatted_content,a.content) as content,a.content as unformatted_content,a.date_created as date_posted,a.image_link,a.article_type,
						m.name as media_title,m.release_date, a.preview_text, a.is_live, a.uri_segment, a.media_type,
						m.uri_segment as media_uri_segment, a.is_tweet, a.sources, a.expiry_date
				from article a
				join media m
				on a.media_fk = m.media_pk AND m.is_enabled = 1
				where a.is_live = 1
				and a.article_type <> ?";
		if($ignore_active_exclusives) {
			$sql .= " AND ((a.media_type = '".MEDIA_NONE."' AND a.expiry_date < date('now')) OR (a.media_type = '".MEDIA_MOVIE."'))";
		}

		$sql .=	" order by a.date_created desc
				limit ?,?";
		$out = $this->db->query($sql,array(POST_NEWS,$start,$end))->result_array();

		set_cached_item($cache_id,$out,CACHE_TTL_SHORT);

		return $out;
	}
	
	function get_latest_exclusives($start = 0, $end = 5) {
		$cache_id = __FUNCTION__.md5("{$start}-{$end}");
		
		if($out = get_cached_item($cache_id)) {
			return $out;
		}
		
		$sql = "select 	a.article_pk as id,a.title,COALESCE(a.formatted_content,a.content) as content,a.content as unformatted_content,a.date_created as date_posted,a.image_link,a.article_type,
						m.name as media_title,m.release_date, a.preview_text, a.is_live, a.uri_segment, a.media_type,
						m.uri_segment as media_uri_segment, a.is_tweet, a.sources, a.expiry_date
				from article a
				join media m
				on a.media_fk = m.media_pk AND m.is_enabled = 1
				where a.is_live = 1
				and a.article_type = ?
				and a.media_type = ?
				and a.expiry_date is not NULL and date('now') < a.expiry_date
				order by a.date_created desc
				limit ?,?";

		$out = $this->db->query($sql,array(POST_ARTICLE,MEDIA_NONE,$start,$end))->result_array();
		
		set_cached_item($cache_id,$out);
		
		return $out;
	}	

	function save_article(	$user_fk,$title,$content,$preview_text,
							$media_type,$media_fk,$image_link,
							$is_live,$is_approved = 0,$article_pk = null,$sources='',$expiry_date=NULL) {
		
		//double check if expiry date is set, if not explicitly set to NULL
		if(empty($expiry_date)) {
			$expiry_date = NULL;
		} else {
			//get the correct format for SQL to interpret it correctly
			$expiry_date = strtotime($expiry_date);
			$expiry_date = sql_datetime($expiry_date);
		}

		$formatted_content = $this->format_article_content($content);
		
		$add_sql = "INSERT INTO article
						(user_fk,title,content,formatted_content,date_created,date_modified,sources,
						 media_type,media_fk,image_link,article_type,
						 preview_text,is_live,uri_segment,last_edited_user_fk, expiry_date)
					VALUES
						(?,?,?,?,date('now'),date('now'),?,
						 ?,?,?,?,
						 ?,?,?,?,?)";
		
		$update_sql = "	UPDATE article
						SET title = ?,
						   content = ?,
							 formatted_content = ?,
						   preview_text = ?,
						   date_modified = date('now'),
						   media_type = ?,
						   media_fk = ?,
						   image_link = ?,
						   is_live = ?,
						   uri_segment = ?,
						   last_edited_user_fk = ?,
						   sources = ?,
						   expiry_date = ?
						WHERE article_pk = ?";
		
		$uri_segment = $this->_generate_article_uri($title);
		
		if($article_pk === null) {
			$this->db->query($add_sql,array($user_fk,$title,$content,$formatted_content,$sources,
											$media_type,$media_fk,$image_link,POST_ARTICLE,
											$preview_text,$is_live,$uri_segment,$user_fk,$expiry_date));
			$article_pk = $this->db->insert_id();
		} else {
			$this->db->query($update_sql,array(	$title,$content,$formatted_content,$preview_text,
												$media_type,$media_fk,$image_link,
												$is_live,$uri_segment,$user_fk,$sources,$expiry_date,$article_pk));
			
		}

		$ci = &get_instance();
		$ci->uri_memory_model->insert_into_uri_memory_model($article_pk,"/".get_uri_prefix_by_article_type(POST_ARTICLE)."{$uri_segment}");
		
		$this->set_is_approved($article_pk,$is_approved);
		
		clear_all_cache();
		return $article_pk;
	}
	
	function save_review(	$user_fk,$title,$content,$preview_text,
							$media_type,$media_fk,$image_link,
							$is_live,$is_approved = 0,$article_pk = null,$sources = '') {

		$formatted_content = $this->format_article_content($content);
		
		$add_sql = "INSERT INTO article
						(user_fk,title,content,formatted_content,date_created,date_modified,sources,
						 media_type,media_fk,image_link,article_type,preview_text,
						 is_live,uri_segment,last_edited_user_fk)
					VALUES
						(?,?,?,?,date('now'),date('now'),?,
						 ?,?,?,?,?,
						 ?,?,?)";
						
		$update_sql = "	UPDATE article
						SET title = ?,
						   content = ?,
							 formatted_content = ?,
						   preview_text = ?,
						   date_modified = date('now'),
						   media_type = ?,
						   media_fk = ?,
						   image_link = ?,
						   is_live = ?,
						   uri_segment = ?,
						   last_edited_user_fk = ?,
						   sources = ?
						WHERE article_pk = ?";
		
		$uri_segment = $this->_generate_article_uri($title);
		
		if($article_pk === null) {
			$this->db->query($add_sql,array($user_fk,$title,$content,$formatted_content,$sources,
											$media_type,$media_fk,$image_link,
											POST_REVIEW,$preview_text,$is_live,$uri_segment,$user_fk));
			$article_pk = $this->db->insert_id();
		} else {
			$this->db->query(	$update_sql,array($title,$content,$formatted_content,$preview_text,
								$media_type,$media_fk,$image_link,$is_live,
								$uri_segment,$user_fk,$sources,$article_pk));
		}
		
		$ci = &get_instance();
		$ci->uri_memory_model->insert_into_uri_memory_model($article_pk,"/".get_uri_prefix_by_article_type(POST_REVIEW)."{$uri_segment}");

		$this->set_is_approved($article_pk,$is_approved);
		
    	clear_all_cache();
    	return $article_pk;
	}
	
	function set_is_approved($article_pk,$is_approved) {
    if(in_array(get_permissions(),array(PERMISSION_ADMIN,PERMISSION_EDITOR))) {
			$this->db->query("UPDATE article SET is_approved = ? WHERE article_pk = ?",array((int)$is_approved,(int)$article_pk));	
		} else {
      //someone else tried to save it!
			$this->db->query("UPDATE article SET is_approved = 0 WHERE article_pk = ?",array((int)$article_pk));	
		}
	}
	
	// function get_last_twitter_id() {
	// 	$sql = "SELECT *
	// 			FROM article
	// 			WHERE twitter_id IS NOT NULL
	// 			ORDER BY twitter_id DESC
	// 			LIMIT 1";
		
	// 	$row = $this->db->query($sql)->row_array();
		
	// 	return (empty($row['twitter_id'])) ? FALSE : $row['twitter_id'];
	// }
	

	// public function	get_related_pages($article_pk) {
	// 	$sql_tags = "select group_concat(tag_fk) as tag_fks 
	// 	 			 from tag_article 
	// 	 			 where article_fk = ?;";

	// 	//get tags list
	// 	$associated_tags = $this->db->query($sql_tags,array($article_pk))->row_array();
	// 	$associated_tags = $associated_tags['tag_fks'];

	// 	$articles_with_similar_tags = $this->get_articles_with_tags($associated_tags,(array)$article_pk);
	// 	$articles_trailer_talks = $this->get_latest_trailer_talks((array)$article_pk);
	// 	$articles_reviews = $this->get_latest_reviews((array)$article_pk);

	// 	return array_merge(
	// 		$articles_with_similar_tags,
	// 		$articles_trailer_talks,
	// 		$articles_reviews
	// 	);
	// }

	public function get_articles_with_tags($article_pk,$exclude_article_pks = array(0),$limit = 2) {
		$sql_tags = "select group_concat(tag_fk) as tag_fks 
		 			 from tag_article 
		 			 where article_fk = ?;";

		//get tags list
		$associated_tags = $this->db->query($sql_tags,array($article_pk))->row_array();

		$tag_list = convert_array_to_in($associated_tags['tag_fks']);
		$exclude_article_pks = convert_array_to_in($exclude_article_pks);

		$sql = "select a.* 
				from tag_article ta
				join article a on ta.article_fk = a.article_pk and a.is_live = 1
				where ta.tag_fk in ({$tag_list}) and a.article_pk not in ({$exclude_article_pks})
				group by a.article_pk
				order by a.date_created desc
				limit {$limit};";

		return $this->db->query($sql)->result_array();
	}

	public function get_latest_trailer_talks($exclude_article_pks = array(0),$limit = 3) {
		$exclude_article_pks = convert_array_to_in($exclude_article_pks);

		$sql = "select * 
				from article 
				where title like \"Trailer Talk%\"
				and article_type = ?
				and article_pk not in ({$exclude_article_pks})
				and is_live = 1
				order by date_created desc
				limit {$limit};";		

		return $this->db->query($sql,array(POST_ARTICLE))->result_array();
	}

	public function get_latest_reviews($exclude_article_pks = array(0),$limit = 3) {
		$exclude_article_pks = convert_array_to_in($exclude_article_pks);
		
		$sql = "select * 
				from article 
				where article_type = ?
				and article_pk not in ({$exclude_article_pks})
				and is_live = 1
				order by date_created desc
				limit {$limit};";
	
		return $this->db->query($sql,array(POST_REVIEW))->result_array();
	}
	
	public function update_article_uris() {
		$articles = $this->db->query("SELECT article_pk,title,article_type FROM article")->result_array();
		
		foreach($articles as $a) {
			
			if($a['article_type'] === POST_NEWS) {
				$uri_segment = $a['article_pk'];
			} else if(in_array($a['article_type'],array(POST_ARTICLE,POST_REVIEW))) {
				$uri_segment = $this->_generate_article_uri($a['title']);
			}
			
			$this->db->query('UPDATE article SET uri_segment = ? WHERE article_pk = ?',array($uri_segment,$a['article_pk']));
		}
		
	}

	public function get_rss_feed($limit = '25') {
		$results = $this->db->query("
			SELECT title,preview_text,uri_segment,article_type,date_notified,image_link
			FROM article
			WHERE is_live = 1
			ORDER BY date_notified desc
			LIMIT {$limit};
		")->result_array();

		return $results;
	}
	
	private function _generate_article_uri($title) {
		$str = trim($title);
		$str = strtolower($str);
		$str = str_replace("$","s",$str);
		$str = preg_replace("/[^A-Za-z0-9- ]/",'', $str);
		$str = preg_replace("/\s+/", ' ', $str);
		$str = str_replace(' ','-',$str);
		$str = preg_replace("/-+/", '-', $str);
		
		echo "Generated article uri {$str} ... \n"; 
		
		return $str;
	}


	function format_article_content($content) {
		$content = $this->_search_replace_style_mapping(
			$content,
			array(
				"/font-family:.+?;/",
				"/font-size: *?15px;/",
			),
			array(
				"font-family:BodyText;",
				"font-size:17px;",
			)
		);

		return $content;
	}

	private function _search_replace_style_mapping($content,$old_style_search,$new_style_replace) {
		return preg_replace($old_style_search, $new_style_replace, $content);
	}

}

/* End of file conversation_model.php */
/* Location: ./application/models/conversation_model.php */
