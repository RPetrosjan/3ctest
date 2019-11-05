$(document).ready(function () {
    $('.fa-wrench').click(function () {
        // get id of Burger
        idBurger = $(this).closest('tr').attr('dataid');

        $.ajax({
            url: Routing.generate('formburger', {
                'id': idBurger,
            }),
            success: function(data){
                $('#burgerformedit').html(data);
                $('#burgerform').addClass('hidden');
                $('#burgerformedit').removeClass('hidden');
                $('#burgerFormModal').modal('show');
            },
            error: function(){
                alert('failure');
            }
        });
    });

    $('.fa-plus-circle').click(function () {
        $('#burgerformedit').addClass('hidden');
        $('#burgerform').removeClass('hidden');
    })
});

