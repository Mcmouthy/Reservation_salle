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
        contentType: "json",
        data: {
            dateSelected: dateSelected.value,
            idSalleToCheck:id
        },
        async: true,
        success: function (data)
        {
            console.log(data);

        }
    });
}