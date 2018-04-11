$( document ).ready(function() {

    $("#overviewsubmit").click(function(){
        var value = $("#overviewinput").val(),
            url = $(this).data("url").replace( '[search]', value );

        if ( value != '' ) {
            window.location.href = url;
        } else {
            window.location.href = $(this).data("baseurl");
        }
    });
    $("#overviewinput").on('keyup', function (e) {
        if (e.keyCode == 13) {
            $("#overviewsubmit").click();
        }
    });
    

    $("#change_lang_en").click(function(){
        document.cookie = "lang=en; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
        location.reload();
    });
    $("#change_lang_nl").click(function(){
        document.cookie = "lang=nl; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
        location.reload();
    });


    $(".del-btn").click(function(e){
        e.preventDefault();
        $(this).dropdown();
        
        $(this).parent().find('.dropdown-menu .btn-danger').attr("href", this.href);
    });

});

$( window ).resize(function() {
    
});