<?php

namespace App\Http\Controllers\API;

use App\Models\Notification;
use App\Transformers\NotificationTransformer;


class NotificationController extends ApiController
{
    protected $transformer;

    public function __construct(NotificationTransformer $transformer)
    {
        $this->transformer=$transformer;
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/notifications",
     *      summary="List Notifications",
     *      tags={"Notifications"},
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Notification en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *               @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                    @SWG\Items(
     *                   @SWG\Property(property="msg", type="string"),
     *                 )
     *              ),
     *          )
     *      )
     * )
     */

    public function listNotification($lang)
    {
        $notification = Notification::get()->toArray();
        $notification = $this->transformer->transformCollection($notification);
        return $this->respondAccepted($notification);
    }
}
