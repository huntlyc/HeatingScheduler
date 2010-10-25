<?php


if (isset ($_POST['type']) && (strcmp($_POST['type'], "heating") == 0))
{
    if(isset($_POST['room']) &&
       isset ($_POST['value']) &&
       isset ($_POST['day']) &&
       isset ($_POST['starthour']) &&
       isset($_POST['startminute']) &&
       isset($_POST['endhour']) &&
       isset($_POST['endminute']))
    {
        require_once 'HeatingEntry.php';
        require_once 'Schedule.php';
        //vars
        $room = $_POST['room'];
        $value = $_POST['value'];
        $day = $_POST['day'];
        $starttime = "{$_POST['starthour']}:{$_POST['startminute']}";
        $endtime = "{$_POST['endhour']}:{$_POST['endminute']}";
        $entry = new HeatingEntry($room, $value, $day, $starttime, $endtime);
        $schedule = new Schedule();
        $result = $schedule->addToSchedule($entry);

        
        print "<html><head><title>Add To Schedule</title></head><body><div id='result'>";
        if(strcmp($result, "error") == 0)
        {
          print "<p>Unknown Error</p>";
        }
        else if(strcmp($result, "Entry Conflict") == 0)
        {
          $conflicts = $schedule->getConflictingEntries($entry);
          print "<p>New entry conflicts with the following:</p>";
          print "<table><thead><tr><th>Room</th><th>Value</th><th>Day</th><th>Start Time</th><th>End Time</th></tr></thead><tbody>";

          $conflict;
          foreach ($conflicts as $conflict)
          {
            $output =  "<tr><td>{$conflict->getRoomName()}</td><td>{$conflict->getValue()}</td>".
                       "<td>{$conflict->getDayName()}</td><td>{$conflict->getStartTime()}</td>" .
                       "<td>{$conflict->getEndTime()}</td></tr>";
            print $output;
            $conflict = NULL;
          }

          print "</tbody></table>";
          print "<form id='conflictform' action='scripts/resolveconflicts.php' method='get'>";
          print "<div class='formdiv'>";
          print "<p><input type='hidden' id='conflictroom' value='{$entry->getRoom()}'/>";
          print "<p><input type='hidden' id='conflictvalue' value='{$entry->getValue()}'/>";
          print "<p><input type='hidden' id='conflictday' value='{$entry->getDay()}'/>";
          print "<p><input type='hidden' id='conflictstarttime' value='". urlencode($entry->getStartTime()). "'/>";
          print "<p><input type='hidden' id='conflictendtime' value='" . urlencode($entry->getEndTime()) . "'/></p>";
          print "<p><input type='radio' value='overwrite' name='conflictchoice'/>Overwrite</p>";
          print "<p><input type='submit' id='conflictformsubmit' value='submit'/></p>";
          print "</div>";
          print "</form>";

        }
        else if(strcmp($result, "Added") == 0)
        {
          print "<p>Entry added to schedule</p>";
        }
        else if(strcmp($result, "Not Added") == 0)
        {
          print "<p>Error: entry not added to schedule</p>";
        }

        print "</div></body></html>";
        


        
    }
    else
    {
        print "Not enough information to add";
    }
}
else
{
    print "Type not supported";
}

?>
