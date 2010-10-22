<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>House Scheduler</title>
    <link type="text/css" href="css/cupertino/jquery-ui-1.8.5.custom.css" rel="Stylesheet" />
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="js/ajaxcalls.js"></script>
    <script type="text/javascript">
    $(function() {
      $("#tabs").tabs();

      loadSchedule();
      
      $("#addentryform").submit(function(){
        return false;
      });

      $("#addbutton").click(function(){
         addEntry();
      });
    });
  	</script>
  </head>
  <body>
    <div id="contentwrapper">
      <div class="header"><h1>Heating Scheduler</h1></div>
      <div id="tabs">
        <ul>
          <li><a href="#tab-heating">Heating</a></li>          
        </ul>
        <div id="tab-heating">
          <div class="boxtop"><div class="boxtl"><div class="boxtr"></div></div></div>
          <div class="boxcontentwrap">
            <div class="boxcontent">
              <!-- call get data script -->
              <h2>Create a new entry</h2>
              <form id="addentryform" action="scripts/addtoschedule.php" method="get">
                <table id="addentry">
                  <thead>
                    <tr>
                      <th>Room</th><th>Value</th><th>Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <label for="room">Room:</label>
                        <select id="room" name="room">
                          <option value="1">Scott's Room</option>
                          <option value="2">Huntly's Room</option>
                          <option value="3">The Office</option>
                        </select>
                      </td>
                      <td>
                        <select id="value" name="value">
                          <option value="5">OFF</option>
                          <option value="15">15</option>
                          <option value="16">16</option>
                          <option value="17">17</option>
                          <option value="18">18</option>
                          <option value="19">19</option>
                          <option value="20">20</option>                    
                          <option value="21">21</option>
                          <option value="22">22</option>
                          <option value="23">23</option>
                          <option value="24">24</option>
                          <option value="25">25</option>          
                        </select>     
                      </td>
                      <td>
                        <label for="day">Day:</label>
                        <select id="day" name="day">
                          <option value="1">Monday</option>
                          <option value="2">Tuesday</option>
                          <option value="3">Wednesday</option>
                          <option value="4">Thursday</option>
                          <option value="5">Friday</option>
                          <option value="6">Saturday</option>
                          <option value="7">Sunday</option>
                        </select>                      
                        <label for="hour">Time:</label>
                        <select id="hour" name="hour">
                          <option value="1">01</option>
                          <option value="2">02</option>
                          <option value="3">03</option>
                          <option value="4">04</option>
                          <option value="5">05</option>
                          <option value="6">06</option>
                          <option value="7">07</option>
                          <option value="8">08</option>
                          <option value="9">09</option>
                          <option value="10">10</option>
                          <option value="11">11</option>
                          <option value="12">12</option>
                          <option value="13">13</option>
                          <option value="14">14</option>
                          <option value="15">15</option>
                          <option value="16">16</option>
                          <option value="17">17</option>
                          <option value="18">18</option>
                          <option value="19">19</option>
                          <option value="20">20</option>
                          <option value="21">21</option>
                          <option value="22">22</option>
                          <option value="23">23</option>
                        </select>
                        <select id="minute" name="minute">
                          <option value="00">00</option>
                          <option value="05">05</option>
                          <option value="10">10</option>
                          <option value="15">15</option>
                          <option value="20">20</option>
                          <option value="25">25</option>
                          <option value="30">30</option>
                          <option value="35">35</option>
                          <option value="40">40</option>
                          <option value="45">45</option>
                          <option value="50">50</option>
                          <option value="55">55</option>
                        </select>
                      </td>
                    </tr>                   
                  </tbody>
                </table>
                <div class="tablebutton">
                  <input type="hidden" id="type" name="type" value="heating"/>
                  <input type="submit" id="addbutton" value="Add"/>
                </div>
              </form>
              <h2>Current Schedule</h2>
              <div id="schedule">


              </div>
            </div>
          </div>
          <div class="boxbottom"><div class="boxbl"><div class="boxbr"></div></div></div>
        </div>
      </div><!-- end of tabbed content -->
    </div><!-- end of content wrap -->
  </body>
</html>
