<?php
declare(strict_types=1);
namespace SandClock;

class Api
{
    private $dtformat = 'Y-m-d H:i:s.u';
    
    public function format($time = 0): string
    {
        if (empty($time)) {
            $time = microtime(true);
        } else {
            if (is_numeric($time)) {
                $time = abs(intval($time));
            } else {
                if (is_string($time)) {
                    $time = strtotime($time);
                    if ($time === false) {
                        throw new \UnexpectedValueException('Time provided is a string and not recognized as acceptable datetime format.');
                    }
                } else {
                    throw new \UnexpectedValueException('Time provided is not numeric or string.');
                }
            }
        }
        return (\DateTimeImmutable::createFromFormat('U.u', number_format($time, 6, '.', '')))->format($this->getFormat());
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