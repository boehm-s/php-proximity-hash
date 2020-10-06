# php-proximity-hash ![Build Status](https://travis-ci.com/boehm-s/php-proximity-hash.svg?branch=main)

A port of [https://github.com/ashwin711/proximityhash](proximityhash) for PHP.

Geohash is a geocoding system invented by Gustavo Niemeyer and placed into the public domain. It is a hierarchical spatial data structure which subdivides space into buckets of grid shape, which is one of the many applications of what is known as a Z-order curve, and generally space-filling curves.

To visualize geohashes, you can [http://geohash.gofreerange.com](go here).

ProximityHash generates a set of geohashes that cover a circular area, given the center coordinates and the radius.

Below is an illustration of what it looks like on a map : 

![ProximityHash illustration](./img/proximityhash-illustration.png)


# Credits

```
https://github.com/ashwin711/proximityhash
```


# Installation 

```
composer require boehm_s/php-proximity-hash
```

# Usage

```php
use boehm_s\ProximityHash;

$latitude  = 48.858156;
$longitude = 2.294776;
$radius    = 1000;     // in meters
$precision = 6;        // number of characters in the geohashes generated
    
$res = ProximityHash::generate($latitude, $longitude, $radius, $precision);

```
