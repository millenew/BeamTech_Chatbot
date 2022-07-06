<style>
	#chat_convo{
		max-height: 65vh;
	}
	#chat_convo .direct-chat-messages{
		min-height: 250px;
		height: inherit;
	}
	#chat_convo .card-body {
		overflow: auto;
	}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-8 <?php echo isMobileDevice() == false ?  "offset-2" : '' ?>">
			<div class="card direct-chat direct-chat-primary" id="chat_convo">
              <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">Ask Me</h3>
 			<div class="card-tools">
            <div class="btn-group nav-link">
                  <button type="button" class="btn btn-rounded badge badge-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                    <span class="ml-3">Talk To Someone</span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" href="<?php echo base_url.'php-chat-app-main/?index.php' ?>"><span class="fa fa-user"></span>Guidance Counselor/<br>&nbsp;&nbsp;&nbsp;&nbsp;Scholarship Coordinator</a>
                     <a class="dropdown-item" href="<?php echo base_url.'chat_clinic_db/?index.php' ?>"><span class="fa fa-user"></span>Campus Nurse</a>
                      <a class="dropdown-item" href="<?php echo base_url.'chat_sas_db/?index.php' ?>"><span class="fa fa-user"></span>Head, SAS</a>
                      <a class="dropdown-item" href="<?php echo base_url.'chat_library_db/?index.php' ?>"><span class="fa fa-user"></span>Librarian</a>
                      <a class="dropdown-item" href="<?php echo base_url.'chat_sports_db/?index.php' ?>"><span class="fa fa-user"></span>Sports & Cultural Program</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo base_url.'/classes/Login.php?f=logout' ?>"><span class="fas fa-sign-out-alt"></span> ADMIN</a>
                  </div>
              </div>
          </li>
            
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages">
                  <!-- Message. Default to the left -->
                  <div class="direct-chat-msg mr-4">
                    <img class="direct-chat-img border-1 border-primary" src="<?php echo validate_image($_settings->info('bot_avatar')) ?>" alt="message user image">
                    <!-- /.direct-chat-img -->
 
                    <div class="direct-chat-text">
                      <?php echo $_settings->info('intro') ?>
                    </div>
                    <!-- /.direct-chat-text -->
                  </div>
                  <!-- /.direct-chat-msg -->

                  
                  <!-- /.contacts-list -->
                </div>
                <div class="end-convo"></div>
                <!-- /.direct-chat-pane -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <form id="send_chat" method="post">
                  <div class="input-group" class="dropdown">
                    <input type="text" id="msgbox" name="message" placeholder="Type Message ..." onkeyup="javascript:load_data(this.value)" class="form-control form-control-lg" required=""/>
                    <span class="input-group-append" id="search_result" style="position: absolute;top: 47px;"></span>
                    <span class="input-group-append">
                      <button type="submit" class="btn btn-primary">Send</button>
                    </span>
                  </div>
                </form>
              </div>
              <!-- /.card-footer-->
            </div>
		</div>
	</div>
</div>
<div class="d-none" id="user_chat">
	<div class="direct-chat-msg right  ml-4">
        <img class="direct-chat-img border-1 border-primary" src="<?php echo validate_image($_settings->info('user_avatar')) ?>" alt="message user image">
        <!-- /.direct-chat-img -->
        <div class="direct-chat-text"></div>
        <!-- /.direct-chat-text -->
    </div>
</div>
<div class="d-none" id="bot_chat">
	<div class="direct-chat-msg mr-4">
        <img class="direct-chat-img border-1 border-primary" src="<?php echo validate_image($_settings->info('bot_avatar')) ?>" alt="message user image">
        <!-- /.direct-chat-img -->
        <div class="direct-chat-text last_resp" onclick="last_resp1(this)"></div>
        <!-- /.direct-chat-text -->
  </div>
</div>
<script type="text/javascript">
function last_resp1(event)
{
	$('[name="message"]').val($(event).html());
}
function get_text(event)
{
	var string = event.textContent;

	document.getElementsByName('message')[0].value = string;
	
	document.getElementById('search_result').innerHTML = '';
}

function load_data(query)
{
	if(query.length > 2)
	{
		var form_data = new FormData();

		form_data.append('query', query);

		var ajax_request = new XMLHttpRequest();

		ajax_request.open('POST', 'process_data.php');

		ajax_request.send(form_data);

		ajax_request.onreadystatechange = function()
		{
			if(ajax_request.readyState == 4 && ajax_request.status == 200)
			{
				var response = JSON.parse(ajax_request.responseText);

				var html = '<div class="list-group">';

				if(response.length > 0)
				{
					for(var count = 0; count < response.length; count++)
					{
						html += '<a href="#" class="list-group-item list-group-item-action" onclick="get_text(this)">'+response[count].question+'</a>';
					}
				}
				else
				{
					html += '<a href="#" type="submit" class="list-group-item list-group-item-action"></a>';
				}

				html += '</div>';

				document.getElementById('search_result').innerHTML = html;
			}
		}
	}
	else
	{
		document.getElementById('search_result').innerHTML = '';
	}
}

	$(document).ready(function(){
		
		$('[name="message"]').keypress(function(e){
			console.log()
			if(e.which === 13 && e.originalEvent.shiftKey == false){
				$('#send_chat').submit()
				return false;
			}
		})
		$('#send_chat').submit(function(e){
			e.preventDefault();
			var message = $('[name="message"]').val();
			if(message == '' || message == null) return false;
			var uchat = $('#user_chat').clone();
			uchat.find('.direct-chat-text').html(message);
			$('#chat_convo .direct-chat-messages').append(uchat.html());
			$('[name="message"]').val('')
			$("#chat_convo .card-body").animate({ scrollTop: $("#chat_convo .card-body").prop('scrollHeight') }, "fast");

			$.ajax({
				url:_base_url_+"classes/Master.php?f=get_response",
				method:'POST',
				data:{message:message},
				error: err=>{
					console.log(err)
					alert_toast("An error occured.",'error');
					end_loader();
				},
				success:function(resp){
					if(resp){
						resp = JSON.parse(resp)
						if(resp.status == 'success'){
							var bot_chat = $('#bot_chat').clone();
								bot_chat.find('.direct-chat-text').html(resp.message);
								$('#chat_convo .direct-chat-messages').append(bot_chat.html());
								$("#chat_convo .card-body").animate({ scrollTop: $("#chat_convo .card-body").prop('scrollHeight') }, "fast");
						}
					}
				}
			})
		})

	})
</script>
