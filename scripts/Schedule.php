<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Schedule
 *
 * @author hunt0r
 */

    


class Schedule
{
    public function  __construct()
    {
        include_once 'dbinfo.php';
        include_once 'Day.php';
    }

    public function addToSchedule($entry)
    {
        $returnValue = "error";

        if($this->checkForConflicts($entry))
        {
          $returnValue = "Entry Conflict";
        }
        else
        {
            $db = new DBInfo();
            $conn = mysql_connect(DBInfo::$server, DBInfo::$username, DBInfo::$password)
                           or die("Connection error " . mysql_error());
            mysql_select_db(DBInfo::$db);

            if(($entry->getDay() == DAY::$WEEKDAY) || ($entry->getDay() == Day::$EVERYDAY))
            {
              if($entry->getDay() == DAY::$WEEKDAY)
              {
                //only add durin week: Mon-Fri
                $numDays = 5;
              }
              else
              {
                //Add for every day of the week: Mon-Sun
                $numDays = 7;
              }

              $added = true;

              for($i=1; $i<=$numDays; $i++)
              {
                $query = sprintf("INSERT INTO schedule SET room='%s', value='%s', day='%s', starttime='%s', endtime='%s'",
                              mysql_real_escape_string($entry->getRoom()),
                              mysql_real_escape_string($entry->getValue()),
                              mysql_real_escape_string($i),
                              mysql_real_escape_string($entry->getStartTime()),
                              mysql_real_escape_string($entry->getEndTime()));
                $result = mysql_query($query);

                if(!$result)
                {
                  $added = false;
                }

                $returnValue = ($added) ? "Added" : "Not Added";
              }
            }
            else
            {
              $query = sprintf("INSERT INTO schedule SET room='%s', value='%s', day='%s', starttime='%s', endtime='%s'",
                              mysql_real_escape_string($entry->getRoom()),
                              mysql_real_escape_string($entry->getValue()),
                              mysql_real_escape_string($entry->getDay()),
                              mysql_real_escape_string($entry->getStartTime()),
                              mysql_real_escape_string($entry->getEndTime()));
              $result = mysql_query($query);
              $returnValue = (mysql_affected_rows() == 1) ? "Added" : "Not Added";
            }

            mysql_close($conn);
        }

        
        return ($returnValue);
    }

    private function checkForConflicts($entry)
    {
        $db = new DBInfo();
        $conn = mysql_connect(DBInfo::$server, DBInfo::$username, DBInfo::$password)
                       or die("Connection error " . mysql_error());
        mysql_select_db(DBInfo::$db);
        
        if($entry->getDay() == Day::$EVERYDAY || $entry->getDay() == Day::$WEEKDAY)
        {
          if($entry->getDay() == Day::$EVERYDAY)
          {
            $query = sprintf("SELECT COUNT(*) ".
                         "FROM schedule " .
                         "WHERE room='%s' AND starttime BETWEEN TIME('%s') AND TIME('%s')",
                         mysql_real_escape_string($entry->getRoom()),
                         mysql_real_escape_string($entry->getDay()),
                         mysql_real_escape_string($entry->getStartTime()),
                         mysql_real_escape_string($entry->getEndTime()));
          }
          else if($entry->getDay() == Day::$WEEKDAY)
          {
            $query = sprintf("SELECT COUNT(*) ".
                             "FROM schedule " .
                             "WHERE room='%s' AND day BETWEEN 1 AND 5 AND starttime BETWEEN TIME('%s') AND TIME('%s')",
                             mysql_real_escape_string($entry->getRoom()),
                             mysql_real_escape_string($entry->getDay()),
                             mysql_real_escape_string($entry->getStartTime()),
                             mysql_real_escape_string($entry->getEndTime()));            
          }
        }
        else
        {
          $query = sprintf("SELECT COUNT(*) ".
                         "FROM schedule " .
                         "WHERE room='%s' AND day='%s' AND starttime BETWEEN TIME('%s') AND TIME('%s')",
                         mysql_real_escape_string($entry->getRoom()),
                         mysql_real_escape_string($entry->getDay()),
                         mysql_real_escape_string($entry->getStartTime()),
                         mysql_real_escape_string($entry->getEndTime()));
        }

        
        $result = mysql_query($query);
        $row = mysql_fetch_row($result);
        $returnValue = ($row[0] > 0) ? true : false;
        mysql_close($conn);

        return ($returnValue);
    }

    public function getConflictingEntries($entry)
    {

        $db = new DBInfo();
        $conn = mysql_connect(DBInfo::$server, DBInfo::$username, DBInfo::$password)
                       or die("Connection error " . mysql_error());
        mysql_select_db(DBInfo::$db);

        if($entry->getDay() == Day::$EVERYDAY || $entry->getDay() == Day::$WEEKDAY)
        {
          if($entry->getDay() == Day::$EVERYDAY)
          {
            $query = sprintf("SELECT * ".
                         "FROM schedule " .
                         "WHERE room='%s' AND starttime BETWEEN TIME('%s') AND TIME('%s')",
                         mysql_real_escape_string($entry->getRoom()),
                         mysql_real_escape_string($entry->getDay()),
                         mysql_real_escape_string($entry->getStartTime()),
                         mysql_real_escape_string($entry->getEndTime()));
          }
          else if($entry->getDay() == Day::$WEEKDAY)
          {
            $query = sprintf("SELECT * ".
                             "FROM schedule " .
                             "WHERE room='%s' AND day BETWEEN 1 AND 5 AND starttime BETWEEN TIME('%s') AND TIME('%s')",
                             mysql_real_escape_string($entry->getRoom()),
                             mysql_real_escape_string($entry->getDay()),
                             mysql_real_escape_string($entry->getStartTime()),
                             mysql_real_escape_string($entry->getEndTime()));
          }
        }
        else
        {
          $query = sprintf("SELECT * ".
                         "FROM schedule " .
                         "WHERE room='%s' AND day='%s' AND starttime BETWEEN TIME('%s') AND TIME('%s')",
                         mysql_real_escape_string($entry->getRoom()),
                         mysql_real_escape_string($entry->getDay()),
                         mysql_real_escape_string($entry->getStartTime()),
                         mysql_real_escape_string($entry->getEndTime()));
        }
        $result = mysql_query($query);
        $conflictList = Array();
        
        while($row = mysql_fetch_assoc($result))
        {
          $conflictList[] = new HeatingEntry($row['room'], $row['value'], $row['day'], $row['starttime'], $row['endtime']);
        }
        
        mysql_close($conn);

        return ($conflictList);
    }

    public function overwriteSchedule($entry)
    {
        $returnValue = false;
        $db = new DBInfo();
        $conn = mysql_connect(DBInfo::$server, DBInfo::$username, DBInfo::$password)
                       or die("Connection error " . mysql_error());
        mysql_select_db(DBInfo::$db);
        if(($entry->getDay() == DAY::$WEEKDAY) || ($entry->getDay() == Day::$EVERYDAY))
        {
          if($entry->getDay() == DAY::$WEEKDAY)
          {
            //only add durin week: Mon-Fri
            $numDays = 5;
          }
          else
          {
            //Add for every day of the week: Mon-Sun
            $numDays = 7;
          }


          for($i=1; $i<=$numDays; $i++)
          {
            $query = sprintf("DELETE FROM schedule " .
                             "WHERE room='%s' AND day='%s' AND starttime BETWEEN TIME('%s') AND TIME('%s') AND endtime BETWEEN TIME('%s') AND TIME('%s')",
                             mysql_real_escape_string($entry->getRoom()),
                             mysql_real_escape_string($i),
                             mysql_real_escape_string($entry->getStartTime()),
                             mysql_real_escape_string($entry->getEndTime()),
                             mysql_real_escape_string($entry->getStartTime()),
                             mysql_real_escape_string($entry->getEndTime()));
            $result = mysql_query($query);

            if(mysql_affected_rows($conn) > 0)
            {
              $query = sprintf("INSERT INTO schedule " .
                               "SET room='%s', value='%s', day='%s', starttime='%s', endtime='%s'",
                               mysql_real_escape_string($entry->getRoom()),
                               mysql_real_escape_string($entry->getValue()),
                               mysql_real_escape_string($i),
                               mysql_real_escape_string($entry->getStartTime()),
                               mysql_real_escape_string($entry->getEndTime()));
              $result = mysql_query($query);

              if(mysql_affected_rows($conn) > 0)
              {
                $returnValue = true;
              }
            }
          }
        }
        else
        {
            $query = sprintf("DELETE FROM schedule " .
                             "WHERE room='%s' AND day='%s' AND starttime BETWEEN TIME('%s') AND TIME('%s') AND endtime BETWEEN TIME('%s') AND TIME('%s')",
                             mysql_real_escape_string($entry->getRoom()),
                             mysql_real_escape_string($entry->getDay()),
                             mysql_real_escape_string($entry->getStartTime()),
                             mysql_real_escape_string($entry->getEndTime()),
                             mysql_real_escape_string($entry->getStartTime()),
                             mysql_real_escape_string($entry->getEndTime()));
            $result = mysql_query($query);

            if(mysql_affected_rows($conn) > 0)
            {
              $query = sprintf("INSERT INTO schedule " .
                               "SET room='%s', value='%s', day='%s', starttime='%s', endtime='%s'",
                               mysql_real_escape_string($entry->getRoom()),
                               mysql_real_escape_string($entry->getValue()),
                               mysql_real_escape_string($entry->getDay()),
                               mysql_real_escape_string($entry->getStartTime()),
                               mysql_real_escape_string($entry->getEndTime()));
              $result = mysql_query($query);
              if(mysql_affected_rows($conn) > 0)
              {
                $returnValue = true;
              }



            }
        }
        mysql_close($conn);
        return($returnValue);

    }

    public function deleteFromSchedule($entryid)
    {
        $db = new DBInfo();
        $conn = mysql_connect(DBInfo::$server, DBInfo::$username, DBInfo::$password)
                       or die("Connection error " . mysql_error());
        mysql_select_db(DBInfo::$db);

        $query = sprintf("DELETE FROM schedule WHERE id='%s'", 
                        mysql_real_escape_string($entryid));
        
        $result = mysql_query($query);        
        $returnValue = (mysql_affected_rows() == 1) ? true : false;
        mysql_close($conn);
        
        return ($returnValue);
    }

    public function printHTMLSchedule()
    {
      
        $db = new DBInfo();
        $conn = mysql_connect(DBInfo::$server, DBInfo::$username, DBInfo::$password)
                       or die("Connection error " . mysql_error());
        mysql_select_db(DBInfo::$db);

        $query = "SELECT schedule.id, room.roomname, schedule.value, day.dayname, schedule.starttime, schedule.endtime " .
                 "FROM schedule " .
                 "INNER JOIN room ON (room = roomid) " .
                 "INNER JOIN day ON (day = dayid)";

        $result = mysql_query($query) or die("Query error: " . mysql_error());

        print "  <table id='scheduletable'>";
        print "    <thead>";
        print "      <tr>";
        print "        <th>Room</th><th>Value</th><th>Date</th><th>Delete</th>";
        print "      </tr>";
        print "    </thead>";
        print "    <tbody>";

        while ($row = mysql_fetch_assoc($result))
        {
            print "      <tr>\n";
            print "        <td>{$row['roomname']}</td>";
            $value = $row['value'];
            if ($value > 5)
            {
              print "        <td>{$row['value']}</td>";
            }
            else
            {
               print "        <td>OFF</td>";
            }
            print "        <td>{$row['dayname']} @ {$row['starttime']} -> {$row['endtime']}</td>";
            print "        <td>";
            print "          <a class='delete' href='scripts/deletefromschedule.php?id={$row['id']}'>";
            print "            <img src='images/delete.png' alt='delete'/>";
            print "          </a>";
            print "        </td>";
            print "      <tr>";
        }

        print "    </tbody>";
        print "  </table>";
        mysql_close($conn);
    }
}
?>
