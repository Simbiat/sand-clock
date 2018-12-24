<?php
declare(strict_types=1);
namespace SandClock;

class Api
{
    private $dtformat = 'Y-m-d H:i:s.u';
    
    private function time($time = 0): string
    {
        if (is_int($time)) {
            if (is_numeric($time)) {
                 if ((int)$time == $time) {
                    $time = (int)$time;
                 } else {
                    $time = strtotime($time);
                 }
            } else {
                $time = strtotime($time);
            }
        } else {
            $time = microtime(true);
        }
        if ($time === false) {
            $time = microtime(true);
        }
        return (\DateTime::createFromFormat('U.u', number_format($time, 6, '.', '')))->format($this->getFormat());
    }
    
    #####################
    #Setters and getters#
    #####################
    public function getFormat(): string
    {
        return $this->dtformat;
    }
    
    public function setFormat(string $dtformat): self
    {
        $this->dtformat = $dtformat;
        return $this;
    }
}
?>