<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Day
 *
 * @author hunt0r
 */
class Day
{
    public static $MONDAY = 1;
    public static $TUESDAY = 2;
    public static $WEDNESDAY = 3;
    public static $THURSDAY = 4;
    public static $FRIDAY = 5;
    public static $SATURDAY = 6;
    public static $SUNDAY = 7;
    public static $EVERYDAY = 8;
    public static $WEEKDAY = 9;

    public function getWeekDayName($dayNumber)
    {
      $dayName = "error";

      switch($dayNumber)
      {
        case 1: $dayName = "Monday"; break;
        case 2: $dayName = "Tuesday"; break;
        case 3: $dayName = "Wednesday"; break;
        case 4: $dayName = "Thurday"; break;
        case 5: $dayName = "Friday"; break;
        case 6: $dayName = "Saturday"; break;
        case 7: $dayName = "Sunday"; break;

        default: $dayName = "unrecognised"; break;
      }

      return $dayName;
    }
}
?>
