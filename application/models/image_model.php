<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image_model extends CI_Model {

	public function __construct() {
		parent::__construct();

		$this->img_storage_directory = $this->_get_path_to_image_directory();
		$this->uri_path = "/img/generated";
	}

	public function get_compressed_image($img_url) {
		
		if(empty($img_url)) {
			return $img_url;
		}

		$this->_download_image($img_url);
		$stored_img = $this->_find_get_stored_image($img_url);

		if(empty($stored_img)) {
			$output_url = $img_url;
		} else {
			$img_uri = $this->_convert_image_filepath_to_uri($stored_img);
			$output_url = rtrim(resource_url(),'/').$img_uri;
		}

		return $output_url;
	}

	public function get_compressed_thumbnail_image($img_url) {

		if(empty($img_url)) {
			return $img_url;
		}

		$stored_compressed_thumbnail = $this->_find_get_stored_image($img_url,TRUE);

		if(empty($stored_compressed_thumbnail)) {
			//raise error because it should have been created
			log_message("ERROR","unable to get thumbnail image for {$img_url}");
			$output_url = $this->get_compressed_image($img_url); //fallback
		} else {
			$img_uri = $this->_convert_image_filepath_to_uri($stored_compressed_thumbnail);
			$output_url = rtrim(resource_url(),'/').$img_uri;
		}

		return $output_url;
	}

	private function _get_path_to_image_directory() {
		$segments = explode('/', rtrim(BASEPATH,"/"));

		array_pop($segments);

		$generated_img_path = implode('/', $segments)."/img/generated";

		return $generated_img_path.'/';
	}

	private function _get_image_signature($img_url) {
		return strlen($img_url).'-'.md5($img_url);
	}
	
	private function _download_image($img_url) {
		$output_file = $this->img_storage_directory.$this->_get_image_signature($img_url);

		if(!$this->check_if_file_exists_by_pattern($output_file)) {
			$img_url = $this->_url_encode_a_web_address($img_url);
			$cmd = "wget -O {$output_file} {$img_url}";
			log_message("INFO","Downloading image: {$cmd}");
			shell_exec($cmd);

			if(file_get_contents($output_file) === "") {
				unlink($output_file);
			}

			if(file_exists($output_file)) {
				log_message("INFO","file successfully downloaded: {$output_file}");
				$image_extension = $this->_get_image_extension($output_file);
				$permanant_filename = $output_file.$image_extension;
				$permanant_thumbnail_filename = $output_file."_THUMBNAIL".$image_extension;
				rename($output_file, $permanant_filename);
				log_message("INFO","renaming {$output_file} -> {$permanant_filename}");
				chmod($permanant_filename, 0777);
				$this->_compress_image($permanant_filename);
				$this->_resize_image($permanant_filename,$permanant_thumbnail_filename);
			}
		}
	}

	private function _url_encode_a_web_address($url) {
		$url_componants = parse_url($url);

		if(isset($url_componants['scheme'])) {
			//url encode the path
			$path_segments = explode("/", $url_componants['path']);
			foreach ($path_segments as &$segment) {
				$segment = urlencode($segment);
			}
			$cleaned_path = implode('/', $path_segments);

			// $cleaned_query = urlencode($url_componants['query']);
			// $cleaned_fragment = urlencode($url_componants['fragment']);

			$updated_url = $url_componants['scheme']."://".$url_componants['host'].$cleaned_path;
		} else {
			$updated_url = $url;
		}

		return $updated_url;
	}

	private function _convert_image_filepath_to_uri($img_filepath) {
		$stripped_string = str_replace($this->img_storage_directory, $this->uri_path."/", $img_filepath);
		return $stripped_string;
	}

	private function _find_get_stored_image($img_url,$is_thumbnail=FALSE) {
		$potential_stored_file = $this->img_storage_directory.$this->_get_image_signature($img_url);

		if($is_thumbnail) {
			$potential_stored_file .= "_THUMBNAIL";
		}

		$possible_files = glob($potential_stored_file.".*");

		if(count($possible_files) > 0) {
			$stored_img_path = $possible_files[0];
		} else {
			log_message("INFO","cannot find compressed image {$potential_stored_file} ...");
			$stored_img_path = "";
		} 

		return $stored_img_path;
	}

	private function _compress_image($image_filepath) { 
		log_message("INFO","Compressing image ...");

		$extension = $this->_get_image_extension($image_filepath);

		if($extension === '.jpg') {
			$this->_compress_jpeg_image($image_filepath);
		} else if($extension === '.png') {
			$this->_compress_png_image($image_filepath);
		}
	} 

	private function _compress_png_image($image_filepath) {
		$cmd = "optipng -preserve -quiet -o7 '{$image_filepath}'";
		shell_exec($cmd);
	}

	private function _compress_jpeg_image($image_filepath) {
		$cmd = "jpegtran -copy none -optimize -progressive -perfect -outfile '{$image_filepath}' '{$image_filepath}'";
		shell_exec($cmd);
	}

	private function _resize_image($original_image_path,$output_image_path,$min_width = 300) {
		//will create a thumbnail tile
		$cmd = "convert -thumbnail '{$min_width}^x{$min_width}' '{$original_image_path}' '{$output_image_path}'";
		log_message("INFO","creating thumbnail: {$cmd}");
		shell_exec($cmd);
	}

	private function check_if_file_exists_by_pattern($file_path) {
		$files = glob($file_path.".*");

		return ($files) ? TRUE : FALSE;
	}

	private function _get_image_extension($path) {
		$image_info = getImageSize($path);
		switch ($image_info['mime']) {
		case 'image/gif':
		    $extension = '.gif';
		    break;
		case 'image/jpeg':
		    $extension = '.jpg';
		    break;
		case 'image/png':        
		    $extension = '.png';
		    break;
		default:
			log_message("ERROR","Unknown image type for: {$path}");
		    $extension = '';
		    break;
		}

		return $extension;

	}

}

/* End of file conversation_model.php */
/* Location: ./application/models/conversation_model.php */
