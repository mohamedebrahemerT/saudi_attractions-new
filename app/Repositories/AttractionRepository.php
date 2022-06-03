<?php

namespace App\Repositories;

use App\Models\Attraction;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AttractionRepository
 * @package App\Repositories
 * @version January 9, 2018, 11:41 am UTC
 *
 * @method Attraction findWithoutFail($id, $columns = ['*'])
 * @method Attraction find($id, $columns = ['*'])
 * @method Attraction first($columns = ['*'])
*/
class AttractionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'address',
        'location',
        'mobile_number',
        'description'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Attraction::class;
    }
}
