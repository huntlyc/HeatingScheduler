<?php

if (isset($_POST['conflictchoice']))
{
  if (strcmp($_POST['conflictchoice'], "overwrite") == 0 )
  {
    if(isset($_POST['room']) &&
       isset ($_POST['value']) &&
       isset ($_POST['day']) &&
       isset ($_POST['starttime']) &&
       isset($_POST['endtime']))
    {
        require_once 'HeatingEntry.php';
        require_once 'Schedule.php';
        //vars
        $room = $_POST['room'];
        $value = $_POST['value'];
        $day = $_POST['day'];
        $starttime = urldecode($_POST['starttime']);
        $endtime = urldecode($_POST['endtime']);
        $entry = new HeatingEntry($room, $value, $day, $starttime, $endtime);
        $schedule = new Schedule();
        $result = $schedule->overwriteSchedule($entry);

        if($result)
        {
          print "Overwritten";
        }
    }
    else
    {
      print "Missing Values";
    }
  }
  else
  {
    print "Could not process request";
  }
}

?>
