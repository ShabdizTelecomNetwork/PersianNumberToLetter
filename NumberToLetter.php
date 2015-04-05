<?php
/**
*  @author mostafa pourgharib
*/

class NumberToLetter
{ 
    public $join = ' و ';
    public $dot = ' ممیز ';
     
    private $ref = array(
        array('', '', ''), 
        array('یک', 'ده', 'صد'), 
        array('دو', 'بیست', 'دویست'), 
        array('سه', 'سی', 'سیصد'), 
        array('چهار', 'چهل', 'چهارصد'), 
        array('پنج', 'پنجاه', 'پانصد'), 
        array('شش', 'شصت', 'ششصد'), 
        array('هفت', 'هفتاد', 'هفتصد'), 
        array('هشت', 'هشتاد', 'هشتصد'), 
        array('نه', 'نود', 'نهصد')
    );
    
    private $map = array(
        'ده و یک' => 'یازده',
        'ده و دو' => 'دوازده',
        'ده و سه' => 'سیزده', 
        'ده و چهار' => 'چهارده',
        'ده و پنج' => 'پانزده',
        'ده و شش' => 'شانزده', 
        'ده و هفت' => 'هفده',
        'ده و هشت' => 'هجده',
        'ده و نه' => 'نوزده'
    );
    
    private $unitTable = array(
        '', 
        'هزار',
         'میلیون',
         'میلیارد',
         'بیلیون',
         'تریلیون',
         'کوادریلیون',
         'کوینتیلیون',
         'سیکستیلون',
         'سپتیلیون',
         'اکتیلیون',
         'نونیلیون',
         'دسیلیون',
         'آندسیلیون',
         'دودسیلیون',
         'تریدسیلیون',
         'کواتردسیلیون',
         'کویندسیلیون',
         'سیکسدسیلیون',
         'سپتندسیلیون',
         'اکتودسیلیوم',
         'نومدسیلیون'
    );
    
    private function getNumberParts($strNumber)
    {
        $parts = array();
        $len   = strlen($strNumber);
        $out   = array();
        if ($len <= 3) {
            $out[] = $strNumber;
        } else {
            $remain    = $len % 3;
            $strNumber = strval($strNumber);
            switch ($remain) {
                case 0:
                    $parts = str_split($strNumber, 3);
                    break;
                
                default:
                    $parts = array(
                        array(
                            substr($strNumber, 0, $remain)
                        ),
                        str_split(substr($strNumber, $remain), 3)
                    );
                    break;
            }
            foreach ($parts as $key => $part) {
                if (is_array($part)) {
                    foreach ($part as $key => $p) {
                        $out[] = $p;
                    }
                } else {
                    $out[] = $part;
                }
            }
        }
        return array_reverse($out);
    }
    
    private function trimZero($number)
    {
        if (isset($number) && strlen($number) && $number[0] == 0) {
            $number = substr($number, 1);
            if (isset($number) && strlen($number) && $number[0] == 0) {
                $number = substr($number, 1);
            }
        }
        return $number;
    }
    
    private function _part($number)
    {
        $number  = $this->trimZero($number);
        $digits  = str_split((string) $number);
        $digits  = array_reverse($digits);
        $digPos  = count($digits);
        $self    = $this->partSolve($digits, $digPos);
        $self    = str_replace(' -  - ', $this->join, $self);
        $self    = str_replace(' - ', $this->join, $self);
        foreach ($this->map as $key => $word) {
            $self = str_replace($key, $word, $self);
        }
        return $self;
    }
    
    private function partSolve($parts, $pos)
    {
        if ($pos < 1) {
            return '';
        } else {
            $number     = $parts[$pos - 1];
            $numberSet  = isset($this->ref[$number]) ? $this->ref[$number] : array();
            $self       = isset($numberSet[$pos - 1]) ? $numberSet[$pos - 1] : '';
            $next       = $this->partSolve($parts, $pos - 1);
            if (isset($next) && $next != '') {
                return $self . ' - ' . $next;
            } else {
                return $self;
            }
        }
    }
    
    private function _solve($parts, $pos)
    {
        if ($pos < 1)
            return '';
        else {
            $number = $parts[$pos - 1];
            $self   = $this->_part($number);
            $unit   = $this->unitTable[$pos - 1];
            $next   = $this->_solve($parts, $pos - 1);
            if ($self != '') {
                if (isset($next) && $next != '') {
                    return $self . ' ' . $unit . ' - ' . $next;
                } else {
                    return $self . ' ' . $unit;
                }
            }
            return $next;
        }
    }

    private function getPart($number)
    {
        $numberParts = $this->getNumberParts($number);
        $result      = $this->_solve($numberParts, count($numberParts));
        return str_replace(' - ', $this->join, $result);
    }
    
    public function solve($number)
    {
        $realNumberParts = explode('.', strval($number));
        switch (count($realNumberParts)) {
            case 1:
                return ($result = $this->getPart($realNumberParts[0])) ? $result : 'صفر';
                break;
            case 2:
                $firstPart  = ($result = $this->getPart($realNumberParts[0])) ? $result : 'صفر';
                $secondPart = $this->getPart($realNumberParts[1]);
                if($secondPart) {
                    return $firstPart . $this->dot . $secondPart;
                }
                return $firstPart;
                break;                
            default:
                return false;
                break;
        }
    }
} 