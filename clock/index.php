<?php
if (isset($_GET['turn']) && !isset($_GET['start'])) {
  if (isset($_GET['days']))
    header('Location: http://langstuff.konata.fi/relaygame/clock/?turn='. $_GET['turn'] .'&start='. time() .'&days='. $_GET['days']);
  else
    header('Location: http://langstuff.konata.fi/relaygame/clock/?turn='. $_GET['turn'] .'&start='. time());
}
?>
<html>
<head>
<title>Relay Clock</title>
<script type="text/javascript">
function startTimer(duration, display) {
    var timer = duration, days, hours, minutes, seconds;
    setInterval(function () {
<?php
if (isset($_GET['days'])) { echo '
        days    = parseInt(timer / 60 / 60 / 24 % '. $_GET['days'] .", 10);\n";}?>
        hours   = parseInt(timer / 60 / 60 % 24, 10);
        minutes = parseInt(timer / 60 % 60, 10);
        seconds = parseInt(timer % 60, 10);

        hours   = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = <?php
if (isset($_GET['days'])) echo 'days + "d " + '; ?>hours + "h " + minutes + "m " + seconds + "s";

        if (--timer < 0) {
            timer = duration;
        }
    }, 1000);
}

window.onload = function () {
    var timelimit = <?php
if (isset($_GET['start'])) {
  if (isset($_GET['days'])) {
    echo ($_GET['start'] - time()) + (60*60*24*$_GET['days']);
  } else {
    echo ($_GET['start'] - time()) + (60*60*24);
  }
} else {
  echo '0';
}
?>;
    var display = document.querySelector('#time');
    startTimer(timelimit, display);
};
</script>
</head>
<body style="text-align: center;">
<p style="font-size: 600%;">Relay Clock</p>
<?php
if (!isset($_GET['turn'])) {
?>
<form method="get" action="index.php">
<p style="font-size:400%;">Whose turn are we timing?</p>
<input type="text" name="turn" /><br />
<input type="radio" name="days" value="1" />1 days <input type="radio" name="days" value="2" />2 days <input type="radio" name="days" value="3" />3 days<br />
<input type="submit" />
</form>
<?php
} else {
  if (isset($_GET['days']))
    $url = 'http://langstuff.konata.fi/relaygame/clock/?turn='. $_GET['turn'] .'&start='. $_GET['start'] .'&days='. $_GET['days'];
  else
    $url = 'http://langstuff.konata.fi/relaygame/clock/?turn='. $_GET['turn'] .'&start='. $_GET['start'];
  echo '<p style="font-size: 400%;">'. $_GET['turn'] .'\'s turn</p>
  <p style="font-size: 200%;"><span id="time"></span></p>
  <form method="get" action="index.php">
  <p>Link to this timer: <a href="'. $url .'">'. $url .'</a></p>
  <p style="font-size:150%;">The next turn is here! Whose turn are we timing now?</p>
  <input type="text" name="turn" /> <br />
<label><input type="radio" name="days" value="1" />1 day</label> <label><input type="radio" name="days" value="2" />2 days</label> <label><input type="radio" name="days" value="3" />3 days</label><br />
<input type="submit" />';
}
?>
</form>
</body>
</html>
