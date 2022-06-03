<?php

namespace App\Repositories;

use App\Models\SocialMedia;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SocialMediaRepository
 * @package App\Repositories
 * @version December 12, 2017, 9:20 am UTC
 *
 * @method SocialMedia findWithoutFail($id, $columns = ['*'])
 * @method SocialMedia find($id, $columns = ['*'])
 * @method SocialMedia first($columns = ['*'])
*/
class SocialMediaRepository extends BaseRepository
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
        return SocialMedia::class;
    }
}
