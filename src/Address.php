<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    /**
     * Address constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable(['zipcode', 'street', 'city', 'house_number', 'country_id']);
        $this->columns(['zipcode', 'street', 'city', 'house_number', 'country_id']);
        $this->associations([
            [
            'relation' => 'country',
            'model' => Country::class,
            'post_field' => 'country',
            'table_field' => 'name',
            'type' => BelongsTo::class
]
        ]);
        parent::__construct($attributes);
    }

    /**
     * The Countries
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}

//  create_countries_table (name, label)
//  via mvc-collections ga je de countries table opvullen nu maar 1 record: (nl, Netherlands)
//  pas de address migration aan en voeg country_id toe relaties op countries (en verwijder oude country veld)
// in de address post ga je country=nl meesturen en dan wil ik dat je via een association de country relatie opslaat.
// check dus in je testcase dat de response country_id=1  heeft <- voorspel dit (geef country_id mee in de response)
// maar nadat je dat getest hebt, wil ik geen country_id in response maar country = ['id' => , 'name' => 'label' => ]
