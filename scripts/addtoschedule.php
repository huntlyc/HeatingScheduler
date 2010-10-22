<?php

if (isset ($_GET['type']) && (strcmp($_GET['type'], "heating") == 0))
{
    if(isset($_GET['room']) &&
       isset ($_GET['value']) &&
       isset ($_GET['day']) &&
       isset ($_GET['hour']) &&
       isset($_GET['minute']))
    {
        require_once 'HeatingEntry.php';
        require_once 'Schedule.php';
        //vars
        $room = $_GET['room'];
        $value = $_GET['value'];
        $day = $_GET['day'];
        $time = "{$_GET['hour']}:{$_GET['minute']}";
        $entry = new HeatingEntry($room, $value, $day, $time);
        $schedule = new Schedule();
        $result = $schedule->addToSchedule($entry);

        if(!($result))
        {
          print "Not Added";
        }
        else
        {
          print "Added";
        }
    }
    else
    {
        print "shit not set";
    }
}
else
{
    print "Type not supported";
}

?>
