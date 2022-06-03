<?php

namespace App\Repositories;

use App\Models\Locale;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class LocaleRepository
 * @package App\Repositories
 * @version December 10, 2017, 10:11 am UTC
 *
 * @method Locale findWithoutFail($id, $columns = ['*'])
 * @method Locale find($id, $columns = ['*'])
 * @method Locale first($columns = ['*'])
*/
class LocaleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'code'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Locale::class;
    }
}
