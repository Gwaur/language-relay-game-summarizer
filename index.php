<?php
include('functions.php');
include('variables.php');
//$nosuchrelay becomes 1 if:
//- a relay has been requested but it doesn't exist
//- no relay has been requested
//nosuchrelay decides whether we want to print the list of all relays or
//the contents of one relay
if (isset($_GET['no']) && is_file('relay'. $_GET['no'])) {
  $no = $_GET['no'];
  $relay = file('relay'. $no);
  $date = $relay[0];
  $nosuchrelay = 0;
} else {
  $nosuchrelay = 1;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/$
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $community; ?> Relay Game - <?php echo $date; ?></title>
<style type="text/css">
@import url("relay2.css")
</style>
</head>
<body>
<?php if (isset($danger)) { echo '<h1 style="background: #faa;">I\'m doing some renovations to the programming, so if you see bugs, don\'t worry.</h1>'; } ?>
<?php
if ($nosuchrelay == 1) {
/* here we print the list of all relays because $nosuchrelay is 1 */

  echo '<h1>'. $community .' Relay Games</h1>';
  echo '<p><a href="#what">Game description and house rules.</a></p>';

  echo '<table>';
  for($i = 1;; $i++) {
    if (!file_exists('relay'. $i)) { break; }

    $relay = file('relay'. $i);

    $title = explode("\t", str_replace("\n", '', $relay[0]));
    $first = explode("\t", str_replace("\n", '', $relay[1]));
    $last = explode("\t", str_replace("\n", '', $relay[count($relay)-1]));

    if (file_exists('relay'. $i .'data/'. strtolower($first[1]) .'_text')) {
      $temp = file('relay'. $i .'data/'. strtolower($first[1]) .'_text');
      $first[2] = parseprev(implode("\n", $temp));
    } else {
      $first[2] = $notext;
    }
    if (file_exists('relay'. $i .'data/'. strtolower($first[1]) .'_engl')) {
      $temp = file('relay'. $i .'data/'. strtolower($first[1]) .'_engl');
      $first[3] = parseprev(implode("\n", $temp));
    } else {
      $first[3] = $notransl;
    }
    if (file_exists('relay'. $i .'data/'. strtolower($last[1]) .'_text')) {
      $temp = file('relay'. $i .'data/'. strtolower($last[1]) .'_text');
      $last[2] = parseprev(implode("\n", $temp));
    } else {
      $last[2] = $notext;
    }
    if (file_exists('relay'. $i .'data/'. strtolower($last[1]) .'_engl')) {
      $temp = file('relay'. $i .'data/'. strtolower($last[1]) .'_engl');
      $last[3] = parseprev(implode("\n", $temp));
    } else {
      $last[3] = $notransl;
    }

    if ($first[0] == '') $first[0] = $nolang;
    if ($last[0] == '') $last[0] = $nolang;

    if (!isset($title[1])) { $title[1] = ''; }
    if ($title[1] != 'hidden') {
      echo '<tr><td class="no" rowspan="5"><a href="./?no='. $i .'">#'. $i .'</td><th class="lang" colspan="3"><a href="./?no='. $i .'">'. $title[0] .'</a></th></tr>
<tr><th class="langsub" rowspan="2">Start</th><th class="langsub">'. $first[0] .' by '. $prefix . $first[1] .'</th><th class="langsub">English</th></tr>
<tr><td class="text">'. $first[2] .'</td><td class="text">'. $first[3] .'</td></tr>
<tr><th class="langsub" rowspan="2">Finish</th>	<th class="langsub">'. $last[0] .' by '. $prefix . $last[1] .'</th><th class="langsub">English</th></tr>
<tr><td class="text">'. $last[2] .'</td><td class="text">'. $last[3] .'</td></tr>';
    } else {
      if ($_SERVER['REMOTE_ADDR'] == $getstoseeall) {
        echo '<tr><td class="no" rowspan="5"><a href="./?no='. $i .'">#'. $i .'</a><br /><span class="hidden">(hidden)</span></td><th class="lang" colspan="3"><a href="./?no='. $i .'">'. $title[0] .'</a></th></tr>
<tr><th class="langsub" rowspan="2">Start</th><th class="langsub">'. $first[0] .' by '. $prefix . $first[1] .'</th><th class="langsub">English</th></tr>
<tr><td class="text">'. $first[2] .'</td><td class="text">'. $first[3] .'</td></tr>
<tr><th class="langsub" rowspan="2">Finish</th>	<th class="langsub">'. $last[0] .' by '. $prefix . $last[1] .'</th><th class="langsub">English</th></tr>
<tr><td class="text">'. $last[2] .'</td><td class="text">'. $last[3] .'</td></tr>';
      }
    }
  }
  echo '</table>';

  $gamedescription = file('gamedescription');
  $gamedescription = implode("\n", $gamedescription);
  echo str_replace('$host', $host, parsemarkup($gamedescription));
  unset($gamedescription);

  echo '<p><a href="clock/">The Relay Clock</a></p>

</body>
</html>';
  die();
}
/*the rest will happen only if we want to print out contents of a single relay*/
?>

<h1><?php echo $community; ?> Relay Game - <?php echo $date; ?></h1>

<?php
$temp = explode("\t", $relay[0]);
if (isset($temp[1]) && $temp[1] == "hidden\n") {
  if ($_SERVER['REMOTE_ADDR'] == $getstoseeall) {
    echo '<p>This relay is hidden.</p>';
  }
  else {
    echo '<p>This relay is currently hidden. Here are some potential reasons:</p>
<ul>
<li>The relay isn\'t finished.</li>
<li>The relay is finished but someone still hasn\'t sent their stuff to '. $host .'. Have you?</li>
<li>Everyone has sent their stuff to '. $host .' but he still hasn\'t summaried them.</li>
</ul>
</body></html>';
    die();
  }
}

if (isset($_GET['step'])) {
  $stepno = $_GET['step'];
} else {
  echo '<p><a href="./">Back to the list of Relay Games.</a></p>';
  $stepno = -1;
}

/* If a step has been requested, we print the links to the adjacent steps here*/

if ($stepno == 1) {
  $nextlang = explode("\t", $relay[($stepno+1)]);
  if ($nextlang[0] == '') $nextlang[0] = $nolang;
  echo '<p class="langlinks">&lArr; Previous step | <a href="./?no='. $no .'">Full Relay</a> | <a href="./?no='. $no .'&step='. ($stepno+1) .'">Next step ('. $nextlang[0] .') &rArr;</a></p>';
} else if ($stepno == (count($relay)-1)) {
  $prevlang = explode("\t", $relay[($stepno-1)]);
  if ($prevlang[0] == '') $prevlang[0] = $nolang;
  echo '<p class="langlinks"><a href="./?no='. $no .'&step='. ($stepno-1) .'">&lArr; Previous step ('. $prevlang[0] .')</a> | <a href="./?no='. $no .'">Full Relay</a> | Next step &rArr;</a></p>';
} else if ($stepno > 1 && $stepno < (count($relay)-1)) {
  $nextlang = explode("\t", $relay[($stepno+1)]);
  if ($nextlang[0] == '') $nextlang[0] = $nolang;
  $prevlang = explode("\t", $relay[($stepno-1)]);
  if ($prevlang[0] == '') $prevlang[0] = $nolang;
  echo '<p class="langlinks"><a href="./?no='. $no .'&step='. ($stepno-1) .'">&lArr; Previous step ('. $prevlang[0] .')</a> | <a href="./?no='. $no .'">Full Relay</a> | <a href="./?no='. $no .'&step='. ($stepno+1) .'">Next step ('. $nextlang[0] .') &rArr;</a></p>';
} else {

/* If a step has not been requested, we print a table showing all the steps*/

  if (isset($_GET['mode']) && $_GET['mode'] == 'alltran') {

/* If the table has been requested in the alltran mode, we print all the
   translations next to each other */
    echo '<p><a href="./?no='. $no .'">Back to the regular table</a></p>';
    echo '<table class="alltran"><tr>';
    for ($i = 1; $i < count($relay); $i++) {
      $step[$i] = explode("\t", $relay[$i]);
      $lang[$i] = $step[$i][0];
      $by[$i] = str_replace("\n", '', $step[$i][1]);
      if (file_exists('relay'. $no .'data/'. strtolower($by[$i]) .'_engl')) {
        $temp = file('relay'. $no .'data/'. strtolower($by[$i]) .'_engl');
        $english[$i] = parsemarkup(implode($temp));
      } else {
        $english[$i] = $notransl;
      }

      if ($lang[$i] == '') $lang[$i] = $nolang;
      if ($by[$i] == '') $by[$i] = $noby;
      echo '<td class="no"><a href="./?no='. $no .'&step='. $i .'">#'. $i .'</a></td>';
    }
    echo '</tr><tr>';
    for ($i = 1; $i < count($relay); $i++) {
      echo '<td class="lang"><a href="./?no='. $no .'&step='. $i .'">'. $lang[$i] .' by /u/'. $by[$i] .'</a></td>';
    }
    echo '</tr><tr>';
    for ($i = 1; $i < count($relay); $i++) {
      echo '<td class="text">'. $english[$i] .'</td>';
    }
    echo '</tr></table></body></html>';
    die();
  } else {

/* If the table has been requested in the regular mode, we print all the steps
   on top of each other with incomplete texts */
    echo '<p><a href="./?no='. $no .'&mode=alltran">View all the translations side by side.</a></p>';
    echo '<table class="data">';
    for ($i = 1; $i < count($relay); $i++) {
      $step = explode("\t", $relay[$i]);
      $lang = $step[0];
      $by = str_replace("\n", '', $step[1]);

      if(file_exists('relay'. $no .'data/'. strtolower($by) .'_text')) {
        $temp = file('relay'. $no .'data/'. strtolower($by) .'_text');
        $text = parseprev(implode("\n", $temp));
      } else {
        $text = $notext;
      }
      if(file_exists('relay'. $no .'data/'. strtolower($by) .'_engl')) {
        $temp = file('relay'. $no .'data/'. strtolower($by) .'_engl');
        $engl = parseprev(implode("\n", $temp));
      } else {
        $engl = $notransl;
      }

      echo '<tr><td class="no" rowspan="3"><a href="./?no='. $no .'&step='. $i .'">#'. $i .'</td><th class="lang" colspan="2"><a href="./?no='. $no .'&step='. $i .'">'. $lang .' by /u/'. $by .'</a></th></tr>
<tr><th class="langsub">'. $lang .'</th><th class="langsub">English</th></tr>
<tr><td class="text prev">'. $text .'</td><td class="text transl prev">'. $engl .'</td></tr>'."\n\n";
    }
  }
  echo '</table></body></html>';
  die();
/* here ends printing the steps of a single relay. the rest will happen only if
   a single step of a relay was requested */
}

/*here we print the contents of a single step of a relay */

$step = explode("\t", $relay[$stepno]);
$lang = $step[0];
$by = str_replace("\n", '', $step[1]);

/*gloss text english glossary grammar ipa*/
if (file_exists('relay'. $no .'data/'. strtolower($by) .'_text')) {
  $temp = file('relay'. $no .'data/'. strtolower($by) .'_text');
  $text = parsemarkup(implode($temp));
} else {
  $text = $notext;
}
if (file_exists('relay'. $no .'data/'. strtolower($by) .'_engl')) {
  $temp = file('relay'. $no .'data/'. strtolower($by) .'_engl');
  $engl = parsemarkup(implode($temp));
} else {
  $engl = $notransl;
}

if (file_exists('relay'. $no .'data/'. strtolower($by) .'_gloss')) {
  $temp = file('relay'. $no .'data/'. strtolower($by) .'_gloss');
  $gloss = parsemarkup(implode($temp));
} else {
  $gloss = $nogloss;
}
if (file_exists('relay'. $no .'data/'. strtolower($by) .'_glossary')) {
  $temp = file('relay'. $no .'data/'. strtolower($by) .'_glossary');
  $glossary = parsemarkup(implode($temp));
} else {
  $glossary = $noglossary;
}
if (file_exists('relay'. $no .'data/'. strtolower($by) .'_grammar')) {
  $temp = file('relay'. $no .'data/'. strtolower($by) .'_grammar');
  $grammar = parsemarkup(implode($temp));
} else {
  $grammar = $nogrammar;
}
if (file_exists('relay'. $no .'data/'. strtolower($by) .'_ipa')) {
  $temp = file('relay'. $no .'data/'. strtolower($by) .'_ipa');
  $ipa = parsemarkup(implode($temp));
} else {
  $ipa = $noipa;
}

echo '<table><tr><td class="no" rowspan="5">#'. $stepno .'</td><th class="lang" colspan="2">'. $lang .' by /u/'. $by .'</th></tr>
<tr><th class="langsub">'. $lang .'</th><th class="langsub">English</th></tr>
<tr><td class="text">'. $text .'</td><td class="transl text ">'. $engl .'</td></tr>
<tr><td class="text ipa">'. $ipa .'</td><td class="text gloss">'. $gloss .'</td></tr></table>

<table class="desc">
<tr><td class="glossary">'. $glossary .'
</td>
<td class="grammar">'. $grammar .'
</td></tr></table>';


?>
</body>
</html>
