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
    $render_str .= "'". $profile[0]->profile_pic. "'";
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
    $company = '';
    $name = '';
    $result = NULL;
    $profiles = NULL;
    if(isset($_GET['company'])){
        $company = $_GET['company'];
        if(isset($_GET['person'])){
            $name = $_GET['person'];
            //$url = 'https://api.rocketreach.co/v1/api/search?api_key=3E7k0123456789abcdef0123456789abcdef&';
            //$search_url = $param['url']. "?api_key=". $param['api_key'];
            $search_url = SEARCH_COMPANY_PERSON . "?api_key=". API_KEY;
            $search_url .= '&name='. $name. '&company='. $company;
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
    }


    $render_str = <<<TABLE
    <table id='search-result-table' class='js-dynamitable table table-bordered'>
			<thead>
				<tr>
					<th>Photo</td>
					<th>Name <input id='filter-person' class="filter-input" type="text" placeholder='filter name' value=""></td>
					<th>Current Employer <input id='filter-current-employer' class="filter-input" type="text" placeholder='filter current employer' value=""></td>
					<th>Detail</td>
				</tr>
				
			</thead>
            <tbody>
TABLE;
    
    foreach($profiles->profiles as $profile) {
        $render_str .= "<tr><td><img src='". $profile->profile_pic. "'width=100 height=100 /></td><td class='person-col'>". $profile->name. "</td><td class='current-employer-col'>". $profile->current_employer. "</td><td><a href='"
                . substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], "?")). "/". $param['url']. "?id=". $profile->id
                . "' >Profile</a></td><tr>";
    }
    $render_str .= "</tbody></table>";
    return $render_str;
}

add_shortcode('search_result_company_person', 'search_result_by_company_person');
add_shortcode('search_profile_id', 'search_profile_by_id');

function table_filter() {
    wp_register_style('search_result_c_p', plugins_url('search-result.css', __FILE__ ));
    wp_enqueue_style('search_result_c_p');
    wp_register_script( 'search_result_c_p', plugins_url('search-result.js', __FILE__ ));
    wp_enqueue_script('search_result_c_p');
}

add_action('wp_enqueue_scripts', 'table_filter');
