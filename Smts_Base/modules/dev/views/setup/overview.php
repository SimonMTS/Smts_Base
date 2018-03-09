<div class="row">
	<div class="col">
		<nav class="mt-4" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= Smts::$config['BaseUrl'] ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?= Smts::$config['BaseUrl'] ?>dev">Dev</a></li>
				<li class="breadcrumb-item active">Setup</li>
			</ol>
		</nav>
	</div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info" role="alert">
            <span>If the request timesout you have to increase max_execution_time in 'php.ini'.</span>
            <img class="alert-spinner" src="<?= Smts::$config['BaseUrl'] ?>assets/spinner.svg" style="display: none;">
        </div>
        
        <button onclick="resetdb()" class="btn btn-primary">Reset database</button>
        <div class="text-center" id="info"></div>
    </div>
</div>

<script>
    function resetdb() {
        $('.alert-info .alert-spinner').show();
        $('button.btn').remove();

        if ( !window.XMLHttpRequest ) {
            alert("Your browser does not support the native XMLHttpRequest object.");
        } try {
            var xhr = new XMLHttpRequest();  
            xhr.previous_text = '';
                                        
            xhr.onerror = function() { console.log('error');};
            xhr.onreadystatechange = function() {
                
                try {
                    if ( xhr.readyState > 2 ) {

                        var new_response = xhr.responseText.substring(xhr.previous_text.length);
                        var result = JSON.parse( new_response );
                        document.getElementById("info").innerHTML += result.msg + '';
                    
                        xhr.previous_text = xhr.responseText;

                        if ( result.isDone ) {
                            $('.alert-info .alert-spinner').hide();
                            $('.alert-info span').html('Database reset completed. <a class="alert-link" href="<?=Smts::$config['BaseUrl'] ?>">Go to home</a>');
                            $('.alert-info').addClass('alert-success');
                            $('.alert-info').removeClass('alert-info');
                        }

                    }  
                }
                catch (e) {
                    $('.alert-info .alert-spinner').hide();
                    $('.alert-info span').html('Database reset failed.');
                    $('.alert-info').addClass('alert-danger');
                    $('.alert-info').removeClass('alert-info');
                }                     
            };
            xhr.open("GET", "<?= Smts::$config['BaseUrl'] ?>dev/setup/init/pwconfirmed", true);
            xhr.send();      
        }
        catch (e) {
            $('.alert-info .alert-spinner').hide();
            $('.alert-info span').html('Database reset failed.');
            $('.alert-info').addClass('alert-danger');
            $('.alert-info').removeClass('alert-info');
        }
    }
</script>