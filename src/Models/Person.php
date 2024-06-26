<?php

namespace FamilyTree365\LaravelGedcom\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    /**
     * @var string
     */
    public $birthday;
    /**
     * @var string
     */
    public $deathday;
    public $events;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'birthday', 'deathday'];

    protected $fillable = [
        'gid',
        'givn',
        'surn',
        'sex',
        'child_in_family_id',
        'description',
        'title', 'name', 'appellative', 'uid', 'email', 'phone', 'birthday', 'birth_year',
        'deathday', 'death_year', 'burial_day', 'burial_year', 'bank', 'bank_account',
        'uid', 'chan', 'rin', 'resn', 'rfn', 'afn', 'type',
    ];

    public function events()
    {
        return $this->hasMany(PersonEvent::class);
    }

    public function child_in_family()
    {
        return $this->belongsTo(Family::class, 'child_in_family_id');
    }

    public function husband_in_family()
    {
        return $this->hasMany(Family::class, 'husband_id');
    }

    public function wife_in_family()
    {
        return $this->hasMany(Family::class, 'wife_id');
    }

    public function fullname()
    {
        return $this->givn.' '.$this->surn;
    }

    public function getSex()
    {
        if ($this->sex == 'F') {
            return 'Female';
        }

        return 'Male';
    }

    public static function getList()
    {
        $persons = self::query()->get();
        $result = [];
        foreach ($persons as $person) {
            $result[$person->id] = $person->fullname();
        }

        return collect($result);
    }

    public function addEvent($title, $date, $place, $description = '')
    {
        $place_id = Place::getIdByTitle($place);
        $event = PersonEvent::query()->updateOrCreate(
            [
                'person_id' => $this->id,
                'title'     => $title,
            ],
            [
                'person_id'   => $this->id,
                'title'       => $title,
                'description' => $description,
            ]
        );

        if ($date) {
            $event->date = $date;
            $event->save();
        }

        if ($place) {
            $event->places_id = $place_id;
            $event->save();
        }

        // add birthyear to person table ( for form builder )
        if ($title == 'BIRT' && !empty($date)) {
            $this->birthday = date('Y-m-d', strtotime((string) $date));
        }
        // add deathyear to person table ( for form builder )
        if ($title == 'DEAT' && !empty($date)) {
            $this->deathday = date('Y-m-d', strtotime((string) $date));
        }
        $this->save();

        return $event;
    }

    public function birth()
    {
        return $this->events->where('title', '=', 'BIRT')->first();
    }

    public function death()
    {
        return $this->events->where('title', '=', 'DEAT')->first();
    }

    public function appellative()
    {
        return $this->givn;
    }
}
