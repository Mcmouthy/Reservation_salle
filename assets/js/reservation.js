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
    var firstButton = $(".btn-first");
    var lastButton = $(".btn-last");
    var clickedButtonFirst = $("#" + id + ".btn-first");
    var clickedButtonLast = $("#" + id + ".btn-last");
    if(clickedButtonLast.length == 1 && clickedButtonFirst.length ==0){
        $.each($(".btn-hoursDispo"), function (index, value) {
;            if($("#"+value.id+".btn-warning"+".btn-hoursDispo").length==1){
                if(parseInt(value.id) == parseInt(firstButton[0].id)){
                    firstButton.addClass('btn-last');
                }else{
                    $("#"+value.id+".btn-warning"+".btn-hoursDispo").removeClass('btn-warning');
                }
            }
            clickedButtonLast.removeClass('btn-last');
        $(".btn-first").addClass("btn-last");
        });
    }else if(clickedButtonFirst.length == 1){
        $.each($(".btn-hoursDispo"), function (index, value) {
            if($("#"+value.id+".btn-warning"+".btn-hoursDispo").length==1){
                $("#"+value.id+".btn-warning"+".btn-hoursDispo").removeClass('btn-warning');
                $("#" + value.id + ".btn-hoursDispo").show();
            }
            firstButton.removeClass("btn-first");
            lastButton.removeClass("btn-last");
            $('#validate_reservation').hide();
        });
        $("#validate_reservation").hide();
    }else if(firstButton.length ==0) {
        $.each($(".btn-hoursDispo"), function (index, value) {
            if (followed) {
                if (parseInt(value.id) < parseInt(id)) {
                    $("#" + value.id + ".btn-hoursDispo").hide();
                } else if (parseInt(value.id) == parseInt(id)) {
                    $("#" + value.id + ".btn-hoursDispo").addClass("btn-warning");
                    $("#" + value.id + ".btn-hoursDispo").addClass("btn-first");
                    $("#" + value.id + ".btn-hoursDispo").addClass("btn-last");
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
        $('#validate_reservation').show();
    }else if(firstButton.length == 1 && parseInt(firstButton[0].id) == parseInt(lastButton[0].id)){
        firstButton.removeClass("btn-last");
        $("#" + id + ".btn-hoursDispo").addClass("btn-last");
        $.each($(".btn-hoursDispo"), function (index, value) {
            if(value.id > firstButton[0].id && value.id <= $("#" + id + ".btn-hoursDispo")[0].id)
            $("#" + value.id + ".btn-hoursDispo").addClass("btn-warning");
        });
    }
}

function ajaxCreateReservation()
{
    var date=$("#dateSelected")[0].value;
    var idSalle = $("tbody .btn-warning")[0].id;
    var heureDebut = $("tfoot .btn-first")[0].innerHTML.split(' - ')[0];
    var heureFin = $("tfoot .btn-last")[0].innerHTML.split(' - ')[1];
    var duree = (1+parseInt($("tfoot .btn-last")[0].id)-parseInt($("tfoot .btn-first")[0].id))*30

    $.ajax({
        url:document.URL+'/create',
        type: "GET",
        contentType: "json",
        data: {
            dateDebut: (date+" "+heureDebut+":00"),
            dateFin: (date+" "+heureFin+":00"),
            duree: duree,
            idSalle:idSalle,
        },
        async: true,
        success: function (data)
        {
            json = JSON.parse(data);
            window.location.href = document.baseURI.split('/reserve/new')[0]+"/reserve/"+json["id"];
        }
    });

}