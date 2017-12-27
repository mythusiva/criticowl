<?php

function get_cached_item($cache_id) {
	# PHP7 extension issues...
	return false;


	// There may be an issue with APC when fetching an item
	// that was not garbage collected correctly.
	try {
		$cached_item = apc_fetch(md5(base_url()).$cache_id);
	} catch(Exception $e) {
		$cached_item = FALSE;
		log_message("INFO","Warning: APC had a problem fetching. Msg -> ".$e->getMessage());
	}

	return $cached_item;
}

function set_cached_item($cache_id,$value,$ttl = CACHE_TTL) {
	# PHP7 extension issues...
	return false;

	return apc_store(md5(base_url()).$cache_id,$value,$ttl);
}

function delete_cached_item($cache_id) {
	# PHP7 extension issues...
	return false;

	return apc_delete(md5(base_url()).$cache_id);
}

function clear_all_cache() {
	# PHP7 extension issues...
	return false;
	
	apc_clear_cache();
	apc_clear_cache('user');
	apc_clear_cache('opcode');
}