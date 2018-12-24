# SandClock
Set of functions for working with date and time. For now just formatting.

## How to use
Easy:
```php
echo (new \SandClock\Api)->format(1234567890);
```
will output `2009-02-13 23:31:30.000000`

You can format to your liking
```php
echo (new \CuteBytes\Api)->setFormat('H:i')->format(1234567890);
```
to get `23:31`
