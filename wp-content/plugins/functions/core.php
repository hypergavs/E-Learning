<?php
/*
Plugin Name: Functions
Version: 1.0
Author: GM

Copyright 2018-2019 GM
*/
show_admin_bar(false);


function get_what_day($datetime){
	$timestamp = $datetime;
	
	$today = new DateTime(); // This object represents current date/time
	$today->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison
	
	$match_date = DateTime::createFromFormat( "Y.m.d\\TH:i", $timestamp );
	$match_date->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison
	
	$diff = $today->diff( $match_date );
	$diffDays = (integer)$diff->format( "%R%a" ); // Extract days count in interval
	
	switch( $diffDays ) {
		case 0:
			return "//Today";
			break;
		case -1:
			return "//Yesterday";
			break;
		case +1:
			return "//Tomorrow";
			break;
		default:
			return "//Sometime";
	}	
}


function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}


function create_error_msg($str){
	$msg = explode(";", $str);
	foreach($msg as $m){
		$a .= $m.'<br/><br/>';	
	}	
	return rtrim($a, "<br/><br/>");
}

function only_admin(){
	$user_info = get_user_by("ID", get_current_user_id());
	if($user_info->roles[0]=="administrator"){
		return true;	
	}else{
		return false;
	}
}

function only_teacher(){
	$user_info = get_user_by("ID", get_current_user_id());
	if($user_info->roles[0]=="teacher"){
		return true;	
	}else{
		return false;
	}
}

function only_student(){
	$user_info = get_user_by("ID", get_current_user_id());
	if($user_info->roles[0]=="student"){
		return true;	
	}else{
		return false;
	}
}


function gen_report_date($type){
	date_default_timezone_set("Asia/Manila");
	$today = date("Y-m-d");
	$previous_week = strtotime("-1 week +1 day");
	switch($type){
		case "today":
			return " BETWEEN '".date("Y-m-d", strtotime("+0 day", strtotime($today)))." 00:00:00' and '".date("Y-m-d", strtotime("+0 day", strtotime($today)))." 23:59:59'";
			break;
		case "yesterday":
			return " BETWEEN '".date("Y-m-d", strtotime("-1 day", strtotime($today)))." 00:00:00' and '".date("Y-m-d", strtotime("-1 day", strtotime($today)))." 23:59:59'";
			break;
		case "this_week":
			return " BETWEEN '".date("Y-m-d", strtotime("last sunday midnight",strtotime($today)))." 00:00:00' and '".date("Y-m-d", strtotime("next saturday",strtotime(date("Y-m-d", strtotime("last sunday midnight",strtotime($today))))))." 23:59:59'";
			break;
		case "last_week":
			return " BETWEEN '".date("Y-m-d", strtotime("last sunday midnight",$previous_week))." 00:00:00' and '".date("Y-m-d", strtotime("next saturday",strtotime(date("Y-m-d", strtotime("last sunday midnight",$previous_week)))))." 23:59:59'";
			break;
		case "this_month":
			return " BETWEEN '".date("Y-m-01")." 00:00:00' and '".$today." 23:59:59'";
			break;
		case "last_month":
			return " BETWEEN '".date("Y-m-d", strtotime("first day of previous month", strtotime($today)))." 00:00:00' and '".date("Y-m-d", strtotime("last day of previous month", strtotime($today)))." 23:59:59'";
			break;
		case "this_year":
			return " BETWEEN '".date("Y-01-01")." 00:00:00' and '".$today." 23:59:59'";
			break;
		case "last_year":
			return " BETWEEN '".date("Y-01-01", strtotime("-1 year", strtotime($today)))." 00:00:00' and '".date("Y-12-31", strtotime("-1 year", strtotime($today)))." 23:59:59'";
			break;
		case "all_time":
			return "all_time";
			break;
		case "custom":
			return "custom";
			break;
		default: 
			return "all_time";	
	}	
}


function gen_as_of($type){
	date_default_timezone_set("Asia/Manila");
	$today = date("Y-m-d");
	$previous_week = strtotime("-1 week +1 day");
	switch($type){
		case "today":
			return " as of '".$today."' to '".date("Y-m-d", strtotime("+1 day", strtotime(date($today))))."'";
			break;
		case "yesterday":
			return " as of '".date("Y-m-d", strtotime("-1 day", strtotime(date($today))))."' to '".date("Y-m-d", strtotime("-1 day", strtotime(date($today))))."'";
			break;
		case "this_week":
			return " as of '".date("Y-m-d", strtotime("last sunday midnight",strtotime($today)))."' to '".date("Y-m-d", strtotime("next saturday",strtotime(date("Y-m-d", strtotime("last sunday midnight",strtotime($today))))))."'";
			break;
		case "last_week":
			return " BETWEEN '".date("Y-m-d", strtotime("last sunday midnight",$previous_week))."' to '".date("Y-m-d", strtotime("next saturday",strtotime(date("Y-m-d", strtotime("last sunday midnight",$previous_week)))))."'";
			break;
		case "this_month":
			return " as of '".date("Y-m-01")."' to '".$today."'";
			break;
		case "last_month":
			return " as of '".date("Y-m-d", strtotime("first day of previous month", strtotime($today)))."' to '".date("Y-m-d", strtotime("last day of previous month", strtotime($today)))."'";
			break;
		case "this_year":
			return " as of '".date("Y-01-01")."' and '".$today."'";
			break;
		case "last_year":
			return " as of '".date("Y-01-01", strtotime("-1 year", strtotime($today)))."' to '".date("Y-12-31", strtotime("-1 year", strtotime($today)))."'";
			break;
		case "all_time":
			return "all_time";
			break;
		case "custom":
			return "custom";
			break;
		default: 
			return "all_time";	
	}	
}



function show_loan_detail_by($key, $val){
	global $wpdb;
	$results = $wpdb->get_row("Select * From gm_tbl_loans Where ".$key."='".$val."'");
	return $results;
}

?>