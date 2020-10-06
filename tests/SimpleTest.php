<?php

use PHPUnit\Framework\TestCase;
use boehm_s\ProximityHash;

final class SimpleTest extends TestCase
{
    public function test(): void
    {
        $latitude  = 48.858156;
        $longitude = 2.294776;
        $radius    = 1000;
        $precision = 6;

        $res = ProximityHash::generate($latitude, $longitude, $radius, $precision);

        $this->assertEquals(count($res), 13);
    }
}
