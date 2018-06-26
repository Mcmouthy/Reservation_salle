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
    console.log($("#validate_reservation"));
    if(clickedButtonLast.length == 1){
        clickedButtonLast.removeClass("btn-danger");
        $("#validate_reservation").hide();
    }else if(clickedButtonFirst.length == 1){
        clickedButtonFirst.removeClass("btn-warning");
        $(".btn-danger").removeClass("btn-danger");
        $("#validate_reservation").hide();
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
    }else if(firstButton.length == 1 && lastButton.length ==0){
        $("#" + id + ".btn-hoursDispo").addClass("btn-danger");
        $("#validate_reservation").show();
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