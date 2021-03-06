@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div>
                <div class="chat">
                  <div class="chat-title">
                    <h1>Chatroom</h1>
                  </div>
                  <div class="messages">
                    <div class="messages-content">
                    </div>
                  </div>
                  <div class="message-box">
                    <input
                      type="text"
                      class="message-input"
                      placeholder="Type message..."
                      id="msgContent"
                    />
                    <button type="button" class="message-submit" id="btnSend">
                      Send
                    </button>
                  </div>
                </div>
              </div>
        </div>
        <div class="col-md-2">
            <div class="users-online">
                <button type="button" class="btn btn-primary">
                    Users online: <span class="badge badge-light" id="userOnline"></span>
                </button>
                <div class="online-users">
                    <div class="d-flex flex-column mb-3 available-users">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_script')
<script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>

<script>
    window.userData = <?php echo json_encode($data);?>;
    var currentUserId = window.userData.user.id;
    var userNotifications = <?php echo json_encode($userNotifications); ?>;

    $(document).ready(function() {
        var listMessages = <?php echo $messages->toJson();?>;
        loadMessages(listMessages);
        scrollToButtom('.messages');
        console.log(window.route.get('room_id'));
        getUserByRoomId();
        displayNotify();
    });

    let channel = 'room.'+window.route.get('room_id');
    window.Echo.join(channel)
        .here((users) => {
            console.log('===HERE===');
            usersOnline = users;
            getTotalUsersOnline(channel);
        })
        .joining((user) => {
            console.log('===JOINING===');
            usersOnline.push(user);
            getTotalUsersOnline(channel);
        })
        .leaving((user) => {
            const index = usersOnline.findIndex(item => item.id === user.id)
            if (index > -1) {
                usersOnline.splice(index, 1)
            }
            getTotalUsersOnline(channel);
        })
        .listen('PrivateMessage', function (data) {
            console.log('===LISTEN===');
            let chat = data.chat;
            chat.user = data.user;
            appendMessage(chat);
            $('.messages').animate({
                scrollTop: $('.messages').get(0).scrollHeight
            }, 1000);
        });


    //Notification Broadcast
    window.Echo.private('notify_users.' + currentUserId)
        .notification((notification) => {
            console.log("===Notify===");
            userNotifications.push(notification);
            displayNotify();
        });

    $('#btnSend').click(function(){
        $.ajaxSetup(ajaxSetupHeader);
        var messageContent = $("#msgContent").val();
        $.ajax({
            url: "/rooms/chat",
            method: "POST",
            data: { 
                message : messageContent,
                room_id:  window.route.get('room_id')
            }
        }).done(function( msg ) {
            console.log('DONE');
            $("#msgContent").val("");
        }).fail(function( jqXHR, textStatus ) {
            console.log( "sendChatMessage FAILED " + textStatus );
        });
    });

</script>
@endsection
