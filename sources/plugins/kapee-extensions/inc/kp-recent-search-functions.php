<?php 
/**
 Kapee recent search functions
*/

add_action( 'wp_ajax_nopriv_kapee_recent_search', 'kapee_recent_search'  );
add_action( 'wp_ajax_kapee_recent_search','kapee_recent_search'  );

function kapee_recent_search(){
	$action = isset($_POST['operation']) ? $_POST['operation'] : '';
	$search_keyword = isset($_POST['value']) ? $_POST['value'] : '';	
	$url = isset($_POST['url']) ? $_POST['url'] : '';
	$path = parse_url(get_option('siteurl'), PHP_URL_PATH);
	$host = parse_url(get_option('siteurl'), PHP_URL_HOST);
	$expiry = strtotime('+1 month');
	switch($action){
		case 'add':
			$recent_search_data = array('keyword'=> $search_keyword,
									'url'=> $serch_url);
			kapee_add_recentsearch_item($recent_search_data);
		break;
		case 'clear':
			setcookie('kapee_recent_search', '', $expiry, $path, $host);				
		break;
		case 'delete':
			$prev_value = $_COOKIE["kapee_recent_search"];
			$prev_value = stripslashes($prev_value);
			$prev_value = json_decode($prev_value, true);
			// delete current value from array and set cookie again
			$value = $search_keyword ;
			unset($prev_value[$value]);
			$new_value = json_encode($prev_value, true);
			setcookie('kapee_recent_search',$new_value, $expiry, $path, $host);
		break;		
	}
	
	die();
}

function kapee_add_recentsearch_item($data = array()){
	$path = parse_url(get_option('siteurl'), PHP_URL_PATH);
	$host = parse_url(get_option('siteurl'), PHP_URL_HOST);
	$expiry = strtotime('+1 month');
	if(isset($_COOKIE['kapee_recent_search'])){
		$prev_value = $_COOKIE["kapee_recent_search"];
		$prev_value = stripslashes($prev_value);
		$prev_value = json_decode($prev_value, true);
		//Add new value at beggining of the array
		array_unshift($prev_value, $data);
		$prev_value = kapee_unique_array($prev_value,'keyword');
		$recent_serch_limit = 5;
		$prev_value = array_slice($prev_value, 0, $recent_serch_limit, true);
		$new_value = json_encode($prev_value, true);
		setcookie('kapee_recent_search', $new_value, $expiry, $path, $host);
	}else{
		$recent_search = array();
		$recent_search[] = $data;
		$cookie_recent_search = json_encode($recent_search);
		setcookie('kapee_recent_search',  $cookie_recent_search, $expiry, $path, $host);
	}
}
/*
	https://stackoverflow.com/questions/307674/how-to-remove-duplicate-values-from-a-multi-dimensional-array-in-php
*/
function kapee_unique_array($array,$key){
   $temp_array = [];
   foreach ($array as &$v) {
	   if (!isset($temp_array[$v[$key]]))
	   $temp_array[$v[$key]] =& $v;
   }
   $array = array_values($temp_array);
   return $array;

}