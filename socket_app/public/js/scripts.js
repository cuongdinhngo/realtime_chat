var currentUserLogin;
var usersOnline;
var csrfToken = $('meta[name="csrf-token"]').attr('content');
var rooms;
var ajaxSetupHeader = {headers: {'X-CSRF-TOKEN': csrfToken}};
var allUsers = [];

function getUserByRoomId() {
    console.log('...getUserByRoomId...');
    $.ajaxSetup(ajaxSetupHeader);
    $.ajax({
        url: "/rooms/get-user-by-room",
        method: "GET",
        async: false,
        data: {
            room_id: window.route.get('room_id')
        }
    }).done(function( response ) {
        allUsers = response;
    }).fail(function( jqXHR, textStatus ) {
        console.log( "getUserByRoomId is Failed" + textStatus );
    }); 
}

function getRooms() {
    console.log('...getRooms...');
    $.ajaxSetup(ajaxSetupHeader);
    $.ajax({
        url: "/rooms/list-user-rooms",
        method: "GET",
        async: false,
    }).done(function( res ) {
        rooms = res;
        listAvailableRooms(rooms);
    }).fail(function( jqXHR, textStatus ) {
        console.log( "getRooms is Failed" + textStatus );
    });    
}

function listUsersOnline() {
    console.log('...listUsersOnline...');
    let item = '';
    if (allUsers.length > 0) {
        for (const member of allUsers) {
            let you = member.user_id == window.userData.user.id ? "(You)" : "";
            let status = usersOnline.findIndex(item => item.id === member.user_id) > -1 ? "on-circle" : "off-circle";
            item += `<div class="p-2" ><div class="${status}"></div><a href="/users/${member.user_id}">${member.user.name} ${you}</a></div>`;
        } 
    } else {
        for (const user of usersOnline) {
            item += `<div class="p-2"><a href="/users/${user.id}">${user.name}</a></div>`;
        }
    }
    $(".available-users").html(item);
}

function listAvailableRooms(rooms) {
    console.log('...listAvailableRooms...');
    let item = '';;
    for (const room of rooms) {
        let status = usersOnline.findIndex(item => room.ids.includes(item.id)) > -1 ? "on-circle" : "off-circle";
        item += `<div class="p-2"><div class="${status}"></div><a href="/rooms?room_id=${room.room_id}">${room.name}</a></div>`;
    }
    $(".available-rooms").html(item);
}

function getUserLogin() {
    console.log('...getUserLogin...');
    $.ajaxSetup(ajaxSetupHeader);
    $.ajax({
        url: "/users/current-user-login",
        method: "GET",
        async: false,
    }).done(function( user ) {
        currentUserLogin = user;
    }).fail(function( jqXHR, textStatus ) {
        console.log( "Request failed: " + textStatus );
    });
}

function getTotalUsersOnline() {
    console.log('...getTotalUsersOnline...');
    $('#userOnline').html(usersOnline.length);
    listUsersOnline();
}

function scrollToButtom(object) {
    $(`${object}`).animate({
            scrollTop: $(`${object}`).get(0).scrollHeight
        }, 1000);
}

function loadMessages(listMessages) {
    for (const property in listMessages) {
        appendMessage(listMessages[property]);
    }
}

function appendMessage(message) {
    let isCurrentUser = currentUserId == message.sender.id ? "is-current-user" : "";
    let item = `<div class="message ${isCurrentUser}">
        <div class="message-item user-name">
            ${message.sender.name}:
        </div>
        <div class="message-item text-message msg-container">
            ${message.content}
        </div>
    </div>`;
    $(".messages-content").append(item);
}

function displayNotify() {
    let qtyNotify = userNotifications.length;
    if (qtyNotify > 10) {
        $("#notify").css("display", "block");
        $("#notify").css("padding", "2px");
        $('#notify').html('10+');
    }
    if (qtyNotify > 0 && qtyNotify < 10) {
        $("#notify").css("display", "block");
        $('#notify').html(qtyNotify);
    }
}
