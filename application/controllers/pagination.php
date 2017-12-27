<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagination extends CI_Controller {
	
	public function index()	{

	}

	public function show_more($identifier,$page_number) {
		$limit = PAGINATION_MEDIUM_PAGE;
		
		switch ($identifier) {
			case POST_ALL:
				$html_data = $this->_get_more_all_types($page_number,$limit);
				$output = array(
					'status' => (empty($html_data)) ? 'false' : 'true',
					'data' => $html_data,
				);
				break;
			case POST_REVIEW:
				$html_data = $this->_get_more_reviews($page_number,$limit);
				$output = array(
					'status' => (empty($html_data)) ? 'false' : 'true',
					'data' => $html_data,
				);
				break;
			case POST_ARTICLE:
				$html_data = $this->_get_more_articles($page_number,$limit);
				$output = array(
					'status' => (empty($html_data)) ? 'false' : 'true',
					'data' => $html_data,
				);
				break;
			case POST_EXCLUSIVE_ARTICLE:
				$html_data = $this->_get_more_exclusive_articles($page_number,$limit);
				$output = array(
					'status' => (empty($html_data)) ? 'false' : 'true',
					'data' => $html_data,
				);
				break;
			default:
				$output = array(
					'status' => 'error',
					'data' => '',
				);
				break;
		}
		echo json_encode($output);	
	}

	private function _get_more_all_types($page_number,$limit,$exclude_active_exclusives=TRUE) {
		$this->load->model(array('article_model'));
		list($start,$end) = $this->_get_limit_bounds($page_number,$limit);
		$all_posts = $this->article_model->get_latest_articles_exclude_active_exclusives($start,$limit);

		$html_data = '';
		foreach ($all_posts as $p) {
			if($p['article_type'] === POST_REVIEW) {
				$html_data .= $this->load->view('snippets/preview_review_content_snippet',$p,TRUE);
			} else if($p['article_type'] === POST_ARTICLE) {
				$html_data .= $this->load->view('snippets/preview_article_content_snippet',$p,TRUE);
			}
		}

		return $html_data;
	}

	private function _get_more_reviews($page_number,$limit) {
		$this->load->model(array('article_model'));
		list($start,$end) = $this->_get_limit_bounds($page_number,$limit);
		$reviews = $this->article_model->get_articles_by_type(MEDIA_MOVIE,$start,$limit,array(POST_REVIEW));

		$html_data = '';
		foreach ($reviews as $r) {
			if($r['article_type'] === POST_REVIEW) {
				$html_data .= $this->load->view('snippets/preview_review_content_snippet',$r,TRUE);
			}
		}

		return $html_data;
	}

	private function _get_more_articles($page_number,$limit) {
		$this->load->model(array('article_model'));
		list($start,$end) = $this->_get_limit_bounds($page_number,$limit);
		$articles = $this->article_model->get_articles_by_type(MEDIA_MOVIE,$start,$limit,array(POST_ARTICLE));

		$html_data = '';
		foreach ($articles as $a) {
			$html_data .= $this->load->view('snippets/preview_article_content_snippet',$a,TRUE);
		}

		return $html_data;
	}

	private function _get_more_exclusive_articles($page_number,$limit) {
		$this->load->model(array('article_model'));
		list($start,$end) = $this->_get_limit_bounds($page_number,$limit);
		$articles = $this->article_model->get_articles_by_type(MEDIA_NONE,$start,$limit,array(POST_ARTICLE));

		$html_data = '';
		foreach ($articles as $a) {
			$html_data .= $this->load->view('snippets/preview_article_content_snippet',$a,TRUE);
		}

		return $html_data;
	}
	
	private function _get_limit_bounds($page_number,$page_size) {
		//check page num is > 0
		if((int)$page_number < 1) {
			$page_number = 1;
		}
		
		$start = $page_size*($page_number - 1);
		$end = $page_size*$page_number;
		
		log_message("INFO","Getting limit bounds: $start to $end");
		
		return array($start,$end);
	}
	
}