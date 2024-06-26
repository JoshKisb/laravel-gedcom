<?php

namespace FamilyTree365\LaravelGedcom\Utils\Importer\Indi;

use FamilyTree365\LaravelGedcom\Models\PersonDesi;

class Desi
{
    /**
     * String $desi
     * String $group
     * Integer $group_id.
     */
    public static function read($conn, string $desi, $subm_ids, $group = '', $group_id = 0)
    {
        // store alia
        if (isset($subm_ids[$desi])) {
            $subm_id = $subm_ids[$desi];
            $key = ['group'=>$group, 'gid'=>$group_id, 'desi'=>$desi];
            $data = ['group'=>$group, 'gid'=>$group_id, 'desi'=>$desi];
            $record = app(PersonDesi::class)->on($conn)->updateOrCreate($key, $data);
        }
    }
}
