<?php

namespace App\Repositories;

use App\Models\SubCategory;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SubCategoryRepository
 * @package App\Repositories
 * @version December 10, 2017, 12:21 pm UTC
 *
 * @method SubCategory findWithoutFail($id, $columns = ['*'])
 * @method SubCategory find($id, $columns = ['*'])
 * @method SubCategory first($columns = ['*'])
*/
class SubCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SubCategory::class;
    }
}
