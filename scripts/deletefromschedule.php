<?php


if(isset($_GET['id']))
{
    require_once 'HeatingEntry.php';
    require_once 'Schedule.php';

    $schedule = new Schedule();
    $result = $schedule->deleteFromSchedule($_GET['id']);

    if(!($result))
    {
      print "Not Deleted";
    }
    else
    {
      print "Deleted";
    }
}
else
{
    print "shit not set";
}


?>
