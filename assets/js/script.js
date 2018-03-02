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

});

$( window ).resize(function() {
    
});