$('#chat_text').on('keypress',function(e){
    if(e.keyCode == 13) {
        noidung=$(this).val();
        if(noidung.length>1 && noidung.length<=250){
            $.ajax({
                url: "/process.php",
                type: "post",
                data: {
                    action: "chat_room",
                    noidung: noidung
                },
                success: function(kq) {
                    var info = JSON.parse(kq);
                    if(info.ok==1){
                    	$('#chat_text').val('');
                        $('.list_chat').append(info.list);///
                      	$('.list_chat').scrollTop($('.list_chat').prop('scrollHeight')); // Cuộn xuống cuối sau khi thêm tin nhắn
                    }else{
                      //  $('#chat_text').val('');////////
                        $('.load_overlay').show();
                        $('.load_process').fadeIn();
                        $('.load_note').html(info.thongbao);
                        setTimeout(function() {
                            $('.load_process').hide();
                            $('.load_note').html('Hệ thống đang xử lý');
                            $('.load_overlay').hide();
                        }, 2000);
                    }
                }
            });
            
        }
    }
});
//////////////////////

$('#button_comment').on('click', function() {

    truyen = $(this).attr('truyen');

    chap = $(this).attr('chap');

    noidung=$('.comment_text_value').val();

    var form_data = new FormData();

    form_data.append('action', 'comment');

    form_data.append('truyen', truyen);

    form_data.append('chap', chap);

    form_data.append('noidung', noidung);

    $('.load_overlay').show();

    $('.load_process').fadeIn();

    $.ajax({

        url: '/process.php',

        type: 'post',

        cache: false,

        contentType: false,

        processData: false,

        data: form_data,

        success: function(kq) {

            var info = JSON.parse(kq);

            setTimeout(function() {

                $('.load_note').html(info.thongbao);

            }, 500);

            setTimeout(function() {

                $('.load_process').hide();

                $('.load_note').html('Hệ thống đang xử lý');

                $('.load_overlay').hide();

                if(info.ok==1){

                    $('.comment_text_value').val('');

                    var dulieu={

                        "user_id":info.user_id,

                        "noi_dung":info.noi_dung

                    }

                    
                }

                $('.list_comment').prepend(info.list);

            }, 2000);

        }



    });

});

//////////////////////

$('.list_comment').on('click','#button_sub_comment', function() {

    id = $(this).attr('id_comment');

    var div=$(this);

    noidung=$(this).parent().find('.sub_comment_text').val();

    var form_data = new FormData();

    form_data.append('action', 'sub_comment');

    form_data.append('id', id);

    form_data.append('noidung', noidung);

    $('.load_overlay').show();

    $('.load_process').fadeIn();

    $.ajax({

        url: '/process.php',

        type: 'post',

        cache: false,

        contentType: false,

        processData: false,

        data: form_data,

        success: function(kq) {

            var info = JSON.parse(kq);

            setTimeout(function() {

                $('.load_note').html(info.thongbao);

            }, 500);

            setTimeout(function() {

                $('.load_process').hide();

                $('.load_note').html('Hệ thống đang xử lý');

                $('.load_overlay').hide();

                if(info.ok==1){

                    div.parent().find('.sub_comment_text').val('');

                    var dulieu={

                        "user_id":info.user_id,

                        "noi_dung":info.noi_dung

                    }

                

                }

                div.parent().parent().parent().find('.list_sub_comment').append(info.list);

            }, 2000);

        }

    });

});

//////////////////////

$('body').on('click','#button_report', function() {

    loi = $('#box_pop_report select[name=loi]').val();

    truyen = $('#button_report').attr('truyen');

    chap = $('#box_pop_report select[name=chap]').val();

    var form_data = new FormData();

    form_data.append('action', 'report');

    form_data.append('loi', loi);

    form_data.append('truyen', truyen);

    form_data.append('chap', chap);

    $('.load_overlay').show();

    $('.load_process').fadeIn();

    $.ajax({

        url: '/process.php',

        type: 'post',

        cache: false,

        contentType: false,

        processData: false,

        data: form_data,

        success: function(kq) {

            var info = JSON.parse(kq);

            setTimeout(function() {

                $('.load_note').html(info.thongbao);

            }, 1000);

            setTimeout(function() {

                $('.load_process').hide();

                $('.load_note').html('Hệ thống đang xử lý');

                $('.load_overlay').hide();

                if (info.ok == 1) {

                    $('.box_pop').fadeOut();

                    $('#box_pop_report input[name=noidung]').val('');

                    $('#box_pop_report select[name=loi]').val('');

                    var dulieu={

                        "user_id":info.user_id,

                        "noi_dung":info.noi_dung

                    }

           

                }

            }, 3000);

        }



    });

});