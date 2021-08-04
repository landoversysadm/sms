/**
 * Author: Olayiwola Odunsi
 * Github: https://github.com/olaysco
 */
$(document).ready(function(e){

    //hides preloader on page load

    setTimeout(function(){
        $('.loader-container').hide('5000',function(){
            $(this).hide('slow','easeout');
            $('#full-container').show();
        });
    }, 1000)
});
