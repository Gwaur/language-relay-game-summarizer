<?php
function countprefix($text, $prefix) {
  for ($i = 0;; $i++) {
    if ($text{$i} != $prefix) break;
  }
  return $i;
}
function brackets($text) {
  $text = str_replace('<', '&lt;', $text);
  $text = str_replace('>', '&gt;', $text);
  $text = str_replace('&lt;table&gt;', '<table>', $text);
  return $text;
}
function cleanarray($array) {
  ksort($array);
  foreach ($array as $num => $val) {
    for (;;) {
      if (!isset($array[$num-1]) && $num > 0) {
        $array[$num-1] = $array[$num];
        unset($array[$num]);
        $num--;
      } else {
        break;
      }
    }
  }
  return $array;
}
function parsemarkup($text) {
  $text = brackets(str_replace("\r\n", "\n", $text));
  $rows = explode("\n", $text);
  $state = '?';
  $final = '';
  $tables = array();
  for ($i = 0; $i < count($rows); $i++) {
    if ($rows[$i] != '' && $rows[$i]{0} == ',') {
      $a = countprefix($rows[$i], ',');
      if (substr($rows[$i], -7) == '<table>') {
        $final .= substr($rows[$i], $a, -7) .'<table>';
        for ($z = $i+1; $z < count($rows);) {
          if (strpos($rows[$z], "\t") !== FALSE) {
            $final .= '<tr><td>'. str_replace("\t", '</td><td>', $rows[$z]) .'</td></tr>';
            unset($rows[$z]);
            $rows = cleanarray($rows);
          } else {
            $final .= "</table>\n";
            break;
          }
        }
        $rows[$i] = str_repeat(',', $a) . $final;
        $final = '';
      }
    }
  }
  $final = '';
  for ($i = 0; $i < count($rows); $i++) {
    if ($rows[$i] != '' && $rows[$i]{0} == ':') {
      $a = countprefix($rows[$i], ':');
      $final .= '<h'. $a .'>'. substr($rows[$i], $a) .'</h'. $a .">\n";
    } else if ($rows[$i] != '' && $rows[$i]{0} == ',') {
      $a = countprefix($rows[$i], ',');
      if ($state == '?') {
        $state = $a;
        $final .= str_repeat("<ul>\n<li>", $a) . substr($rows[$i], $a);
      } else if ($state != '?') {
        if ($a == $state) {
          $final .= "</li>\n<li>". substr($rows[$i], $a);
        } else if ($a > $state) {
          $state = $a;
          $final .= "<ul>\n<li>". substr($rows[$i], $a);
        } else if ($a < $state) {
          $final .= str_repeat("\n</li></ul>", ($state-$a));
          $state = $a;
          $final .= '</li><li>'. substr($rows[$i], $a);
        }
      }
      if (!isset($rows[$i+1]) || $rows[$i+1] == '' || $rows[$i+1]{0} != ',') {
        $final .= str_repeat("</li></ul>\n", $state);
        $state = '?';
      }
    } else if ($rows[$i] == "<table>") {
      $state = 'table';
      $final .= "<table>\n";
    } else if ($state == 'table') {
      if (strpos($rows[$i], "\t") !== FALSE) {
        $final .= '<tr><td>'. str_replace("\t", '</td><td>', $rows[$i]) ."</td></tr>\n";
        if (!isset($rows[$i+1])) {
          $final .= "</table>\n";
          $state = '?';
        }
      } else {
        $final .= "</table>\n";
        $state = '?';
      }
    } else if ($rows[$i] != '') {
      if (!isset($rows[$i+1]) || $rows[$i+1] == '') {
        if ($state == '?') {
          $final .= '<p>'. $rows[$i] .'</p>';
        } else {
          $final .= $rows[$i] .'</p>';
          $state = '?';
        }
      } else {
        if ($state == '?') {
          $final .= '<p>'. $rows[$i] .'<br />';
          $state = 'para';
        } else {
          $final .= $rows[$i] .'<br />';
        }
      }
    }
  }
  return $final;
}
function parseprev($text) {
  $text = brackets(str_replace("\r\n", "\n", $text));
  $rows = explode("\n", $text);
  for ($i = 0; $i < count($rows); $i++) {
    if ($rows[$i] == '') {
      unset($rows[$i]);
      $rows = cleanarray($rows);
    } else if ($rows[$i]{0} == ',') {
      $a = countprefix($rows[$i], ',');
      $rows[$i] = substr($rows[$i], $a);
    } else if ($rows[$i]{0} == ':') {
      $a = countprefix($rows[$i], ':');
      $rows[$i] = substr($rows[$i], $a);
    }
  }
  for ($i = count($rows)-1; $i > 0; $i--) {
    if ($i >= 3) {
      unset($rows[$i]);
    }
  }
  return implode('<br />', $rows);
}
