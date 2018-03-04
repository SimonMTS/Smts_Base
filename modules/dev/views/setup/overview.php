<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info" role="alert">
            <span>If the request timesout you have to increase max_execution_time in 'php.ini'.</span>
            <i class="fa fa-refresh pull-right" style="font-size:21px"></i>
        </div>
        <button onclick="resetdb()" class="btn btn-primary">Reset database</button>
        <div class="text-center" id="info"></div>
    </div>
</div>

<script>
    function resetdb() {
        $('.alert-info i').addClass('fa-spin');
        $('button.btn').remove();

        if ( !window.XMLHttpRequest ) {
            alert("Your browser does not support the native XMLHttpRequest object.");
        } try {
            var xhr = new XMLHttpRequest();  
            xhr.previous_text = '';
                                        
            xhr.onerror = function() { document.getElementById("info").innerHTML += ' e3'; };
            xhr.onreadystatechange = function() {
                try {
                    if ( xhr.readyState == 4 ) {

                        var new_response = xhr.responseText.substring(xhr.previous_text.length);
                        var result = JSON.parse( new_response );
                        document.getElementById("info").innerHTML += result.msg + '';

                        if ( result.isDone ) {
                            $('.alert-info i.fa-spin').removeClass('fa-spin');
                            $('.alert-info span').html('Database reset completed. <a class="alert-link" href="<?=$GLOBALS['config']['base_url']?>">Go to home</a>');
                            $('.alert-info').addClass('alert-success');
                            $('.alert-info').removeClass('alert-info');
                        }

                    } else if ( xhr.readyState > 2 ) {

                        var new_response = xhr.responseText.substring(xhr.previous_text.length);
                        var result = JSON.parse( new_response );
                        document.getElementById("info").innerHTML += result.msg + '';
                                    
                        xhr.previous_text = xhr.responseText;

                        if ( result.isDone ) {
                            console.log('true');
                        }

                    }  
                }
                catch (e) {
                    $('.alert-info i.fa-spin').removeClass('fa-spin');
                    $('.alert-info span').html('Database reset failed.');
                    $('.alert-info').addClass('alert-danger');
                    $('.alert-info').removeClass('alert-info');
                }                     
            };
            xhr.open("GET", "http://localhost/topdownportaal/smts/setup/init/pwconfirmed", true);
            xhr.send();      
        }
        catch (e) {
            $('.alert-info i.fa-spin').removeClass('fa-spin');
            $('.alert-info span').html('Database reset failed.');
            $('.alert-info').addClass('alert-danger');
            $('.alert-info').removeClass('alert-info');
        }
    }
</script>