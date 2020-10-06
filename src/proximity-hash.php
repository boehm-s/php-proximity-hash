<?php


namespace boehm_s;

use Sk\Geohash\Geohash;

class ProximityHash {
    private static function in_circle_check($latitude, $longitude, $centre_lat, $centre_lon, $radius) {

        $x_diff = $longitude - $centre_lon;
        $y_diff = $latitude - $centre_lat;

        if (pow($x_diff, 2) + pow($y_diff, 2) <= pow($radius, 2)) {
            return true;
        }

        return false;
    }

    private static function get_centroid($latitude, $longitude, $height, $width) {

        $y_cen = $latitude + ($height / 2);
        $x_cen = $longitude + ($width / 2);

        return [$x_cen, $y_cen];
    }

    private static function convert_to_latlon($y, $x, $latitude, $longitude) {
        $r_earth = 6371000;

        $lat_diff = ($y / $r_earth) * (180 / M_PI);
        $lon_diff = ($x / $r_earth) * (180 / M_PI) / cos($latitude * M_PI / 180);

        $final_lat = $latitude + $lat_diff;
        $final_lon = $longitude + $lon_diff;

        return [$final_lat, $final_lon];
    }

    public static function generate($latitude, $longitude, $radius, $precision) {
        $geohash = new Geohash();
        $x = 0.0;
        $y = 0.0;

        $points = [];
        $geohashes = [];

        $grid_width = [5009400.0, 1252300.0, 156500.0, 39100.0, 4900.0, 1200.0, 152.9, 38.2, 4.8, 1.2, 0.149, 0.0370];
        $grid_height = [4992600.0, 624100.0, 156000.0, 19500.0, 4900.0, 609.4, 152.4, 19.0, 4.8, 0.595, 0.149, 0.0199];

        $height = ($grid_height[$precision - 1]) / 2;
        $width = ($grid_width[$precision - 1]) / 2;

        $lat_moves = (int) ceil($radius / $height); // 4
        $lon_moves = (int) ceil($radius / $width); // 2

        for ($i = 0; $i < $lat_moves; $i++) {
            $temp_lat = $y + $height * $i;

            for ($j = 0; $j < $lon_moves; $j++) {
                $temp_lon = $x + $width * $j;

                if (static::in_circle_check($temp_lat, $temp_lon, $y, $x, $radius)) {
                    list($x_cen, $y_cen) = static::get_centroid($temp_lat, $temp_lon, $height, $width);

                    list($lat, $lon) = static::convert_to_latlon($y_cen, $x_cen, $latitude, $longitude);
                    $points[]= [$lat, $lon];
                    list($lat, $lon) = static::convert_to_latlon(-$y_cen, $x_cen, $latitude, $longitude);
                    $points[]= [$lat, $lon];
                    list($lat, $lon) = static::convert_to_latlon($y_cen, -$x_cen, $latitude, $longitude);
                    $points[]= [$lat, $lon];
                    list($lat, $lon) = static::convert_to_latlon(-$y_cen, -$x_cen, $latitude, $longitude);
                    $points[]= [$lat, $lon];
                }
            }
        }

        foreach($points as $point) {
            $geohashes []= $geohash->encode($point[0], $point[1], $precision);
        }

        return array_values(array_unique($geohashes));
    }
}
