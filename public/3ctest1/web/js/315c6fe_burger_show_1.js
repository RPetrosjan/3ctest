$(document).ready(function () {
    $(".fa-chevron-left, .fa-chevron-right").click(function () {
        idBurger = $("#idburger").text();
        $.ajax({
            url: Routing.generate('shwoburger', {
                'id': idBurger,
            }),
            success: function(data){
               console.log(data);
            },
            error: function(){
                alert('failure');
            }
        });
    });

});

