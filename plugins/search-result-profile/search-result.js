
jQuery(document).ready( function(){
    var filter_current_employer = jQuery('#filter-current-employer');
    var filter_person = jQuery('#filter-person');
    console.log(filter_person);
    function myFilter(input, filter_column) {
        var input, filter, table, tr, td, i, txtValue;
        filter = input.toUpperCase();
        table = document.getElementById("search-result-table");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByClassName(filter_column)[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }       
        }
    }

    filter_current_employer.on('change', function(e){
        myFilter(filter_current_employer.val(), 'current-employer-col');
    });

    filter_current_employer.on('keydown', function(e){
        filter_current_employer.trigger('change');   
    });

    filter_current_employer.on('keyup', function(e){
        filter_current_employer.trigger('change');
    });

    filter_current_employer.on('keypress', function(e){
        filter_current_employer.trigger('change');
    });

    filter_person.on('change', function(e){
        myFilter(filter_person.val(), 'person-col');
    });

    filter_person.on('keypress', function(e){
        filter_person.trigger('change');    
    });

    filter_person.on('keydown', function(e){
        filter_person.trigger('change');    
    });

    filter_person.on('keyup', function(e){
        filter_person.trigger('change');
    });

});
