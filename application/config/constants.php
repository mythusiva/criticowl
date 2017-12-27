<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

define('MAX_LOGIN_ATTEMPTS', 3);

//Permission Levels
define('PERMISSION_ADMIN','ADMIN');
define('PERMISSION_SYSTEM','SYSTEM');
define('PERMISSION_EDITOR','EDITOR');
define('PERMISSION_WRITER','WRITER');
define('PERMISSION_USER','USER');

//Release time
define('DATE_PRERELEASE','PRERELEASE');
define('DATE_POSTRELEASE','POSTRELEASE');
define('DATE_UNAVAILABLE','UNAVAILABLE');


//Media Types
define('MEDIA_MOVIE','MOVIE');
define('MEDIA_MUSIC','MUSIC');
define('MEDIA_NONE','NONE');

//Post Types
define('POST_ALL','ALL');
define('POST_NEWS','NEWS');
define('POST_ARTICLE','ARTICLE');
define('POST_REVIEW','REVIEW');
define('POST_EXCLUSIVE_ARTICLE','EXCLUSIVE_ARTICLE');

//Vote Ballot Type
define('BALLOT_WATCHED_IT','WATCHED_IT');
define('BALLOT_WANT_TO_WATCH_IT','WANT_TO_WATCH_IT');
define('BALLOT_RATING','RATING');

//Max allowed votes per IP Address
define('MAX_VOTE_WATCHED_IT',15);
define('MAX_VOTE_WANT_TO_WATCH_IT',15);
define('MAX_VOTE_RATING',15);


//Pagination Identifiers
define('PAGINATION_RECENT_POSTS','recent_posts');
define('PAGINATION_MOVIES_LIST','movies_list');
define('PAGINATION_MUSIC_LIST','music_list');
define('PAGINATION_MOVIE_POSTS_LIST','movie_posts_list');
define('PAGINATION_MUSIC_POSTS_LIST','music_posts_list');


//ShowMore Identifiers
define('SHOW_MORE_ALL_LIST','all_posts_list');
define('SHOW_MORE_REVIEWS_LIST','reviews_list');
define('SHOW_MORE_ARTICLES_LIST','articles_list');
define('SHOW_MORE_EXCLUSIVE_ARTICLES_LIST','exclusive_articles_list');

//Default pagination page sizes
define('PAGINATION_SMALL_PAGE',5);
define('PAGINATION_MEDIUM_PAGE',10);
define('PAGINATION_LARGE_PAGE',15);

//MISC
define('SEPARATOR','~#mythusiva#~');

//Caching
define('CACHEID_SYSTEM_QUOTATIONS','criticowl_system_quotations');

define('CACHE_TTL',3600);
define('CACHE_TTL_SHORT',300);
define('CACHE_TTL_MEDIUM',3600);
define('CACHE_TTL_LONG',7200);

//mobile to desktop pixel point
define('VIEWPORT_CHANGE_SIZE',980);

//max page width 
define('MAX_PAGE_WIDTH',1200);

//rating thresholds
define('RATING_THRESHOLD_WORTH_WATCHING',0.6);
define('RATING_THRESHOLD_CRITICOWL_APPROVED',0.8);


//dvd bluray
define('RELEASE_DATE_DVD_THRESHOLD',60);
define('RELEASE_DATE_BLURAY_THRESHOLD',60);

/* End of file constants.php */
/* Location: ./application/config/constants.php */