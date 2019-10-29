<?php
/**
 * Plugin Name: search result profile
 * Description: RobotReach Search Functionality With Company Name And Person Name.
 * Version: 1.0
 */

include('rocket-reach-infor.php');

function search_profile_by_id(){
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        //$url = 'https://api.rocketreach.co/v1/api/lookupProfile?api_key=3E7k0123456789abcdef0123456789abcdef&id='. $id;
        $search_url = SEARCH_PROFILE . "?api_key=" . API_KEY . "&id=" . $id;
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json",
                'method'  => 'GET'
            )
        );
        $result = NULL;
        $context  = stream_context_create($options);
        $result = file_get_contents($search_url, false, $context);
        $profile = json_decode($result);
        if ($result === FALSE) {  }
    }
    $render_str = "<div class='profile-container'>
			<div class='profile-image-content'>
                <img src=";
    $pic_url = $profile[0]->profile_pic ? $profile[0]->profile_pic : plugins_url('person.png', __FILE__ );
        
    $render_str .= "'". $pic_url . "'";
    $render_str .= "class='profile-image' />
			</div>
			<div class='profile-content'>
				<div class='profile-item'>
					<label>Name</label>
                    <p>";
    $render_str .= $profile[0]->name . "</p>
				</div>
				<div class='profile-item'>
					<label>Current Title</label>
                    <p>".  $profile[0]->current_title."</p>
				</div>
				<div class='profile-item'>
					<label>Current Employer</label>
                    <p>" .  $profile[0]->current_employer . "</p>
				</div>
				<div class='profile-item'>
					<label>Location</label>
                    <p>" . $profile[0]->location . "</p>
				</div>
				<div class='profile-item'>
					<label>Current Work Email</label>
                    <p>" . $profile[0]->current_work_email . "</p>
				</div>
				<div class='profile-item'>
					<label>Current Title</label>
                    <p>" . $profile[0]->current_title . "</p>
				</div>
				<div class='profile-item'>
					<label>Current Personal Email</label>
                    <p>" . $profile[0]->current_personal_email . "</p>
				</div>
				
				<div class='profile-item'>
					<label>Linkedin URL</label>
                    <p><a href='" . $profile[0]->linkedin_url . "'>". $profile[0]->linkedin_url. "</a>
                    </p>
				</div>
				
			</div>
			<div class='profile-content'>
				<div class='profile-item-email'>
					<label>Emails</label>
                    <p>";
    foreach($profile[0]->emails as $email){
        $render_str .= "<div>". $email->email. "</div>";
    }
    $render_str .= "</p>
				</div>
			</div>
			<div class='profile-content'>
				<div class='profile-item-link'>
					<label>Links</label>
					<div>
                    <ul>";
						foreach($profile[0]->links as $key => $link){
							$render_str .= '<li><a href="'. $link. '">'. $key. "</a></li>";
						}
    $render_str .= "</ul></div></div></div></div>";
    return $render_str;
}

function search_result_by_company_person($param) {
    $no_whitespaces = preg_replace( '/\s*,\s*/', ',', filter_var( $param['search_key'], FILTER_SANITIZE_STRING ) ); 
    if(strlen($no_whitespaces) == 0){
        $s_key_array = ['0' => 'company', '1' => 'person'];
    } else {
        $s_key_array = explode( ',', $no_whitespaces );
    }
    $result = NULL;
    $profiles = NULL;
    $search_url = SEARCH_COMPANY_PERSON . "?api_key=". API_KEY;
    $searchable = 0;
    $search_all = "";
    foreach ( $s_key_array as $k => $s_key ) { 
        if(isset($_GET[$s_key])){
            $searchable = 1;
            $search_value = $_GET[$s_key];    
            $search_key = $s_key=='person'?'name':$s_key;
            $search_url .= '&'. $search_key. '='. $search_value;
            if($s_key == 'company') {
                $search_all = substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], "?")). '?company='. $search_value;
            }
        }
    }
    if($searchable == 1){
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json",
                'method'  => 'GET'
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($search_url, false, $context);
        $profiles = json_decode($result);
        if ($result === FALSE) { /* Handle error */ }
    }

    $search_all_href = strlen($search_all) > 0 ? "href='". $search_all . "'" : "";
    $render_str = "<a class='btn btn-red pull-right'". $search_all_href. "> Show All Employees </a>";
    $render_str .= <<<TABLE
    <table id='search-result-table' class='table table-bordered'>
			<thead>
				<tr>
					<th></th>
					<th>Name<br><input id='1' class="filter-input" type="text" placeholder='' value=""></th>
                    <th>Employer<br><input id='2' class="filter-input" type="text" placeholder='' value=""></th>
                    <th>Title<br><input id='3' class="filter-input" type="text" placeholder='' value=""></th>
					<th>Contact Detail</th>
				</tr>
				
			</thead>
            <tbody>
TABLE;
    
    foreach($profiles->profiles as $profile) {
        $pic_url = $profile->profile_pic ? $profile->profile_pic : plugins_url('person.png', __FILE__ );
        $render_str .= "<tr><td><img class='search-image' src='". $pic_url . "'width=100 height=100 /></td><td class='person-col'>". $profile->name. "</td><td class='current-employer-col'>". $profile->current_employer
                    . "</td>". "<td class='current-title-col'>".$profile->current_title. "</td><td><a class='btn btn-red' href='"
                    . substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], "?")). "/". $param['url']. "?id=". $profile->id
                    . "' >Profile</a></td></tr>";
    }
    $render_str .= "</tbody></table>";
    return $render_str;
}

add_shortcode('search_result_company_person', 'search_result_by_company_person');
add_shortcode('search_profile_id', 'search_profile_by_id');

function datatable_control() {
    wp_register_style('search_result_c_p', plugins_url('search-result.css', __FILE__ ));
    wp_register_script( 'search_result_c_p', plugins_url('search-result.js', __FILE__ ));
    wp_enqueue_style('search_result_c_p');
    wp_enqueue_script('search_result_c_p');
    wp_register_script( 'jquery', 'https://code.jquery.com/jquery-1.12.4.min.js');
    wp_enqueue_script('jquery');
    wp_register_script( 'datatable', 'https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js');
    wp_register_style( 'datatable', 'https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css');
    wp_enqueue_style('datatable');
    wp_enqueue_script('datatable');
    
}

add_action('wp_enqueue_scripts', 'datatable_control');
