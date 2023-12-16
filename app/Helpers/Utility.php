<?php

namespace App\Helpers;

class Utility
{
    public function __construct()
    {
    }

    public function removeLeaveSlots($avalable_slots, $leaved_slots){
        $valid_timeslots = [];
        $oph = $avalable_slots;
        foreach($leaved_slots as $os) {
            $oph = $this->flatAndClean($this->cutOpeningHours($oph, $os ));
        }
        $valid_timeslots = $oph;
        return $valid_timeslots;
    }

    public function removeOccupiedSlots($avalable_slots, $booked_slots, $buffer_time){
        $valid_timeslots = [];
        $oph = $avalable_slots;
        foreach($booked_slots as $os) {

            $toTime = $os[1]; // Replace with your original time in 'H:i' format
            $minutesToAdd = $buffer_time;

            $timestamp = strtotime($toTime);

            $timestamp += $minutesToAdd * 60;

            $os[1] = date('H:i', $timestamp);
            $oph = $this->flatAndClean($this->cutOpeningHours($oph, $os ));
        }

        $valid_timeslots = $oph;
        return $valid_timeslots;
    }

    public function flatAndClean($interwals) {
        $result = [];
        foreach($interwals as $inter) {
            foreach($inter as $i) {
                if($i[0]!=$i[1]) {
                    //$result[] = $i;
                    $result[] = [$this->numToTime($i[0]), $this->numToTime($i[1])];
                }
            }
        }
        return $result;
    }

    public function timeToNum($time) {
        preg_match('/(\d\d):(\d\d)/', $time, $matches);
        if( !empty($matches[1]) && !empty($matches[2])){
            return 60 * $matches[1] + $matches[2];
        }else{
            return 60*0;
        }
    }

    public function numToTime($num) {
        $m  = $num%60;
        $h = intval($num/60) ;
        return ($h>9? $h:"0".$h).":".($m>9? $m:"0".$m);

    }

    public function cutOpeningHours($op_h, $occ_slot) {
        $subsn=[];
        foreach($op_h as $oh) {
            $ohn = [$this->timeToNum($oh[0]), $this->timeToNum($oh[1])];
            $osn = [$this->timeToNum($occ_slot[0]), $this->timeToNum($occ_slot[1])];
            $subsn[] = $this->sub($ohn, $osn);
        }
        return $subsn;
    }
    public function sub($a,$b)
    {
        // case A: $b inside $a
        if($a[0]<=$b[0] and $a[1]>=$b[1]) return [ [$a[0],$b[0]], [$b[1],$a[1]] ];

        // case B: $b is outside $a
        if($b[1]<=$a[0] or $b[0]>=$a[1]) return [ [$a[0],$a[1]] ];

        // case C: $a inside $b
        if($b[0]<=$a[0] and $b[1]>=$a[1]) return [[0,0]]; // "empty interval"

        // case D: left end of $b is outside $a
        if($b[0]<=$a[0] and $b[1]<=$a[1]) return [[$b[1],$a[1]]];

        // case E: right end of $b is outside $a
        if($b[1]>=$a[1] and $b[0]>=$a[0]) return [[$a[0],$b[0]]];
    }
}
