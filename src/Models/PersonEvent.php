<?php

namespace FamilyTree365\LaravelGedcom\Models;

use FamilyTree365\LaravelGedcom\Observers\EventActionsObserver;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonEvent extends Event
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'person_events';

    protected $fillable = [
        'person_id',
        'title',
        'type',
        'attr',
        'date',
        'created_date',
        'plac',
        'phon',
        'caus',
        'age',
        'agnc',
        'places_id',
        'description',
        'year',
        'month',
        'day',
    ];

    protected $gedcom_event_names = [
        'BIRT' => 'Birth',
        'DEAT' => 'Death',
    ];

    public static function boot()
    {
        parent::boot();
        static::observe(new EventActionsObserver());
    }

    public function person()
    {
        return $this->hasOne(Person::class, 'id', 'person_id');
    }
}
