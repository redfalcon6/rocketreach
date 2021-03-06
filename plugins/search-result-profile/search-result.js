
jQuery(document).ready( function(){
    let searchData = {};
    let keys = ['person', 'company', 'title', 'location'];
    for (let key of keys) {
        let el = jQuery(`.search-element:not(.hidden) #${key}`);
        if (el.length) {
            searchData[key] = el.val()
        }
    }
    searchData['profile_uri'] = jQuery('#profile_uri').text();

    jQuery('html, body').animate({
        scrollTop: jQuery('#search-result-table').offset().top - 100
    }, 100);

    jQuery('#search-result-table').DataTable({
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "/wp-json/employers/profiles",
        "bSort": false,
        "fnServerParams": function ( aoData ) {
            for (let key in searchData) {
                aoData.push({
                    'name': key,
                    'value': searchData[key]
                })
            }
        },
        "sServerMethod": "POST"
    });


    jQuery('#email-label').on('click', function(e) {
        jQuery('#email-div').toggleClass('display-none');
        jQuery('#email-label i').toggleClass('active');
    });
    jQuery('#link-label').on('click', function(e) {
        jQuery('#link-div').toggleClass('display-none');
        jQuery('#link-label i').toggleClass('active');
    });
});
