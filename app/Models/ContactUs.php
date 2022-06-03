<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;


/**
 * @SWG\Definition(
 *      definition="ContactUs",
 *      required={"address", "telephone", "email", "website"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="address",
 *          description="address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="telephone",
 *          description="telephone",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="website",
 *          description="website",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class ContactUs extends Model
{
    use SoftDeletes;
    //use Translatable;


    public $translationModel = 'App\ContactUsLocale';


     public $translatedAttributes = ['address'];

    public $table = 'contactus';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'address',
        'telephone',
        'email',
        'website'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'address' => 'string',
        'telephone' => 'string',
        'email' => 'string',
        'website' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'address' => 'required',
        'telephone' => 'required|regex:/^[0-9. -]+$/',
        'email' => 'required|email',
        'website' => 'required|url',
        'contactMedia.*.image' => 'nullable|mimes:jpeg,jpg,png,svg',
        'contactMedia.*.url'   => 'nullable|url',
    ];

    public function contactMedia()
    {
        return $this->hasMany('App\ContactMedia','contactus_id');
    }

}
