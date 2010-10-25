<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HeatingEntry
 *
 * @author hunt0r
 */

include_once 'ScheduleEntry.php';
class HeatingEntry extends ScheduleEntry
{
    private $_roomid;
    private $_value;
    private $_day;
    private $_starttime;
    private $_endtime;

    public function  __construct($roomid, $value, $day, $starttime, $endtime)
    {
      $this->_roomid = $roomid;
      $this->_value = $value;
      $this->_day = $day;
      $this->_starttime = $starttime;
      $this->_endtime = $endtime;
      include_once 'dbinfo.php';
    }

    public function getRoom()
    {
      return ($this->_roomid);
    }


    public function getRoomName()
    {        
        $roomname = "error";
        $db = new DBInfo();
        $conn = mysql_connect(DBInfo::$server, DBInfo::$username, DBInfo::$password)
                           or die("Connection error " . mysql_error());
        mysql_select_db(DBInfo::$db);
        $query = sprintf("SELECT roomname ".
                         "FROM room " .
                         "WHERE roomid='%s'",
                         mysql_real_escape_string($this->_roomid));
        $result = mysql_query($query);
        $row = mysql_fetch_row($result);
        $roomname = $row[0];
        mysql_close($conn);

        return ($roomname);
    }

    public function getValue()
    {
      $value = "OFF";

      if($this->_value > 5)
      {
        $value = $this->_value;
      }
      
      return ($value);
    }

    public function getDay()
    {
      return ($this->_day);
    }

    public function getDayName()
    {
        include_once 'Day.php';
        $day = new Day();
        return ($day->getWeekDayName($this->_day));
    }

    public function getStartTime()
    {
      return ($this->_starttime);
    }

    public function getEndTime()
    {
      return ($this->_endtime);
    }
}
?>
