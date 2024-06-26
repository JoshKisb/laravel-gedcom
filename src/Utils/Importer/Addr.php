<?php

namespace FamilyTree365\LaravelGedcom\Utils\Importer;

use FamilyTree365\LaravelGedcom\Models\Addr as MAddr;

class Addr
{
    /**
     * Gedcom\Record\Refn $noteref
     * String $group
     * Integer $group_id.
     */
    public static function read($conn, $addr)
    {
        $id = null;
        if ($addr == null) {
            return $id;
        }
        $adr1 = $addr->getAdr1();
        $adr2 = $addr->getAdr2();
        $city = $addr->getCity();
        $stae = $addr->getStae();
        $post = $addr->getPost();
        $ctry = $addr->getCtry();

        $addr = app(MAddr::class)->on($conn)->where([
            ['adr1', '=', $adr1],
            ['adr2', '=', $adr2],
            ['city', '=', $city],

            ['stae', '=', $stae],
            ['post', '=', $post],
            ['ctry', '=', $ctry],
        ])->first();
        if ($addr !== null) {
            $id = $addr->id;
        } else {
            $addr = app(MAddr::class)->on($conn)->create(['adr1' => $adr1, 'adr2' => $adr2, 'city' => $city, 'stae' => $stae, 'post' => $post, 'ctry' => $ctry]);
            $id = $addr->id;
        }

        return $id;
    }
}
