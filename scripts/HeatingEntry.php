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
class HeatingEntry implements ScheduleEntry
{
    private $_roomid;
    private $_value;
    private $_day;
    private $_time;

    public function  __construct($roomid, $value, $day, $time)
    {
      $this->_roomid = $roomid;
      $this->_value = $value;
      $this->_day = $day;
      $this->_time = $time;
    }

    public function getRoom()
    {
      return ($this->_roomid);
    }

    public function getValue()
    {
      return ($this->_value);
    }

    public function getDay()
    {
      return ($this->_day);
    }

    public function getTime()
    {
      return ($this->_time);
    }
}
?>
