 $(function() {

    // dynamicShow();

    $('#akses').change(function(){
        var url = window.location.href;

        var segments = url.split( '/' );

        console.log(segments[2]);
        // dynamicShow();

    });



    $('#toko').select2({

    });

    function dynamicShow() {

        if($('#akses').val() == '1') {

            $('#row_status').show();

            $('#row_cabang').hide();

            $("#row_kota").hide();

            // $('#status').change(function(){

            //     if($('#status').val() != "") {

            $('#row_toko').show();

            //     } else {

            //         $('#row_toko').hide();

            //     }

            // });

        }

        else if ($('#akses').val() == '0') {

            $('#row_status').hide();

            $('#row_toko').hide();

            $("#row_kota").show();

            $('#row_cabang').show();

            if ($("#akses").val() == "") {

                $('#row_cabang').hide();

            }

            else{

                $("#cabang").change(function() {

                    if ($(this).val() == "") {

                        $("#row_kota").show();

                    }

                    else{

                        var base_url = window.location.origin;

                        $("#row_kota").show();

                        $.ajax({

                            type: "POST",

                            url: base_url+"/api/filterkota?key=ganteng",

                            data: {id_cabang:$(this).val()},

                            dataType: 'json',

                            success:function(datas) {

                                console.log(datas);

                                var data = "";

                                data += "<option value='pilih'>-Pilih Kota-</option>";

                                for (var i = datas.length - 1; i >= 0; i--) {

                                    data += "<option value='"+datas[i]['id_kota']+"''>"+datas[i]['nama_kota']+"</option>";

                                }

                                $("#kota").html(data);

                            }

                        })

                    }

                })

            }

        }

        else {

            $('#row_status').hide();

            $('#row_cabang').hide();

            $('#row_toko').hide();

            $("#row_kota").hide();

        }

    }

    $("#select_cabang").on('change', function(event) {
        event.preventDefault();
        var id = $(this).val();
        var base_url = window.location.origin;

        $.ajax({
            url: base_url+'/users/changeCabang.jsp?key=ganteng',
            type: 'POST',
            dataType: 'json',
            data: {id_cab: id},
        })
        .done(function(msg) {
            var data = "";
                data += "";
            for (var i = msg.length - 1; i >= 0; i--) {
                    data += "<option value='"+msg[i]['id_kota']+"'>"+msg[i]['nama_kota']+"</option>";
            }
            $("#select_kota").html(data);
        })
        .fail(function() {
            console.log("error");
        })
    });
});