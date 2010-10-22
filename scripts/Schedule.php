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
  
    public function addToSchedule($entry)
    {
        include_once 'dbinfo.php';
        include_once 'HeatingEntry.php';
        
        $conn = mysql_connect($server, $username, $password)
                       or die("Connection error " . mysql_error());
        mysql_select_db($db);

        $query = sprintf("INSERT INTO schedule SET room='%s', value='%s', day='%s', time='%s'",
                        mysql_real_escape_string($entry->getRoom()),
                        mysql_real_escape_string($entry->getValue()),
                        mysql_real_escape_string($entry->getDay()),
                        mysql_real_escape_string($entry->getTime()));
        $result = mysql_query($query);        
        $returnValue = (mysql_affected_rows() == 1) ? true : false;
        mysql_close($conn);
        
        return ($returnValue);
    }

    public function deleteFromSchedule($entryid)
    {
        include_once 'dbinfo.php';
        $conn = mysql_connect($server, $username, $password)
                       or die("Connection error " . mysql_error());
        mysql_select_db($db);

        $query = sprintf("DELETE FROM schedule WHERE id='%s'", 
                        mysql_real_escape_string($entryid));
        
        $result = mysql_query($query);        
        $returnValue = (mysql_affected_rows() == 1) ? true : false;
        mysql_close($conn);
        
        return ($returnValue);
    }

    public function printHTMLSchedule()
    {
        include_once 'dbinfo.php';
        $conn = mysql_connect($server, $username, $password)
                       or die("Connection error " . mysql_error());
        mysql_select_db($db);

        $query = "SELECT schedule.id, room.roomname, schedule.value, day.dayname, schedule.time " .
                 "FROM schedule " .
                 "INNER JOIN room ON (room = roomid) " .
                 "INNER JOIN day ON (day = dayid)";

        $result = mysql_query($query);

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
            print "        <td>{$row['value']}</td>";
            print "        <td>{$row['dayname']} @ {$row['time']}</td>";
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
