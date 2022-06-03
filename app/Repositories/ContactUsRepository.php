<?php

namespace App\Repositories;

use App\Models\ContactUs;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ContactUsRepository
 * @package App\Repositories
 * @version October 28, 2018, 8:07 pm UTC
 *
 * @method ContactUs findWithoutFail($id, $columns = ['*'])
 * @method ContactUs find($id, $columns = ['*'])
 * @method ContactUs first($columns = ['*'])
*/
class ContactUsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'address',
        'telephone',
        'email',
        'website'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ContactUs::class;
    }
}
