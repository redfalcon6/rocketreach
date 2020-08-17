<?php
/**
 * Plugin Name: search result profile
 * Description: RobotReach Search Functionality With Company Name And Person Name.
 * Version: 1.0
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('rocket-reach-infor.php');

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}


$inserted = FALSE;

function datatable_control() {
    global $inserted;
    if ($inserted === FALSE) {
        wp_register_script( 'jquery', 'https://code.jquery.com/jquery-3.5.1.min.js');
        wp_enqueue_script('jquery');
        wp_register_script( 'datatable', 'https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js');
        wp_register_style( 'datatable', 'https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css');
        wp_enqueue_style('datatable');
        wp_enqueue_script('datatable');
        wp_register_script('datatable_responsive', "https://cdn.datatables.net/responsive/1.0.2/js/dataTables.responsive.js");
        wp_register_script('datatable_responsive_bootstrap', "//cdn.datatables.net/plug-ins/a5734b29083/integration/bootstrap/3/dataTables.bootstrap.js");
        wp_register_style('bootstrap_css', "https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css");
        wp_enqueue_style('bootstrap_css');
        wp_register_script('bootstrap_js', "https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js");
        wp_enqueue_script('bootstrap_js');
        wp_register_style('fontawesome', "https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
        wp_enqueue_style('fontawesome');

        wp_register_style('search_result_c_p', plugins_url('search-result.css', __FILE__ ));
        wp_enqueue_style('search_result_c_p');
        wp_register_script( 'search_result_c_p', plugins_url('search-result.js', __FILE__ ));
        wp_enqueue_script('search_result_c_p');

        $inserted = TRUE;
    }
}


function search_profile_by_id(){
    datatable_control();

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
        <div class='profile-section'>
            <div class='profile-img'>
                <img src=";
                $pic_url = $profile[0]->profile_pic ? $profile[0]->profile_pic : plugins_url('person.png', __FILE__ );
                $render_str .= "'". $pic_url . "'/>";
    $render_str .= " 
            </div>
            <div class='profile-main'>
                <div class='row'>
                    <div class='col-lg-1 col-xs-2'><span><i class='colored-person-icon fa fa-user'></i></span></div>
                    <div class='col-lg-10 col-xs-10'><label class='name-label'>". $profile[0]->name . "</label></div>
				</div>
				<div class='row line-spacing-1'>
					<div class='col-lg-1 col-xs-2'></div>
                    <div class='col-lg-10 col-xs-10 main-profile'>". $profile[0]->current_title."</div>
				</div>
				<div class='row line-spacing-1'>
					<div class='col-lg-1 col-xs-2'></div>
                    <div class='col-lg-10 col-xs-10 main-profile'>". $profile[0]->current_employer . "</div>
				</div>
				<div class='row line-spacing-1'>
					<div class='col-lg-1 col-xs-2'></div>
                    <div class='col-lg-10 col-xs-10 main-profile'>". $profile[0]->location . "</div>
				</div>
				<div class='row line-spacing-2'>
					<div class='col-lg-1 col-xs-2'><span><i class='colored-mail-icon fa fa-envelope'></i></span></div>
                    <div class='col-lg-10 col-xs-10'><label class='mail-label'>Work:</label><a class='mail-link' href='mailto:". $profile[0]->current_work_email . "'>". $profile[0]->current_work_email . "</a></div>
				</div>
				<div class='row line-spacing-2'>
					<div class='col-lg-1 col-xs-2'><span><i class='colored-mail-icon fa fa-envelope'></i></span></div>
                    <div class='col-lg-10 col-xs-10'><label class='mail-label'>Personal:</label><a class='mail-link' href='mailto:". $profile[0]->current_personal_email. "'>". $profile[0]->current_personal_email . "</a></div>
                </div>
                <div class='row'>
					<div class='col-lg-1 col-xs-2'><div class='linkedin-label-container'><span><i class='colored-linkedin-icon fa fa-linkedin'></i></span></div></div>
                    <div class='col-lg-10 col-xs-10'><a class='linkedin-link' href='". $profile[0]->linkedin_url ."'>". $profile[0]->linkedin_url. "</a></div>
				</div>
            </div>
            <div class='profile-section-col'>
                <div class='search-employer-btn'>".
                "<a class='btn btn-red pull-right border-radius-zero' href='". $_SESSION['search_employer_url']. "'> Search Contacts </a>";
                /*
                    <div id='email-label' class='red-colored'><span><label class='profile-label-colored color-white'> Emails </label></span> <i class='fa fa-chevron-down pull-right'></i></div>
                    <div id='email-div' class='margin-left-15 display-none'>
                        <div>";
                            //$index = false;
                            //foreach($profile[0]->emails as $email){
                                
                            //    $render_str .= "<div class=''>". $email->email. "</div>";
                            //}
                            //if($index == true) $render_str .= '</div>';
                        $render_str .= "
                        </div>
                    </div>
                */
                $render_str .= "
                </div>
                <div class=''>";
                /*
                    <div id='link-label' class='red-colored'><span><label class='profile-label-colored color-white'> Links </label></span> <i class='fa fa-chevron-down pull-right'></i></div>
                    <div id='link-div' class='margin-left-15 display-none'>";
                        //$index = false;
                        //foreach($profile[0]->links as $key => $link){
                        //    $index = !($index);
                        //    if($index){
                        //        $render_str .= "<div class='row'><div class='col-md-6 col-xs-6'><a href='". $link. "'>". $key. "</a></div>";
                        //    } else {
                        //        $render_str .= "<div class='col-md-6 col-xs-6'><a href='". $link. "'>". $key. "</a></div></div>";
                        //    }
                        //}
                        //if($index == true) $render_str .= '</div>';
                    $render_str .= "
                    </div>
                */
                $render_str .= "
                </div>
            </div>
        </div>
        
    </div>";

    return '<div id="search-result-profile">' . $render_str . "</div>";
}

function search_result_by_company_person($param) {
    datatable_control();

    $_SESSION['search_employer_url'] = $_SERVER["REQUEST_URI"];
    $no_whitespaces = preg_replace( '/\s*,\s*/', ',', filter_var( $param['search_key'], FILTER_SANITIZE_STRING ) ); 
    $s_key_array = [];

    if(strlen($no_whitespaces) == 0){
        $s_key_array = ['person', 'company', 'title', 'location'];
    } else {
        $s_key_array = explode( ',', $no_whitespaces );
    }
    $values = ['person' => '', 'company' => '', 'title' => '', 'location' => ''];
    foreach ($s_key_array as $key) {
        if (isset($_GET[$key])) {
            $values[$key] = $_GET[$key];
        }
    }

    $render_str = '
    <form method="get" class="form-horizontal" id="search-form">
        <h5> Search Contacts </h5>' . (in_array('person', $s_key_array) ? 
        '<div class="col-md-6 search-element">
            <label for="name" class="col-md-3">Name:</label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="person" id="person" value="' .$values['person']. '">
            </div>
        </div>' : '') . (in_array('company', $s_key_array) ? 
        '<div class="col-md-6 search-element">
            <label for="company" class="col-md-3">Employer:</label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="company" id="company" value="' . $values['company'] . '">
            </div>
        </div>' : '') . (in_array('title', $s_key_array) ? 
        '<div class="col-md-6 search-element">
            <label for="title" class="col-md-3">Title:</label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="title" id="title" value="' . $values['title'] . '">
            </div>
        </div>' : '') . (in_array('location', $s_key_array) ? 
        '<div class="col-md-6 search-element">
            <label for="location" class="col-md-3">Location:</label>

            <div class="col-md-9">
                <input type="text" class="form-control" name="location" id="location" value="' . $values['location'] . '">
            </div>
        </div>' : '') . 
        '
        <div class="search-btn search-element">
            <input type="submit" class="btn btn-red" value="Search">
        </div>
    </form> 
    <div id="profile_uri" class="hidden">' . substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], "?")). "/". $param['url'] . '</div>';

    $render_str .= "<h5> Employers </h5>";
    if(isMobile()){
        $render_str .= <<<TABLE_M
        <table id='search-result-table' class='table'>
            <thead>
                <tr>
                    <th>Detail</th>
                </tr>
            </thead>
        </table>
TABLE_M;
    }
    else {
        $render_str .= <<<TABLE
        <table id='search-result-table' class='table'>
                <thead>
                    <tr>
                        <th class='width-8'></th>
                        <th class='width-20'>Name<br></th>
                        <th class='width-20'>Employer<br></th>
                        <th class='width-20'>Title<br></th>
                        <th class='width-20'>Location<br></th>
                        <th class='width-12'>Contact Detail</th>
                    </tr>
                    
                </thead>
        </table>
TABLE;
    }

    return '<div id="search-result-profile">' . $render_str . "</div>";
}

function checkRemoteFile($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(curl_exec($ch)!==FALSE)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function search_profiles(WP_REST_Request $request) {
    $params = $request->get_body_params();

    $search_url = SEARCH_COMPANY_PERSON . "?api_key=". API_KEY;

    $query_keys = ['person', 'company', 'title', 'location'];

    foreach($query_keys as $key) {
        if (isset($params[$key])) {
            if ($key=='person') {
                $search_url .= "&name=";
            }
            else {
                $search_url .= "&$key=";
            }
            $search_url .= str_replace(' ', '%20', $params[$key]);
        }
    }
    if (isset($params['iDisplayStart'])) {
        $search_url .= "&start=" . $params['iDisplayStart'];
    }

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json",
            'method'  => 'GET'
        )
    );
    $context  = stream_context_create($options);
    $response = json_decode(file_get_contents($search_url, false, $context));

    $data = [];
    if (isset($response->profiles)) {
        foreach($response->profiles as $profile) {
            if(isMobile()) {
                $data[] = [
                    "<a class='btn btn-nopadding btn-nomargin btn-font-bold btn-font-size-larger btn-black' href='". $params['profile_uri']. "?id=". $profile->id. "' >". $profile->name. "</a>"
                            . "<br><label>". $profile->current_title. "</label>"
                            . "<br><label>". $profile->current_employer. "</label>"
                            . "<br><label>". $profile->location. "</label>"
                ];
            } else {
                $data[] = [
                    "<a href=" . $params['profile_uri'] . "?id=". $profile->id . "> <img class='search-image' src='". ($profile->profile_pic && checkRemoteFile($profile->profile_pic) ? $profile->profile_pic : plugins_url('person.png', __FILE__ )) . "' /></a>",
                    $profile->name,
                    $profile->current_employer,
                    $profile->current_title,
                    $profile->location,
                    "<a class='btn btn-red' href=" . $params['profile_uri'] . "?id=". $profile->id . ">Profile</a>",
                ];
            }
        }
    }

    return [
        'recordsTotal' => $response->pagination->total,
        'recordsFiltered' => $response->pagination->total,
        'data' => $data
    ];
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'employers', '/profiles', array(
      'methods' => 'POST',
      'callback' => 'search_profiles',
    ) );
} );

add_shortcode('search_result_company_person', 'search_result_by_company_person');
add_shortcode('search_profile_id', 'search_profile_by_id');
