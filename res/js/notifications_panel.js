$(function() {
    $("form").on('submit', function(event) {
        event.preventDefault();
    });
    get_teacher_notifications();
    get_student_notifications();
    $('#submit').on('click', function() {
        send_notification();
    });
});

function get_teacher_notifications() {
    $('#preload').removeClass('hidden');
    var url = "index.php?action=get_teacher_notifications";
    var success = function(result) {
        var json_data = $.parseJSON(result);
        show_teacher_notifications(json_data);
        $('#preload').addClass('hidden');
    };
    $.get(url, success);
}

function show_teacher_notifications(data) {
    var list = $('#teacher_content');
    list.empty();
    for (var i = 0; i < data.length; i++) {
      list.append('<div id="student-notifications-' + data[i].notification_id + '" class="col-md-12 col-sm-12">'+
        '<div class="card card-border-c-green">'+
              '<div class="card-header">'+
                  '<a href="#!" class="text-secondary">#' + data[i].notification_id + '. ' + data[i].name + ' ( ' + data[i].username + ' ) </a>'+
                  '<span class="label label-success float-right"> ' + data[i].time_sent + ' </span>'+
              '</div>'+
              '<div class="card-block card-task">'+
                  '<div class="row">'+
                      '<div class="col-sm-12">'+
                          '<p class="task-due"><strong class="label label-success">' + data[i].notification_title + '</strong></p>'+
                          '<p class="task-detail">' + data[i].notification_content + '</p>'+
                      '</div>'+
                  '</div>'+
                  '<hr>'+
                  '<div class="task-list-table">'+
                      '<p class="task-due"><strong>Người Nhận : </strong><strong class="label label-warning">' + data[i].receive_name + '</strong> <span class="receive_username"> ( ' + data[i].receive_username + ' )</span></p>'+
                      '<a href="#!"><img style="with:40px" class="img-fluid img-radius mr-1" src="assets/images/user/avatar-2.jpg" alt="1" /></a>'+
                  '</div>'+
              '</div>'+
          '</div>'+
      '</div>');
    }
}





function get_student_notifications() {
    $('#preload').removeClass('hidden');
    var url = "index.php?action=get_student_notifications";
    var success = function(result) {
        var json_data = $.parseJSON(result);
        show_student_notifications(json_data);
        $('#preload').addClass('hidden');
    };
    $.get(url, success);
}

function show_student_notifications(data) {
    var list = $('#student_content');
    list.empty();
    for (var i = 0; i < data.length; i++) {
      list.append('<div id="student-notifications-' + data[i].notification_id + '" class="col-md-12 col-sm-12">'+
        '<div class="card card-border-c-green">'+
              '<div class="card-header">'+
                  '<a href="#!" class="text-secondary">#' + data[i].notification_id + '. ' + data[i].name + ' ( ' + data[i].username + ' ) </a>'+
                  '<span class="label label-success float-right"> ' + data[i].time_sent + ' </span>'+
              '</div>'+
              '<div class="card-block card-task">'+
                  '<div class="row">'+
                      '<div class="col-sm-12">'+
                          '<p class="task-due"><strong class="label label-success">' + data[i].notification_title + '</strong></p>'+
                          '<p class="task-detail">' + data[i].notification_content + '</p>'+
                      '</div>'+
                  '</div>'+
                  '<hr>'+
                  '<div class="task-list-table">'+
                      '<p class="task-due"><strong>Người Nhận : </strong><strong class="label label-warning">' + data[i].class_name + '</strong></p>'+
                      '<a href="#!"><img style="with:40px" class="img-fluid img-radius mr-1" src="assets/images/user/avatar-2.jpg" alt="1" /></a>'+
                  '</div>'+
              '</div>'+
          '</div>'+
      '</div>');
    }
}

function send_notification() {
    $('#preload').removeClass('hidden');
    var url = "index.php?action=send_notification";
    var teacher_id_value = $('#teacher_id').val();
    var class_id_value = $('#class_id').val();
    teacher_id = JSON.stringify(teacher_id_value);
    class_id = JSON.stringify(class_id_value);
    console.log(teacher_id);
    console.log(class_id);
    var notification_title = $('#notification_title').val();
    var notification_content = $('#notification_content').val();
    var data = {
        teacher_id: teacher_id,
        class_id: class_id,
        notification_title: notification_title,
        notification_content: notification_content
    };
    var success = function(result) {
        var json_data = $.parseJSON(result);
        if (json_data.status) {
          swal("Gửi thông báo thành công!", {
              icon: "success",
          });
          $('#send_notification')[0].reset();
          $('select').multiSelect('refresh');
        } else {
          swal(json_data.status_value, {
              icon: "warning",
          });
        }
        get_student_notifications();
        get_teacher_notifications();
    };
    $.post(url, data, success);
}

// [ Searchable ] start
$('.searchable').multiSelect({
    selectableHeader: "<input type='text' class='form-control' autocomplete='off' placeholder='tìm kiếm...'>",
    selectionHeader: "<input type='text' class='form-control' autocomplete='off' placeholder='tìm kiếm...'>",
    afterInit: function(ms) {
        var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
            .on('keydown', function(e) {
                if (e.which === 40) {
                    that.$selectableUl.focus();
                    return false;
                }
            });

        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
            .on('keydown', function(e) {
                if (e.which == 40) {
                    that.$selectionUl.focus();
                    return false;
                }
            });
    },
    afterSelect: function() {
        this.qs1.cache();
        this.qs2.cache();
    },
    afterDeselect: function() {
        this.qs1.cache();
        this.qs2.cache();
    }
});
$('#select-all-class').on('click', function() {
    $('#class_id').multiSelect('select_all');
    return false;
});
$('#deselect-all-class').on('click', function() {
    $('#class_id').multiSelect('deselect_all');
    return false;
});
$('#select-all-teacher').on('click', function() {
    $('#teacher_id').multiSelect('select_all');
    return false;
});
$('#deselect-all-teacher').on('click', function() {
    $('#teacher_id').multiSelect('deselect_all');
    return false;
});
