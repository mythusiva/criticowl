<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
require APPPATH.'/libraries/JSMin.php';

class Minifier {

	public function __construct() {
		$this->ci = &get_instance();
	}
	
	public function get_url_combine_and_compress_links($output_name,$type,$array_of_links) {

		$output_file = "/".strtolower($type)."/{$output_name}";
		if(file_exists(getcwd().$output_file)) {
			$out = $output_file;
		} else {
			if($type === "CSS") {
				$contents = $this->_combine_css($array_of_links);
				$contents = $this->_compress_css_contents($contents);
				$this->_output_compressed_css_file($contents,$output_name);
				$out = "/css/{$output_name}";
			} elseif ($type === "JS") {
				$contents = $this->_minify_and_combine_js($array_of_links);
				// $contents = $this->_compress_js_contents($contents);
				$this->_output_compressed_js_file($contents,$output_name);
				$out = "/js/{$output_name}";
			}
		}

		return $out;
	}

	private function _output_compressed_css_file($contents,$output_name) {
		$output_file = getcwd()."/css/{$output_name}";

		file_put_contents($output_file, $contents);
	}

	private function _output_compressed_js_file($contents,$output_name) {
		$output_file = getcwd()."/js/{$output_name}";

		file_put_contents($output_file, $contents);
	}

	private function _combine_css($array_of_links) {
		$combined_css = '';
		foreach ($array_of_links as $url) {
			$combined_css .= $this->_download_content($url);
			$combined_css .= " ";
		}
		return $combined_css;
	}

	private function _minify_and_combine_js($array_of_links) {
		$combined_js = '';
		foreach ($array_of_links as $url) {
			$combined_js .= JSMin::minify($this->_download_content($url));
			$combined_js .= " ";
		}
		return $combined_js;
	}

	private function _download_content($url) {
		if(!is_production()) {
			$username = 'tester';
			$password = 'welcome';
			$context = stream_context_create(array(
			    'http' => array(
			        'header'  => "Authorization: Basic " . base64_encode("$username:$password")
			    )
			));
			$contents = file_get_contents($url,false,$context);
		} else {
			$contents = file_get_contents($url);
		}
		return $contents;
	}

	private function _compress_css_contents($contents) {
		// Remove comments
		$contents = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $contents);

		// Remove space after colons
		$contents = str_replace(': ', ':', $contents);

		// Remove whitespace
		$contents = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $contents);

		return $contents;
	}

	// private function _compress_js_contents($contents) {
	// 	// Remove comments
	// 	// $contents = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $contents);

	// 	// Remove space after colons
	// 	// $contents = str_replace(': ', ':', $contents);

	// 	// Remove whitespace
	// 	$contents = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $contents);

	// 	return $contents;
	// }
	
}
