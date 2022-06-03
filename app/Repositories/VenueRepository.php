<?php

namespace App\Repositories;

use App\Models\Venue;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class VenueRepository
 * @package App\Repositories
 * @version December 28, 2017, 1:12 pm UTC
 *
 * @method Venue findWithoutFail($id, $columns = ['*'])
 * @method Venue find($id, $columns = ['*'])
 * @method Venue first($columns = ['*'])
*/
class VenueRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'start_time',
        'end_time',
        'address',
        'location',
        'description',
        'mobile_number'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Venue::class;
    }
}
