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
        $("#disponibleRoom").empty();
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
                $("#disponibleRoom").append(data);

            }
        });
    }


}