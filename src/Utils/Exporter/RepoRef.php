<?php

namespace FamilyTree365\LaravelGedcom\Utils\Exporter;

use FamilyTree365\LaravelGedcom\Models\Repository;

class RepoRef
{
    /**
     * Gedcom\Record\RepoRef $reporef
     * String $group
     * Integer $group_id.
     */
    public static function read($conn, \Gedcom\Record\RepoRef $reporef, $group = '', $group_id = 0)
    {
        if ($reporef == null) {
            return;
        }
        $repo = $reporef->getRepo();
        // store Source
        $key = ['group'=>$group, 'gid'=>$group_id, 'repo' => $repo];
        $data = [
            'group'=> $group,
            'gid'  => $group_id,
            'repo' => $repo,
        ];
        $record = app(Repository::class)->on($conn)->updateOrCreate($key, $data);

        $_group = 'reporef';
        $_gid = $record->id;
        // store Note
        $notes = $reporef->getNote();
        foreach ($notes as $item) {
            NoteRef::read($conn, $item, $_group, $_gid);
        }

        // store Caln
        $caln = $reporef->getCaln();
        foreach ($caln as $item) {
            if ($item) {
                Caln::read($conn, $item, $_group, $_gid);
            }
        }
    }
}
