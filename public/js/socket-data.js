var socketData = function(auth_user_id, receiver_id) {
  var socket = io(':3000');
  var count = 0;
  var data = {
      id: auth_user_id
  }
  var data_single = {
      sender_id :auth_user_id,
      receiver_id: receiver_id,
      message :  "",
      seen: 0,
      created_at: moment().format("YYYY-MM-DD HH:mm:ss"),
      updated_at: moment().format("YYYY-MM-DD HH:mm:ss")
  };

  //-----------------Message and Notification sokjet -----------------------------------
  socket.emit('message_notifications', data);
  socket.on('notifications', function(message_notification_data){
    console.log(message_notification_data);
      if(auth_user_id != 0){
            $('.socket-messages-data').empty();
            count=0 ;
            $.each(message_notification_data,function (key,value){
              if (value.receiver_id == data.id) {
                if (value.seen == 0) {
                    count++;
                }
                $('.socket-messages-data').append(
                    '<li>' +
                    '<a href="/Mesajlar/'+value.id+'">' +
                    '<img src="/image/' + value.avatar + '" class="img-responsive pull-left" alt="Notification image" />' +
                    '<p>'+ '<span style="color:#0090D9;">' + value.name + '</span>' + ': '+ value.message +'</p></a></li>'
                  );
            };
          })

      }else{
          count = 0;
      }
      if (count > 0) {
          $('.socket-messages-number').empty();
          $('.socket-messages-count span').text('');
          $('.socket-messages-number').append('<a href="#" data-toggle="dropdown" class="dropdown-toggle socket-messages-count"><i class="fa fa-comments-o"></i> <span class="contact-auth-notification-number"> </span> </a>');
          $('.socket-messages-count span').text(count);
      }else{
          $('.socket-messages-number').empty();
          $('.socket-messages-number').append('<a href="#" data-toggle="dropdown" class="dropdownyoxdur-toggle socket-messages-count"><i class="fa fa-comments-o"></i></a>');
  //            $('.socket-messages-data').append('<li><a href="#"> <h4 class="text-center margin0">Mesajınız yoxdur</h4></a></li>');
      }
  });

  //OnClick message notification
    $('.clickNumber').on('click',function () {
        socket.emit('CountZero',data);
        socket.emit('message_notifications', data);
    })

  //notifications
  socket.emit('live_notification',data);
  $('#notification_chat').submit(function () {
      socket.emit('live_notification',data);
  })
  socket.on('live_noti',function(live_notification_data){
    $('.notification').html('');
  //      console.log(live_notification_data);

      $.each(live_notification_data,function (key,value) {
        var noti_text_els_user= (value.type_id == 2) ?'<span class="special-destek">'+ value.qarsiliqs_user_name +'</span> adlı istifadəçi istəyinizə dəstək vermək istəyir !':
        '<span class="special-istek">'+ value.qarsiliqs_user_name +'</span> adlı istifadəçi desteyinize istek vermək istəyir !';

        var noti_text_qars_user= (value.type_id == 2) ?'<span class="special-istek">'+ value.els_user_name +'</span> adlı istifadəçi desteyinizi qəbul etdi !':
        '<span class="special-istek">'+ value.els_user_name +'</span> adlı istifadəçi istəyinizi qəbul etdi !';

            if (value.els_user_id==data.id) {
              if (value.status==1) {
                $('.count').addClass('contact-auth-notification-number');
                 $('.contact-auth-notification-number').text(live_notification_data.length);
              }
              $('.notification').append('<li>'+
              '<a href="/Bildiriş/'+value.qarsiliqs_id +'"class="notification-seen">'+
              '<img src="/image/' + value.avatar + '" class="img-responsive pull-left" alt="Notification image" />'+
              '<p>'+noti_text_els_user+'</p>'+
              '</a>'+
              '</li>'
              );

            }
            else if(value.qarsiliqs_user_id==data.id && value.data_status==1){
              if (value.data_status==1) {
                $('.count').addClass('contact-auth-notification-number');
                 $('.contact-auth-notification-number').text(live_notification_data.length);
              }
              console.log('yes');
              $('.notification').append('<li>'+
              '<a href="/message/'+value.qarsiliqs_id +'"class="notification-seen">'+
              '<img src="/image/' + value.avatar + '" class="img-responsive pull-left" alt="Notification image" />'+
              '<p>'+noti_text_qars_user+'</p>'+
              '</a>'+
              '</li>'
              );
            }

      });
  });
//--------------------Message and Notification socket End -----------------------------------
//--------------------Notification_single chat code :D -----------------------------------
if (data_single.receiver_id != 0) {
  socket.emit('data',data_single);
  $('#notification_chat').submit(function () {
    socketData(auth_user_id,receiver_id);
      data_single.message = $('.footer-input').val();
      socket.emit('send_message', data_single);
      $('.footer-input').val("");
      $('.chat-body ul').text('');
      socket.on('all_data',function (allData) {
          $('.chat-body ul').text('');
          $.each(allData,function (key,value) {
              if (value.sender_id == auth_user_id && value.receiver_id == receiver_id){
                  $('.body-message').append(
                      '<li class="pull-right">' +
                      '<p class="message-content">'+String(value.message).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')+'</p>'+
                      '<img src="/image/'+value.avatar+'" class="message-img" alt="user-image">'+
                      '</li>'+
                      '<div class="clearfix"></div>'
                  );
              }else if (value.sender_id == receiver_id && value.receiver_id == auth_user_id){
                  $('.body-message').append(
                      '<li class="pull-left">' +
                      '<img src="/image/'+value.avatar+'" class="message-img" alt="user-image">'+
                      '<p class="message-content">'+String(value.message).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')+'</p>'+
                      '</li>'+
                      '<div class="clearfix"></div>'
                  );
              }
          });
      })
      return false;
  });
  socket.on('all_data',function (allData) {
      $('.chat-body ul').text('');
      $.each(allData,function (key,value) {
          if (value.sender_id == auth_user_id && value.receiver_id == receiver_id){
              $('.body-message').append(
                  '<li class="pull-right">' +
                  '<p class="message-content">'+String(value.message).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')+'</p>'+
                  '<img src="/image/'+value.avatar+'" class="message-img" alt="user-image">'+
                  '</li>'+
                  '<div class="clearfix"></div>'
              );
          }else if (value.sender_id == receiver_id && value.receiver_id == auth_user_id){
              $('.chat-body ul').append(
                  '<li class="pull-left">' +
                  '<img src="/image/'+value.avatar+'" class="message-img" alt="user-image">'+
                  '<p class="message-content">'+String(value.message).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')+'</p>'+
                  '</li>'+
                  '<div class="clearfix"></div>'
              );
          }
      });
  });

  // CHAT scroll
  $('.chat-body').animate({scrollTop: $('.chat-body').prop("scrollHeight")}, 500);

//--------------------Notification_single chat code END:D -----------------------------------





//--------------------Chat blade code -------------------------------------------------------
socket.emit('data',data_single);
$('#notification_chat').submit(function () {
  socketData(auth_user_id,receiver_id);
    data_single.message = $('.footer-input').val();
    socket.emit('send_message', data_single);
    $('.footer-input').val("");
//            $('.chat-body').text('');
    socket.on('all_data',function (allData) {
        $('.chat-body ul').text('');
        $.each(allData,function (key,value) {
            if (value.sender_id == auth_user_id && value.receiver_id == receiver_id){
                $('.body-message').append(
                    '<li class="pull-right">' +
                    '<p class="message-content">'+String(value.message).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')+'</p>'+
                    '<img src="/image/'+value.avatar+'" class="message-img" alt="user-image">'+
                    '</li>'+
                    '<div class="clearfix"></div>'
                );
            }else if (value.sender_id == receiver_id && value.receiver_id == auth_user_id){
                $('.chat-body ul').append(
                    '<li class="pull-left">' +
                    '<img src="/image/'+value.avatar+'" class="message-img" alt="user-image">'+
                    '<p class="message-content">'+String(value.message).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')+'</p>'+
                    '</li>'+
                    '<div class="clearfix"></div>'
                );
            }
        });
    })
    return false;
});
socket.on('all_data',function (allData) {
    $('.chat-body ul').text('');
    if (Object.keys(allData).length === 0) {
     $("#dsp_none").css("display","none");
    }
    $.each(allData,function (key,value) {
        $('.header-name').text(value.username);
        if (value.sender_id == auth_user_id && value.receiver_id == receiver_id){
            $('.chat-body ul').append(
                '<li class="pull-right">' +
                '<p class="message-content">'+String(value.message).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')+'</p>'+
                '<img src="/image/'+value.avatar+'" class="message-img" alt="user-image">'+
                '</li>'+
                '<div class="clearfix"></div>'
            );
        }else if (value.sender_id == receiver_id && value.receiver_id == auth_user_id){
            $('.chat-body ul').append(
                '<li class="pull-left">' +
                '<img src="/image/'+value.avatar+'" class="message-img" alt="user-image">'+
                '<p class="message-content">'+String(value.message).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')+'</p>'+
                '</li>'+
                '<div class="clearfix"></div>'
            );
            $('.chat-body').animate({scrollTop: $('.chat-body').prop("scrollHeight")}, 0.001);
        }
    });
});

//CHAT scroll
$('.chat-body').animate({scrollTop: $('.chat-body').prop("scrollHeight")}, 0.001);
}

//--------------------Chat blade code end ---------------------------------------------------
}
