function loadSchedule(){
    $("#schedule").load("scripts/getscheduledata.php", function(response, status, xhr){
        if(status == "error"){
            $(this).html("<h1>Could not fetch data</h1>");
        }
        else
        {

            //Zedbra stripe the table
            $(".scheduletable tbody tr:odd").addClass("alternaterow");
            $(".scheduletable").tablesorter();
            $(".scheduletable").bind("sortStart",function() {
                $(".scheduletable tbody tr:odd").removeClass("alternaterow");
                }) .bind("sortEnd",function() {
                   $(".scheduletable tbody tr:odd").addClass("alternaterow");
                });

            //setup click events for deleting
            $(".delete").click(function(){
                $.get($(this).attr("href"));
                loadSchedule();
                return false;

            });
        }

    });
}

function addEntry(){
  $.get("scripts/addtoschedule.php", {room: $("#room").val(),
                                      value: $("#value").val(),
                                      day: $("#day").val(),
                                      hour: $("#hour").val(),
                                      minute: $("#minute").val(),
                                      type: $("#type").val()},
                              function(){
                                  loadSchedule();
                              });
}