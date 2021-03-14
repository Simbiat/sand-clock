# SandClock
Set of functions for working with date/time.

## How to use
### Time Formatting
```php
echo (new \Simbiat\SandClock)->format(1234567890);
```
will output `2009-02-13 23:31:30.000000`

You can format to your liking
```php
echo (new \Simbiat\SandClock)->setFormat('H:i')->format(1234567890);
```
to get `23:31`

### Seconds to time units left
```php
echo (new \Simbiat\SandClock)->format('31536050000829051');
```
will output `1 aeon 1 millennium 5 centuries 8 decades 5 years 6 months 1 week 1 day 7 hours 10 minutes 52 seconds`

You can output the same in 'short' format (without text), for example
```php
echo (new \Simbiat\SandClock)->format('31536050000829051', false);
```
will output `1:1:5:8:5:6:1:1:7:10:52`

This function is multilingual and at the moment supports 7 languages: Deutsch (de), English (en), Spanish (es), French (fr), Italian (it), Japanese (jp), Russian (ru). You can switch it like this:
```php
echo (new \Simbiat\SandClock)->format('31536050000829051', true, 'jp');
```
will output `1 ion 1 sennenki 5 seiki 8 tōnen 5 toshi 6 tsuki 1 shū 1 hi 7 jikan 10 bun 52 byō`