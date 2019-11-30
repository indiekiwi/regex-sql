$('[data-toggle="collapse"]').on('click', function (e) {
    if ($(this).parents('.accordion').find('.collapse.show')) {
        var idx = $(this).index('[data-toggle="collapse"]');
        if (idx == $('.collapse.show').index('.collapse')) {
            e.stopPropagation();
        }
    }
});

var i = 4;
$("#add_row").click(function () {
    $('#capture' + i).html(
        "<td>$" + i + "</td>" +
        "<td><input name='col" + i + "' type='text' value='col_" + i + "' class='form-control input-md'  /></td>" +
        "<td><input name='type" + i + "' type='text' value='VARCHAR(255)' class='form-control input-md'  /></td>" +
        "</td>");

    $('#tab_logic').append('<tr id="capture' + (i + 1) + '"></tr>');
    i++;
});

$("#delete_row").click(function () {
    if (i > 1) {
        $("#capture" + (i - 1)).html('');
        i--;
    }
});
