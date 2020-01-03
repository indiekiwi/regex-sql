/**
 * Starting capture groups rows +1
 *
 * @type int
 */
var idxCaptureGroup = 101;

/**
 * Automatically update capture group rows until the user manually clicks the add/delete button
 *
 * @type boolean
 */
var autoCaptureGroup = true;

/**
 * Saved capture group col data while it may be deleted
 */
var colData = [];

/**
 * Saved type data while it may be deleted
 */
var typeData = [];

/**
 * @todo Load correct number of capture groups onload when using back button
 */
$(document).ready(function () {
    automateCaptureGroup();
});

/**
 * Add Capture Group Button
 */
$("#add_row").click(function () {
    addCaptureGroup();
    autoCaptureGroup = false;
});

/**
 * Remove Capture Group Button
 */
$("#delete_row").click(function () {
    deleteCaptureGroup();
    autoCaptureGroup = false;
});

/**
 * Add the Capture Group Row
 */
function addCaptureGroup() {
    var addCol = "col_" + idxCaptureGroup;
    if (typeof colData[idxCaptureGroup] !== 'undefined') {
        addCol = colData[idxCaptureGroup];
    }
    var addType = "TEXT";
    if (typeof typeData[idxCaptureGroup] !== 'undefined') {
        addType = typeData[idxCaptureGroup];
    }

    $('#capture' + idxCaptureGroup).html(
        "<td>$" + idxCaptureGroup + "</td>" +
        "<td><input name='col" + idxCaptureGroup + "' type='text' value='" + addCol + "' class='form-control input-md'  /></td>" +
        "<td><input name='type" + idxCaptureGroup + "' type='text' value='" + addType + "' class='form-control input-md'  /></td>" +
        "</td>");

    $('#tab_logic').append('<tr id="capture' + (idxCaptureGroup + 1) + '"></tr>');
    idxCaptureGroup++;
}

/**
 * Remove the Capture Group Row
 */
function deleteCaptureGroup() {
    if (idxCaptureGroup > 1) {
        colData[idxCaptureGroup - 1] = $("input[name=col" + (idxCaptureGroup - 1) + "]").val();
        typeData[idxCaptureGroup - 1] = $("input[name=type" + (idxCaptureGroup - 1) + "]").val();

        $("#capture" + (idxCaptureGroup - 1)).html('');
        idxCaptureGroup--;
    }
}

/**
 * Sync the capture groups automatically
 */
function automateCaptureGroup() {
    if (autoCaptureGroup) {
        var currentValue = $('#regex-pattern').val();
        currentValue = currentValue.replace(/\\\(/g, 'VOID');
        currentValue = currentValue.replace(/\(\?:/g, 'VOID');
        var autoGroupCount = currentValue.split('(').length - 1;
        console.log(currentValue + ' ' + autoGroupCount);

        if (idxCaptureGroup - 1 < autoGroupCount) {
            while (idxCaptureGroup - 1 < autoGroupCount) {
                if (idxCaptureGroup > 100) {
                    break;
                }
                addCaptureGroup();
            }
        } else if (idxCaptureGroup - 1 > autoGroupCount) {
            while (idxCaptureGroup - 1 > autoGroupCount) {
                if (idxCaptureGroup < 1) {
                    break;
                }
                deleteCaptureGroup();
            }
        }
    }
}

/**
 * Automated detection of capture groups to generate rows
 *
 * @supported `(a)` only; +1 capture group
 *             no support for `(a)?` for +0/1 capture group
 */
$('#regex-pattern').on('input', function () {
    automateCaptureGroup();
});

/**
 * Accordion
 */
$('[data-toggle="collapse"]').on('click', function (e) {
    if ($(this).parents('.accordion').find('.collapse.show')) {
        var idx = $(this).index('[data-toggle="collapse"]');
        if (idx == $('.collapse.show').index('.collapse')) {
            e.stopPropagation();
        }
    }
});

/**
 * Toggle
 */
function toggle(e, className) {
    if (e.checked) {
        console.log(className + ' hide');
        $('.' + className).css('display', 'block');
    } else {
        console.log(className + ' show');
        $('.' + className).css('display', 'none');
    }
}