<?php

namespace App\Repositories;

use App\Models\Newsletter;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class NewsletterRepository
 * @package App\Repositories
 * @version February 1, 2018, 1:04 pm UTC
 *
 * @method Newsletter findWithoutFail($id, $columns = ['*'])
 * @method Newsletter find($id, $columns = ['*'])
 * @method Newsletter first($columns = ['*'])
*/
class NewsletterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'content'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Newsletter::class;
    }
}
