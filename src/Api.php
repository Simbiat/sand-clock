<?php
declare(strict_types=1);
namespace SandClock;

class Api
{
    private string $dtformat = 'Y-m-d H:i:s.u';
    const timeunits = [
        'seconds'=>[
	        'dependon'=>'seconds',
	        'power'=>1,
	        'lang'=>[
	            'de'=>['Sekunde','Sekunden'],
	            'en'=>['second','seconds'],
	            'es'=>['segundo','segundos'],
	            'fr'=>['seconde','secondes'],
	            'it'=>['secondo','secondi'],
	            'jp'=>['byō','byō'],
	            'ru'=>['секунда','секунд'],
	        ],
        ],
        'minutes'=>[
	        'dependon'=>'seconds',
	        'power'=>60,
	        'lang'=>[
	            'de'=>['Minute','Minuten'],
	            'en'=>['minute','minutes'],
	            'es'=>['minuto','minutos'],
	            'fr'=>['minute','minutes'],
	            'it'=>['minuto','minuti'],
	            'jp'=>['bun','bun'],
	            'ru'=>['минута','минут'],
	        ],
        ],
        'hours'=>[
	        'dependon'=>'minutes',
	        'power'=>60,
	        'lang'=>[
	            'de'=>['Stunde','Stunden'],
	            'en'=>['hour','hours'],
	            'es'=>['hora','horas'],
	            'fr'=>['heure','heures'],
	            'it'=>['ora','ore'],
	            'jp'=>['jikan','jikan'],
	            'ru'=>['час','часов'],
	        ],
        ],
        'days'=>[
	        'dependon'=>'hours',
	        'power'=>24,
	        'lang'=>[
	            'de'=>['Tag','Tage'],
	            'en'=>['day','days'],
	            'es'=>['día','días'],
	            'fr'=>['jour','jours'],
	            'it'=>['giorno','giorni'],
	            'jp'=>['hi','hi'],
	            'ru'=>['день','дней'],
	        ],
        ],
        'years'=>[
	        'dependon'=>'days',
	        'power'=>365,
	        'lang'=>[
	            'de'=>['Jahr','Jahre'],
	            'en'=>['year','years'],
	            'es'=>['año','años'],
	            'fr'=>['année','années'],
	            'it'=>['anno','anni'],
	            'jp'=>['toshi','toshi'],
	            'ru'=>['год','лет'],
	        ],
        ],
        'months'=>[
	        'dependon'=>'days',
	        'power'=>30,
	        'lang'=>[
	            'de'=>['Monat','Monate'],
	            'en'=>['month','months'],
	            'es'=>['mes','meses'],
	            'fr'=>['mois','mois'],
	            'it'=>['mese','mesi'],
	            'jp'=>['tsuki','tsuki'],
	            'ru'=>['месяц','месяцев'],
	        ],
        ],
        'weeks'=>[
	        'dependon'=>'days',
	        'power'=>7,
	        'lang'=>[
	            'de'=>['Woche','Wochen'],
	            'en'=>['week','weeks'],
	            'es'=>['semana','semanas'],
	            'fr'=>['semaine','semaines'],
	            'it'=>['settimana','settimane'],
	            'jp'=>['shū','shū'],
	            'ru'=>['неделя','недель'],
	        ],
        ],
        'decades'=>[
	        'dependon'=>'years',
	        'power'=>10,
	        'lang'=>[
	            'de'=>['Jahrzehnt','Jahrzehnte'],
	            'en'=>['decade','decades'],
	            'es'=>['década','décadas'],
	            'fr'=>['décennie','décennies'],
	            'it'=>['decennio','decenni'],
	            'jp'=>['tōnen','tōnen'],
	            'ru'=>['декада','декад'],
	        ],
        ],
        'centuries'=>[
	        'dependon'=>'decades',
	        'power'=>10,
	        'lang'=>[
	            'de'=>['Jahrhundert','Jahrhunderte'],
	            'en'=>['century','centuries'],
	            'es'=>['siglo','siglos'],
	            'fr'=>['siècle','siècle'],
	            'it'=>['secolo','secoli'],
	            'jp'=>['seiki','seiki'],
	            'ru'=>['век','веков'],
	        ],
        ],
        'millenniums'=>[
	        'dependon'=>'centuries',
	        'power'=>10,
	        'lang'=>[
	            'de'=>['Jahrtausend','Jahrtausende'],
	            'en'=>['millennium','millennia'],
	            'es'=>['milenio','milenio'],
	            'fr'=>['millénaire','millénaires'],
	            'it'=>['millennio','millenni'],
	            'jp'=>['sennenki','sennenki'],
	            'ru'=>['миллениум','миллениумов'],
	        ],
        ],
        'megannums'=>[
	        'dependon'=>'millenniums',
	        'power'=>1000,
	        'lang'=>[
	            'de'=>['Megannum','Megannum'],
	            'en'=>['megannum','megannums'],
	            'es'=>['megannum','megannums'],
	            'fr'=>['mégannum','mégannums'],
	            'it'=>['megannum','megannum'],
	            'jp'=>['meganamu','meganamu'],
	            'ru'=>['мегагод','мегалет'],
	        ],
        ],
        'aeons'=>[
	        'dependon'=>'megannums',
	        'power'=>1000,
	        'lang'=>[
	            'de'=>['Äon','Äonen'],
	            'en'=>['aeon','aeons'],
	            'es'=>['eón','eones'],
	            'fr'=>['aeon','aeons'],
	            'it'=>['eone','eoni'],
	            'jp'=>['ion','ion'],
	            'ru'=>['гигагод','гигалет'],
	        ],
        ],
    ];
    
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
    
    public function seconds($seconds = 0, $full = true, $lang = 'en'): string
    {
        if (!is_numeric($seconds)) {
            throw new \UnexpectedValueException('Seconds provided is not numeric.');
        }
        #Enforce lower case for consistency
        $lang = strtolower($lang);
        $units = self::timeunits;
        #Check if langauge is supported
        if (!in_array($lang, array_keys($units['seconds']['lang']))) {
            throw new \UnexpectedValueException('Unsupported language (`'.$lang.'`).');
        }
        $result = '';
        foreach ($units as $type=>$unit) {
            if ($type === 'seconds') {
                #Just adding seconds to the array to work with them going forward and explicitely converting to float (which is larger than integer) to prevent implicit conversions in following functions
                $units[$type]['value'] = floatval($seconds);
            } else {
                #Calculate current unit type value based on predefined power of the dependant
                $units[$type]['value'] = $units[$unit['dependon']]['value']/$unit['power'];
                if ($type === 'months') {
                    #Adjust number of days, in case we have 30 days or more, each 30 days is 1 month
                    while (floor($units[$type]['value'])>0 && $units[$unit['dependon']]['value']>=$unit['power']) {
                        $units[$unit['dependon']]['value'] = $units[$unit['dependon']]['value'] - $unit['power'];
                    }
                } else {
                    #Deduct current unit value from the previous one in order to retain only the 'remainder' of it. 'Weeks' have an extra check for consistency between weeks, months and days
                    if ($type !== 'weeks' || ($type === 'weeks' && floor($units[$type]['value'])>0 && $units[$unit['dependon']]['value']>=$unit['power'])) {
                        $units[$unit['dependon']]['value'] = abs($units[$unit['dependon']]['value']-floor($units[$type]['value'])*$unit['power']);
                    }
                }
                if ($type === 'weeks') {
                    #Adjust number of weeks, in case we have 4 weeks or more, each 4 weeks is ~1 month
                    while (floor($units['months']['value'])>0 && $units[$type]['value']>=4) {
                        $units[$type]['value'] = $units[$type]['value'] - 4;
                    }
                }
                #Add previous (already adjsuted) unit to resulting line. 'Years' and 'months' are skipped, to prevent early addition of 'days', since final value is known only on 'weeks' cycle
                if ($type !== 'years' && $type !== 'months' && floor($units[$unit['dependon']]['value'])>0) {
                    $result = floor($units[$unit['dependon']]['value']).($full ? ' '.(floor($units[$unit['dependon']]['value'])>1 ? $units[$unit['dependon']]['lang'][$lang][1] : $units[$unit['dependon']]['lang'][$lang][0]).' ' : ':').$result;
                }
                if ($type === 'weeks') {
                    #Adding weeks
                    if (floor($units[$type]['value'])>0) {
                        $result = floor($units[$type]['value']).($full ? ' '.(floor($units[$type]['value'])>1 ? $units[$type]['lang'][$lang][1] : $units[$type]['lang'][$lang][0]).' ' : ':').$result;
                    }
                    #Adding months
                    if (floor($units['months']['value'])>0) {
                        $result = floor($units['months']['value']).($full ? ' '.(floor($units['months']['value'])>1 ? $units['months']['lang'][$lang][1] : $units['months']['lang'][$lang][0]).' ' : ':').$result;
                    }
                }
                #Special for aeons, since last itteration
                if ($type === 'aeons'  && floor($units[$type]['value'])>0) {
                    $result = rtrim(trim(floor($units[$type]['value']).($full ? ' '.(floor($units[$type]['value'])>1 ? $units[$type]['lang'][$lang][1] : $units[$type]['lang'][$lang][0]).' ' : ':').$result), ':');
                }
            }
            
        }
        if (empty($result)) {
            $result = '0'.($full ? ' seconds' : '');
        }
        return $result;
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