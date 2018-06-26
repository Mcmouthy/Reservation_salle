/*$( document ).ready(function(){
    $("#typeSalleSelected").change(ajaxGetDisponibilities());
    $("#dateSelected").change(ajaxGetDisponibilities());
    $("#capacitySelected").change(ajaxGetDisponibilities());
});*/


function ajaxGetDisponibilities()
{
    var typeSelected = $("#typeSalleSelected")[0].options.selectedIndex;
    var dateSelected = $("#dateSelected")[0].value;
    var capaciteSelected = $("#capacitySelected")[0].value;
    if (dateSelected !== ''){
        $("#DisponibilitiesRoom").empty();
        $.ajax({
            url:document.URL+'/disponibilities',
            type: "GET",
            contentType: "html",
            data: {
                typeSelected: typeSelected,
                dateSelected: dateSelected,
                capaciteSelected: capaciteSelected
            },
            async: true,
            success: function (data)
            {
                $("#DisponibilitiesRoom").append(data);

            }
        });
    }
}

function showHours(id)
{
    $(".btn-warning").removeClass("btn-warning").addClass("btn-primary")
    $("#"+id).removeClass("btn-primary").addClass("btn-warning");
    $("tfoot").remove();

    $.ajax({
        url:document.URL+'/hoursDispo',
        type: "GET",
        contentType: "html",
        data: {
            dateSelected: dateSelected.value,
            idSalleToCheck:id
        },
        async: true,
        success: function (data)
        {
            $("#tableDispo").append(data);

        }
    });
}

function getPossibleFollowedButton(id)
{
    //$(".btn-hoursDispo");
    var followed = true;
    var current_id = 0;
    var firstButton = $(".btn-hoursDispo"+".btn-warning");
    var lastButton = $(".btn-hoursDispo"+".btn-danger");
    var clickedButtonFirst = $("#" + id + ".btn-warning");
    var clickedButtonLast = $("#" + id + ".btn-danger");
    if(clickedButtonLast.length == 1){
        clickedButtonLast.removeClass("btn-danger");
    }else if(clickedButtonFirst.length == 1){
        clickedButtonFirst.removeClass("btn-warning");
        clickedButtonLast.removeClass("btn-danger");
        $.each($(".btn-hoursDispo"), function (index, value) {
            $("#" + value.id + ".btn-hoursDispo").show();
        });
    }else if(firstButton.length ==0) {
        $.each($(".btn-hoursDispo"), function (index, value) {
            if (followed) {
                if (parseInt(value.id) < parseInt(id)) {
                    $("#" + value.id + ".btn-hoursDispo").hide();
                } else if (parseInt(value.id) == parseInt(id)) {
                    $("#" + value.id + ".btn-hoursDispo").addClass("btn-warning");
                    current_id = parseInt(value.id);
                } else {
                    if (parseInt(value.id) != current_id + 1) {
                        followed = false;
                        $("#" + value.id + ".btn-hoursDispo").hide();
                    } else {
                        current_id = parseInt(value.id);
                    }
                }
            } else {
                $("#" + value.id + ".btn-hoursDispo").hide();
            }

        });
    }else if(firstButton.length == 1){
        $("#" + id + ".btn-hoursDispo").addClass("btn-danger");
    }
    
}