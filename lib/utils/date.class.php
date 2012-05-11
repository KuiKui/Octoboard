<?php

class myDate
{
  public static function dateRangeArray($start, $end)
  {
    $range = array();
    
    if (is_string($start) === true) $start = strtotime($start);
    if (is_string($end) === true ) $end = strtotime($end);
    
    do {
      $range[] = date('Y-m-d', $start);
      $start = strtotime("+ 1 day", $start);
    } while($start <= $end);
    
    return $range;
  }  
}
