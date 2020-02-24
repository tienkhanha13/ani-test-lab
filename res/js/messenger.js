$(document).ready(function() {
    $.ajaxSetup({
       type: 'POST',
       headers: { "cache-control": "no-cache" }
    });
    get_recent_messenger_user();
    get_count_messenger_seen();
    $("#msg-friends").on("keyup", function() {
        var g = $(this).val().toLowerCase();
        $(".msg-user-list .userlist-box .media-body .chat-header").each(function() {
            var s = $(this).text().toLowerCase();
            $(this).closest('.userlist-box')[s.indexOf(g) !== -1 ? 'show' : 'hide']();
        });
    });
    $('#OpenImgUpload').click(function() {
        $('#imgupload').trigger('click');
    });
    $('.msg-send-chat').on('keyup', function(e) {
        msg_cfc(e);
    });
    $('.btn-msg-send').on('click', function(e) {
        msg_fc(e);
    });
    setInterval(function(){
        get_count_messenger_seen();
        get_new_messenger($('.media.userlist-box.active').attr('data-username'));
    }, 3000);
    function msg_cfc(e) {
        if (e.which == 13) {
            msg_fc(e);
        }
    };
    function msg_fc(e) {
        var id = date_now();
        $('.msg-block .main-friend-chat').append('' +
            '<div class="media chat-messages">' +
            '<div class="media-body chat-menu-reply">' +
            '<div class="">' +
            '<p class="chat-cont">' + $('.msg-send-chat').val() + '</p>' +
            '</div>' +
            '<p class="chat-time" id="'+id+'"><span class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></span></p>' +
            '</div>' +
            '</div>' +
            '');
        msg_fsc();
        send_messenger($('.media.userlist-box.active').attr('data-username'),$('.msg-send-chat').val(),id);
        $('.msg-send-chat').val(null);
    };

    function msg_frc(wrmsg) {
        setTimeout(function() {
            $('.msg-block .main-friend-chat').append('' +
                '<div class="media chat-messages typing">' +
                '<a class="media-left photo-table" href="#!"><img class="media-object img-radius img-radius m-t-5" src="assets/images/user/avatar-2.jpg" alt="Generic placeholder image"></a>' +
                '<div class="media-body chat-menu-content">' +
                '<div class="rem-msg">' +
                '<p class="chat-cont">Typing . . .</p>' +
                '</div>' +
                '<p class="chat-time">now</p>' +
                '</div>' +
                '</div>' +
                '');
            msg_fsc();
        }, 1500);
        setTimeout(function() {
            document.getElementsByClassName("rem-msg")[0].innerHTML = "<p class='chat-cont'>hello superior personality you write '" + wrmsg + " '</p>";
            $('.rem-msg').removeClass("rem-msg");
            $('.typing').removeClass("typing");
            msg_fsc();
        }, 3000);
    };
    var ps = new PerfectScrollbar('.msg-user-list.scroll-div', {
        wheelSpeed: 1,
        swipeEasing: 0,
        suppressScrollX: !0,
        wheelPropagation: 1,
        minScrollbarLength: 40,
    });
    var ps = new PerfectScrollbar('.msg-user-chat.scroll-div', {
        wheelSpeed: 2,
        swipeEasing: 0,
        suppressScrollX: !0,
        wheelPropagation: 1,
        minScrollbarLength: 40,
    });
    $(".task-right-header-status").on('click', function() {
        $(".taskboard-right-progress").slideToggle();
    });

    $(".message-mobile .media").on('click', function() {
        var vw = $(window)[0].innerWidth;
        if (vw < 992) {
            $(".taskboard-right-progress").slideUp();
            $(".msg-block").addClass('dis-chat');
        }
    });
});
function date_now() {
  var d = new Date();
  var n = d.getTime();
  return n;
}
function msg_fsc() {
  $(".msg-user-chat.scroll-div").scrollTop($(".msg-user-chat.scroll-div")[0].scrollHeight);
}
function scroll_bootom() {
  $(".msg-user-chat.scroll-div").animate({scrollTop:$(".msg-user-chat.scroll-div")[0].scrollHeight}, 500);
}
function clear_scroll() {
  $(".msg-user-chat.scroll-div").scrollTop(0);
  $(".msg-user-chat.scroll-div").animate({scrollTop:$(".msg-user-chat.scroll-div")[0].scrollHeight}, 500);
}
function get_recent_messenger_user() {
  var url = "index.php?action=get_recent_messenger_user";
  var success = function(result) {
      var json_data = $.parseJSON(result);
      show_recent_messenger_user(json_data);
  };
  $.get(url, success);
}
function show_recent_messenger_user(json_data) {
  $.each(json_data, function(index, value) {
    if (value.permission==2) {
      var badge = ' <a href="#!" class="badge badge-danger">GV</a>';
    } else {
      var badge = ' <a href="#!" class="badge badge-info">HS</a>';
    }
    $('.main-friend-list').append('' +
      '<div onclick="get_user_messenger('+ String.fromCharCode(39) + value.username + String.fromCharCode(39) +');" class="media userlist-box" data-status="online" data-username="'+ value.username +'">' +
      '<a class="media-left" href="#!">' +
      '<img class="media-object img-radius" src="upload/avatar/'+ value.avatar +'" alt="'+ value.username +' avatar">' +
      '<div style="display:none" class="live-status"></div>'+
      '</a>'+
      '<div class="media-body">'+
      '<h6 class="chat-header">'+ value.name + badge +
      '<small class="d-block text-c-green">online</small></h6>'+
      '</div>'+
      '</div>'
    );
  });
  get_user_messenger(json_data[0].username);
}
function get_user_messenger(username) {
  $('.userlist-box').removeClass("active");
  $('[data-username='+username+']').addClass("active");
  $('.msg-block .main-friend-chat').empty();
  var data = {
    username: username
  }
  var url = "index.php?action=get_user_messenger";
  var success = function(result) {
      var json_data = $.parseJSON(result);
      $("[data-username='"+username+"'] a div").css("display","none");
      show_user_messenger(json_data,username);
  };
  $.post(url, data, success);
}
function show_user_messenger(json_data,username) {
  $.each(json_data, function(index, value) {
    if (value.username_get==username) {
      $('.msg-block .main-friend-chat').append('' +
          '<div class="media chat-messages">' +
          '<div class="media-body chat-menu-reply">' +
          '<div class="">' +
          '<p class="chat-cont">' + value.content + '</p>' +
          '</div>' +
          '<p class="chat-time">'+ value.time +'</p>' +
          '</div>' +
          '</div>' +
          '');
    } else {
      var avatar = $('[data-username='+username+'] img').attr('src');
      $('.msg-block .main-friend-chat').append('' +
          '<div class="media chat-messages">' +
          '<a class="media-left photo-table" href="#!"><img class="media-object img-radius img-radius m-t-5" src="'+avatar+'" alt="'+username+' avatar"></a>' +
          '<div class="media-body chat-menu-content">' +
          '<div class="">' +
          '<p class="chat-cont">' + value.content + '</p>' +
          '</div>' +
          '<p class="chat-time">'+ value.time +'</p>' +
          '</div>' +
          '</div>' +
          '');
    }
  });
  clear_scroll();
}
function send_messenger(username,content,id) {
  var data = {
    username: username,
    content: content
  }
  var url = "index.php?action=send_messenger";
  var success = function(result) {
      var json_data = $.parseJSON(result);
      if (json_data.status==1) {
        $('#'+id).empty();
        $('#'+id).append('<i class="fas fa-check-circle"></i>');
      }
  };
  $.post(url, data, success);
}
function get_count_messenger_seen() {
  var url = "index.php?action=get_count_messenger_seen";
  var success = function(result) {
      var json_data = $.parseJSON(result);
      $.each(json_data, function(index, value) {
        var username_send = value.send_get.split(":")[0];
        if (value.count>0) {
          $("[data-username='"+username_send+"'] a div").text(value.count);
          $("[data-username='"+username_send+"'] a div").css("display","block");
        }
      });
  };
  $.get(url, success);
}
function get_new_messenger() {
  var data = {
    username: $('.media.userlist-box.active').attr('data-username')
  }
  var url = "index.php?action=get_new_messenger";
  var success = function(result) {
      var json_data = $.parseJSON(result);
      if (json_data.length>0) {
        $.each(json_data, function(index, value) {
            if ($('.media.userlist-box.active').attr('data-username')==value.username_send) {
              var avatar = $('[data-username='+value.username_send+'] img').attr('src');
              $('.msg-block .main-friend-chat').append('' +
                  '<div class="media chat-messages">' +
                  '<a class="media-left photo-table" href="#!"><img class="media-object img-radius img-radius m-t-5" src="'+avatar+'" alt="'+value.username_send+' avatar"></a>' +
                  '<div class="media-body chat-menu-content">' +
                  '<div class="">' +
                  '<p class="chat-cont">' + value.content + '</p>' +
                  '</div>' +
                  '<p class="chat-time">'+ value.TIME +'</p>' +
                  '</div>' +
                  '</div>' +
                  '');
            }
        });
        scroll_bootom();
      }

  };
  $.post(url, data, success);
}