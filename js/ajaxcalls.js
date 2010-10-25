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
    
  $("#conflictdialog").load("scripts/addtoschedule.php #result", {room: $("#room").val(),
                                      value: $("#value").val(),
                                      day: $("#day").val(),
                                      starthour: $("#starthour").val(),
                                      startminute: $("#startminute").val(),
                                      endhour: $("#endhour").val(),
                                      endminute: $("#endminute").val(),
                                      type: $("#type").val()}, function(){

      $("#conflictform").submit(function(){
            return false;
          });

          $("#conflictformsubmit").click(function(){
            overwriteEntry();
          });

          loadSchedule();
      alert($("#conflictdialog div p").text());
      if($("#conflictdialog div p").text() != "Entry added to schedule")
      {
        $("#conflictdialog").dialog('open');
      }
  });

}

function overwriteEntry()
{
    $("#conflictdialog").dialog('close');
    $("#confirmationdialog").load("scripts/resolveconflicts.php", {room: $("#conflictroom").val(),
                                                                   value: $("#conflictvalue").val(),
                                                                   day: $("#conflictday").val(),
                                                                   starttime: $("#conflictstarttime").val(),
                                                                   endtime: $("#conflictendtime").val(),
                                                                   conflictchoice: $("#conflictform input[name='conflictchoice']:checked").val()},
      function(){
        $("#confirmationdialog").dialog({beforeClose: function(){
                loadSchedule()
                }});
        $("#confirmationdialog").dialog('open');      
   });
}