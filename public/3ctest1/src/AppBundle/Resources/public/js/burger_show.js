$(document).ready(function () {
    $(".fa-chevron-right").click(function () {
        idBurger = parseInt($("#idburger").text()) + 1;
        getshowBurger(idBurger, 'next');
    });
    $(".fa-chevron-left").click(function () {
        idBurger = parseInt($("#idburger").text()) - 1;
        getshowBurger(idBurger, 'prev');
    });
});

function getshowBurger(idBurger, direction) {
    console.log(idBurger);
    $.ajax({
        url: Routing.generate('shwoburger', {
            'id': idBurger,
            'direction': direction,
        }),
        success: function(data){
            $('#idburger').text(data['burger']['id']);
            $('.burger>.name').text(data['burger']['name']);
            $('.burger>.desc').text(data['burger']['desc']);
            $('.burger>.price>span').html(data['burger']['price']+' &euro;');
            $('.burger>.supplement>span').text(data['burger']['supp_double']);
        },
        error: function(){
            alert('failur dd');
        }
    });
}

