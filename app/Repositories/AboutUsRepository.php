<?php

namespace App\Repositories;

use App\Models\AboutUs;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AboutUsRepository
 * @package App\Repositories
 * @version January 31, 2018, 11:14 am UTC
 *
 * @method AboutUs findWithoutFail($id, $columns = ['*'])
 * @method AboutUs find($id, $columns = ['*'])
 * @method AboutUs first($columns = ['*'])
*/
class AboutUsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'paragraph'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AboutUs::class;
    }
}
