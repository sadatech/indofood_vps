$(document).ready(function() {
    var message_status = $("#status");
    var base_url = window.location.origin;
    $("td[contenteditable=true]").blur(function(){
        var id_kat = $(this).attr("id");
        var value = $(this).text();
        $.post(base_url+'/indofod/kategori_segmen.jsp' , id_kat + "=" + value, function(data){
            if(data != '')
            {
                message_status.show();
                message_status.text(data);
                setTimeout(function(){message_status.hide()},3000);
            }
        });
    });    
});