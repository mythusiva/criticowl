<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the 'welcome' class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['default_controller'] = 'homepage/index';

//top bar
$route['home'] = 'homepage/index';

$route['terms-and-conditions'] = 'homepage/terms_and_conditions';
$route['privacy-policy'] = 'homepage/privacy_policy';
$route['about-us'] = 'homepage/about_us';
$route['contact-us'] = 'homepage/contact_us';
$route['submit-contact-us'] = 'homepage/submit_contact_us';

$route['movies'] = 'homepage/movies';
$route['movies/(:num)'] = 'homepage/movie_related_posts/$1';
$route['movies/(:num)/(:any)'] = 'homepage/movie_related_posts_by_uri/$1/$2';

$route['reviews'] = 'homepage/reviews';
$route['articles'] = 'homepage/articles';
$route['exclusive_articles'] = 'homepage/exclusive_articles';

//public routes
// $route['section/(:num)'] = 'homepage/landing_page/$1';
// $route['news/(:any)'] = 'homepage/news_landing_page/$1';
$route['article/(:any)'] = 'homepage/article_landing_page/$1';
$route['review/(:any)'] = 'homepage/review_landing_page/$1';

//admin pages
$route['admin'] = 'admin/index';
$route['admin/login'] = 'admin/login';

$route['404_override'] = 'homepage/page_not_found';

$route['seo/sitemap\.xml'] = "seo/sitemap";
$route['rss\.xml'] = "homepage/rss_feed";

$route['browser_fail'] = "homepage/browser_fail";


/* End of file routes.php */
/* Location: ./application/config/routes.php */
