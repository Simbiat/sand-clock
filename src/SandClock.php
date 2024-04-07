<?php
declare(strict_types=1);
namespace Simbiat;

class SandClock
{
    const array timeunits = [
        'seconds'=>[
            'dependOn'=>'seconds',
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
            'value'=>0,
        ],
        'minutes'=>[
            'dependOn'=>'seconds',
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
            'value'=>0,
        ],
        'hours'=>[
            'dependOn'=>'minutes',
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
            'value'=>0,
        ],
        'days'=>[
            'dependOn'=>'hours',
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
            'value'=>0,
        ],
        'years'=>[
            'dependOn'=>'days',
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
            'value'=>0,
        ],
        'months'=>[
            'dependOn'=>'days',
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
            'value'=>0,
        ],
        'weeks'=>[
            'dependOn'=>'days',
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
            'value'=>0,
        ],
        'decades'=>[
            'dependOn'=>'years',
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
            'value'=>0,
        ],
        'centuries'=>[
            'dependOn'=>'decades',
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
            'value'=>0,
        ],
        'millenniums'=>[
            'dependOn'=>'centuries',
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
            'value'=>0,
        ],
        'megannums'=>[
            'dependOn'=>'millenniums',
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
            'value'=>0,
        ],
        'aeons'=>[
            'dependOn'=>'megannums',
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
            'value'=>0,
        ],
    ];

    public static function format(string|float|int $time = 0, string $dtFormat = 'Y-m-d H:i:s.u'): string
    {
        if (empty($time)) {
            $time = microtime(true);
        } elseif (is_numeric($time)) {
            $time = abs((int)$time);
        } elseif (is_string($time)) {
            $timeFromString = strtotime($time);
            if ($timeFromString === false) {
                throw new \UnexpectedValueException('Time provided is a string and not recognized as acceptable datetime format.');
            }
            $time = $timeFromString;
        } else {
            throw new \UnexpectedValueException('Time provided is not numeric or string.');
        }
        return (\DateTimeImmutable::createFromFormat('U.u', number_format($time, 6, '.', '')))->format($dtFormat);
    }

    public static function seconds(string|float|int $seconds = 0, bool $full = true, string $lang = 'en', bool $iso = false): string
    {
        if (!is_numeric($seconds)) {
            throw new \UnexpectedValueException('Seconds provided is not numeric.');
        }
        #Enforce lower case for consistency
        $lang = mb_strtolower($lang, 'UTF-8');
        $units = self::timeunits;
        #If using ISO 8601 duration format, remove unused units
        if ($iso) {
            unset($units['decades'], $units['centuries'], $units['millenniums'], $units['megannums'], $units['aeons']);
        }
        #Check if language is supported
        if (!array_key_exists($lang, $units['seconds']['lang'])) {
            throw new \UnexpectedValueException('Unsupported language (`'.$lang.'`).');
        }
        $result = '';
        foreach ($units as $type=>$unit) {
            if ($type === 'seconds') {
                #Just adding seconds to the array to work with them going forward and explicitly converting to float (which is larger than integer) to prevent implicit conversions in following functions
                $units[$type]['value'] = (float)$seconds;
            } else {
                #Calculate current unit type value based on predefined power of the dependant
                $units[$type]['value'] = $units[$unit['dependOn']]['value']/$unit['power'];
                if ($type === 'months') {
                    #Adjust number of days, in case we have 30 days or more, each 30 days is 1 month
                    while (floor($units[$type]['value'])>0 && $units[$unit['dependOn']]['value']>=$unit['power']) {
                        $units[ $unit['dependOn'] ]['value'] -= $unit['power'];
                    }
                    #Deduct current unit value from the previous one in order to retain only the 'remainder' of it. 'Weeks' have an extra check for consistency between weeks, months and days
                } elseif ($type !== 'weeks' || (floor($units[$type]['value'])>0 && $units[$unit['dependOn']]['value']>=$unit['power'])) {
                    $units[$unit['dependOn']]['value'] = abs($units[$unit['dependOn']]['value']-floor($units[$type]['value'])*$unit['power']);
                }
                if ($type === 'weeks') {
                    #Adjust number of weeks, in case we have 4 weeks or more, each 4 weeks is ~1 month
                    while (floor($units['months']['value'])>0 && $units[$type]['value']>=4) {
                        $units[ $type ]['value'] -= 4;
                    }
                }
                #Add previous (already adjusted) unit to resulting line. 'Years' and 'months' are skipped, to prevent early addition of 'days', since final value is known only on 'weeks' cycle
                if ($type !== 'years' && $type !== 'months' && floor($units[$unit['dependOn']]['value'])>0) {
                    $result = floor($units[$unit['dependOn']]['value']).($full === true ? ' '.(floor($units[$unit['dependOn']]['value'])>1 ? $units[$unit['dependOn']]['lang'][$lang][1] : $units[$unit['dependOn']]['lang'][$lang][0]).' ' : ':').$result;
                }
                if ($type === 'weeks') {
                    #Adding weeks
                    if (floor($units[$type]['value'])>0) {
                        $result = floor($units['weeks']['value']).($full === true ? ' '.(floor($units['weeks']['value'])>1 ? $units['weeks']['lang'][$lang][1] : $units['weeks']['lang'][$lang][0]).' ' : ':').$result;
                    }
                    #Adding months
                    if (floor($units['months']['value'])>0) {
                        $result = floor($units['months']['value']).($full === true ? ' '.(floor($units['months']['value'])>1 ? $units['months']['lang'][$lang][1] : $units['months']['lang'][$lang][0]).' ' : ':').$result;
                    }
                }
                #Special for aeons, since last iteration
                if ($type === 'aeons' && floor($units[$type]['value'])>0) {
                    $result = rtrim(trim(floor($units['aeons']['value']).($full === true ? ' '.(floor($units['aeons']['value'])>1 ? $units['aeons']['lang'][$lang][1] : $units['aeons']['lang'][$lang][0]).' ' : ':').$result), ':');
                }
            }

        }
        if (empty($result)) {
            if ($iso) {
                $result = 'P0Y0M0W0DT0H0M0S';
            } else {
                $result = '0' . ($full === true ? ' seconds' : '');
            }
        } elseif ($iso) {
            $result = 'P'.floor($units['years']['value']).'Y'.floor($units['months']['value']).'M'.floor($units['weeks']['value']).'W'.floor($units['days']['value']).'DT'.floor($units['hours']['value']).'H'.floor($units['minutes']['value']).'M'.floor($units['seconds']['value']).'S';
        }
        return $result;
    }
    
    public static function convertTimezone(int|string|\DateTime|\DateTimeImmutable $time, string|\DateTimeZone|null $from = null, string|\DateTimeZone $to = 'UTC'): \DateTime
    {
        #Validate and convert timezone, if any of them is a string
        if (is_string($from)) {
            if (!in_array($from, timezone_identifiers_list(), true)) {
                throw new \UnexpectedValueException('`'.$from.'` is not a supported timezone');
            }
            try {
                $from = new \DateTimeZone($from);
            } catch (\Throwable) {
                throw new \UnexpectedValueException('Failed to convert `'.$from.'` to time');
            }
        }
        if (is_string($to)) {
            if (!in_array($to, timezone_identifiers_list(), true)) {
                throw new \UnexpectedValueException('`'.$to.'` is not a supported timezone');
            }
            try {
                $to = new \DateTimeZone($to);
            } catch (\Throwable) {
                throw new \UnexpectedValueException('Failed to convert `'.$to.'` to time');
            }
        }
        #Set the object depending on what we got
        if ($time instanceof \DateTimeImmutable) {
            $datetime = $time;
        } elseif ($time instanceof \DateTime) {
            $datetime = clone $time;
        } else {
            #If we are here, it means we need a $from, because a string can have no timezone in it, and if it does not, we will get default one during conversion, which may not be desired
            if (empty($from)) {
                throw new \UnexpectedValueException('Time provided is not a DateTime(Immutable) and no original TimeZone was provided');
            }
            try {
                if (preg_match('/\d{10}/', (string)$time) === 1) {
                    $datetime = new \DateTime(timezone: $from);
                    $datetime->setTimestamp((int)$time);
                } else {
                    try {
                        $datetime = new \DateTime($time, $from);
                    } catch (\Throwable) {
                        $datetime = new \DateTime(timezone: $from);
                        $datetime->setTimestamp((int)$time);
                    }
                }
            } catch (\Throwable $throwable) {
                throw new \RuntimeException('Failed to create DateTime object from `'.$time.'`', previous: $throwable);
            }
        }
        #If somehow we do not have original timezone in DateTime object at this point - something went wrong.
        #Most likely DateTime(Immutable) was provided, but it somehow did have a timezone. Not sure if that can happen, but better check.
        if (!$datetime->getTimezone()) {
            throw new \UnexpectedValueException('No TimeZone found in DateTime object');
        }
        #Change the timezone
        $datetime->setTimezone($to);
        #Return
        return $datetime;
    }
}
