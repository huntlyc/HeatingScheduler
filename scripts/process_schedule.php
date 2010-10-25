<?php    

    include_once 'dbinfo.php';

    $_DEBUG = true;

    $day = date("N"); //numerical day of week, from start of week (Monday), ie 1-7.
    $time = date("H:i"); //24hr time
    $getString = ""; //HTTP "GET" string to send to the Arduino
    $numParamCouples = 0;

    if ($_DEBUG)
    {
      echo "Day: {$day} Time: {$time}\n\n";
    }

    $db = new DBInfo();
    $conn = mysql_connect(DBInfo::$server, DBInfo::$username, DBInfo::$password)
                   or die("Connection error " . mysql_error());
    mysql_select_db(DBInfo::$db);

    //Get all entries to be started now
    $query = "SELECT room, value FROM schedule WHERE day='{$day}' AND starttime='{$time}'";
    $result = mysql_query($query) or die("Query error in process_schedule.php: " . mysql_error());

    //Build up GET string "?id=#&val=#&id=#&val..."
    while ($row = mysql_fetch_assoc($result))
    {
        if($numParamCouples == 0)
        {
            $getString .= "?";
        }
        else
        {
            $getString .= "&";
        }

        $getString .= "id={$row['room']}&val={$row['value']}";

        $numParamCouples++;
    }

    //Get all entries ending now
    $query = "SELECT room FROM schedule WHERE day='{$day}' AND endtime='{$time}'";
    $result = mysql_query($query) or die("Query error in process_schedule.php: " . mysql_error());
        
    //Build up GET string "?id=#&val=#&id=#&val..."
    while ($row = mysql_fetch_assoc($result))
    {
        if($numParamCouples == 0)
        {
            $getString .= "?";
        }
        else
        {
            $getString .= "&";
        }

        $getString .= "id={$row['room']}&val=5";

        $numParamCouples++;
    }

    //for crontab log
    $executionDateTime = date("o-m-d H:i:s"); //will read 2010-08-30 15:55:23
    print "[{$executionDateTime}]: # Commands executed: {$numParamCouples}\n";

    mysql_close($conn);

    if ($_DEBUG)
    {
       echo "GET STRING: {$getString}\n\n";
    }

    //If we have at least 1 id & val pair, then send it to the Arduino
    if ($numParamCouples >= 1)
    {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'http://172.16.11.220/set.html' . $getString);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_TIMEOUT, '60');
      $content = trim(curl_exec($ch));

      if ($_DEBUG)
      {
        print "CONTENT:  {$content}\n\n";
      }
      
      curl_close($ch);
    }
?>
