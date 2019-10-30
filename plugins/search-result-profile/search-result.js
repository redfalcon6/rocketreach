
jQuery(document).ready( function(){
    
    var search_table = jQuery('#search-result-table').DataTable({
        "bSort": false,
        "lengthMenu": [[20, -1], [20, "All"]],
        "language": {
            "info": "Showing _START_ to _END_ of _TOTAL_ employees"
        },
        "responsive": true
    });

    jQuery('#search-result-table thead tr th').each( function (i) {
        jQuery( 'input', this ).on( 'keyup change', function () {
            if ( search_table.column(i).search() !== this.value ) {
                search_table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );

    jQuery('#show-all-btn').on('click', function(e) {
        jQuery('#search-result-table thead tr th input').each(function (i) {
            jQuery(this).val("");
            jQuery(this).trigger('change');
        });
    });

    jQuery('#email-label').on('click', function(e) {
        jQuery('#email-div').toggleClass('mobile-hidden');
        jQuery('#email-label i').toggleClass('active');
    });
    jQuery('#link-label').on('click', function(e) {
        jQuery('#link-div').toggleClass('mobile-hidden');
        jQuery('#link-label i').toggleClass('active');
    });
});
