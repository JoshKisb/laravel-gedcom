<?php

namespace FamilyTree365\LaravelGedcom\Utils\Exporter;

use FamilyTree365\LaravelGedcom\Models\Note;

class NoteRef
{
    /**
     * Gedcom\Record\NoteRef $noteref
     * String $group
     * Integer $group_id.
     */
    public static function read($conn, \Gedcom\Record\NoteRef $noteref, $group = '', $group_id = 0)
    {
        $note = $noteref->getNote();

        // store note
        $key = ['group'=>$group, 'gid'=>$group_id, 'note'=>$note];
        $data = ['group'=>$group, 'gid'=>$group_id, 'note'=>$note];
        $record = app(Note::class)->on($conn)->updateOrCreate($key, $data);

        // store Sources of Note
        $_group = 'note';
        $_gid = $record->id;
        // SourRef array
        $sour = $noteref->getSour();
        foreach ($sour as $item) {
            SourRef::read($conn, $item, $_group, $_gid);
        }
    }
}
