<?php

namespace App\Http\Controllers\API;

use App\Media;
use App\Models\Event;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use \Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateController extends ApiController
{
    protected $transformer;

    protected $registerValidationRules = [
        'name' => 'required',
        'email' => 'email|required|unique:users',
        'mobile_number' => 'nullable|numeric|min:12',
        'password' => 'required|min:6',
        'profile_image' => 'nullable|mimes:jpeg,jpg,png',
    ];


    protected $updateValidationRules = [
        'name' => 'min:3',
        'email' => 'email',
        'mobile_number' => 'nullable|numeric|min:12',
        'password' => 'min:6',
        'profile_image' => 'nullable|mimes:jpeg,jpg,png',
        'gender' => 'nullable|in:male,female',
        'birth_date' => 'nullable|date|date_format:Y-m-d',
        'country_id' => 'nullable|integer',
        'city_id' => 'nullable|integer',
        'address' => 'nullable'
    ];

    public function __construct(UserTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/register",
     *      summary="Register User",
     *      tags={"Authentication"},
     *      description="",
     *      consumes={"multipart/form-data"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of User en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *
     *     @SWG\Parameter(
     *          name="name",
     *          description=" Name of the User ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *
     *        @SWG\Parameter(
     *          name="email",
     *          description=" Email of the User ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *
     *    @SWG\Parameter(
     *          name="password",
     *          description=" Password of the User ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *
     *    @SWG\Parameter(
     *          name="mobile_number",
     *          description=" Number of the User",
     *          type="string",
     *          in="formData"
     *      ),
     *
     *     @SWG\Parameter(
     *          name="profile_image",
     *          description=" Image of the User ",
     *          type="file",
     *          in="formData"
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

    public function register(Request $request)
    {
        $register_validation = Validator::make($request->all(), $this->registerValidationRules);

        if ($register_validation->fails()) {
            $errors =$register_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }
        $input = $request->only('name', 'email', 'password', 'mobile_number');
        if ($request->file('profile_image')) {
            $fileName = uploadFile($request->file('profile_image'), 'users/profile_image');
            $media = Media::create(['image' => $fileName]);
            $input['profile_image'] = $media->id;
        }

        $input['forget_code'] = bin2hex(random_bytes('3'));
        $input['ja_id'] = bin2hex(random_bytes('3'));
        $user = User::create($input);
        $token =  JWTAuth::fromUser($user);
        $user=$this->transformer->transform($user);

        return $this->respondAccepted(['token' => $token, "user" => $user]);

    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/login",
     *      summary="Login User",
     *      tags={"Authentication"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of User en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *
     *        @SWG\Parameter(
     *          name="email",
     *          description=" Email of the User ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *
     *    @SWG\Parameter(
     *          name="password",
     *          description=" Password of the User ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
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
     *                    @SWG\Items(),

     *                   @SWG\Property(property="msg", type="string"),
     *                 )
     *              ),
     *          )
     *      )
     * )
     */

    public function login(Request $request)
    {

        $login_validation = Validator::make($request->all(), [

            'email' => 'required|email|max:255',
            'password' => 'required|min:6',

        ]);

        if ($login_validation->fails()) {
            $errors =$login_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }

        $credentials = $request->only('email', 'password');

        try {
            // verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->respondNotAuthenticated(['msg' => 'Invalid Credentials']);
            }

        } catch (JWTException $e) {
            // something went wrong
            return $this->respondNotAcceptable(['msg' => "couldn't create token"]);
        }
        $user = User::where('email', $request->input('email'))->first();
        // if no errors are encountered we can return a JWT
        if($user->is_blocked == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        $user=$this->transformer->transform($user);
        return $this->respondAccepted(['token' => $token, "user" => $user]);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/password/code",
     *      summary="Get Reset Code",
     *      tags={"Authentication"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *      produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of User en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *        @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
     *     @SWG\Parameter(
     *          name="email",
     *          description="Email of the user",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
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

    public function getResetCode(Request $request,  $lang)
    {
        $reset_code_validation = Validator::make($request->all(), ['email' => 'required|email']);

        if ($reset_code_validation->fails()) {
            $errors =$reset_code_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }

        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            return $this->respondNotAcceptable(['msg' => 'Email is invalid']);
        }
        if($user->is_blocked == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        Mail::send('emails.forget',['token'=>$user->forget_code], function ($message) use ($request)
        {

            $message->from('no-reply@saudiattractions.net', 'Saudi Attraction');

            $message->subject("Forget Password Saudi Attraction");

            $message->to($request->input('email'));

        });
        return $this->respondAccepted(['forget code' => $user->forget_code]);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/password/reset",
     *      summary="Reset Password",
     *      tags={"Authentication"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of User en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
     *        @SWG\Parameter(
     *          name="forget_code",
     *          description="Forget Code",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *     @SWG\Parameter(
     *          name="password",
     *          description="New password of the user",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
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


    public function passwordReset(Request $request, $lang)
    {
        $reset_password_validation = Validator::make($request->all(), [
            'password' => 'required|min:6',
        ]);

        if ($reset_password_validation->fails()) {
            $errors =$reset_password_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }

        $user = User::where('forget_code', $request->input('forget_code'))->first();
        if (!$user) {
            return $this->respondNotAcceptable(['msg' => 'User is invalid']);
        }
        if($user->is_blocked == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        $user->update(['password' => $request->input('password')]);
        $user->forget_code = bin2hex(random_bytes('3'));
        $user->save();
        return $this->respondAccepted(['msg' => 'Password has been reset']);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/profile",
     *      summary="View Profile",
     *      tags={"Authentication"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of User en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
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
    public function viewProfile($lang)
    {

        $user_id = JWTAuth::toUser(JWTAuth::getToken())->id;
        $user = User::with('media')->with('country')->with('city')->find($user_id)->toArray();
        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        $user = $this->transformer->transform($user);

        return $this->respondAccepted($user);
    }


    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/profile/edit",
     *      summary="Edit Profile",
     *      tags={"Authentication"},
     *      description="",
     *      consumes={"multipart/form-data"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of User en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          in="header"
     *      ),
     *
     *        @SWG\Parameter(
     *          name="email",
     *          description=" Email of the User ",
     *          type="string",
     *          in="formData"
     *      ),
     *
     *    @SWG\Parameter(
     *          name="password",
     *          description=" New Password of the User ",
     *          type="string",
     *          in="formData"
     *      ),
     *      @SWG\Parameter(
     *          name="name",
     *          description="New Name of the User",
     *          type="string",
     *          in="formData"
     *      ),
     *    @SWG\Parameter(
     *          name="mobile_number",
     *          description=" New Mobile Number of the User",
     *          type="string",
     *          in="formData"
     *      ),
     *    @SWG\Parameter(
     *          name="birth_date",
     *          description=" Date Of Birth of the User ",
     *          type="string",
     *          in="formData"
     *      ),
     *    @SWG\Parameter(
     *          name="gender",
     *          description=" Gender of the User ",
     *          type="string",
     *          in="formData"
     *      ),
     *    @SWG\Parameter(
     *          name="country_id",
     *          description=" Country of the User ",
     *          type="string",
     *          in="formData"
     *      ),
     *   @SWG\Parameter(
     *          name="city_id",
     *          description=" City of the User ",
     *          type="string",
     *          in="formData"
     *      ),
     *    @SWG\Parameter(
     *          name="address",
     *          description=" Address of the User ",
     *          type="string",
     *          in="formData"
     *      ),
     *
     *    @SWG\Parameter(
     *          name="profile_image",
     *          description="New Image of the User",
     *          type="file",
     *          in="formData"
     *      ),
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

    public function editProfile(Request $request)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());

        $update_validation = Validator::make($request->all(), $this->updateValidationRules);

        if ($update_validation->fails()) {
            $errors = $update_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }
        $input = $request->only(
          'name', 'password', 'mobile_number', 'email', 'birth_date', 'gender', 'country_id', 'city_id', 'verified', 'address'
        );
        if ($request->file('profile_image')) {
            $fileName = uploadFile($request->file('profile_image'), 'users/profile_image');
            $media = Media::create(['image' => $fileName]);
            $input['profile_image'] = $media->id;
        }
        if($user->is_blocked == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        $user->update($input);

        $token =  JWTAuth::fromUser($user);
        $user=$this->transformer->transform($user);

        return $this->respondAccepted(['token' => $token, "user" => $user]);
    }


    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/login/social",
     *      summary="Social Login User",
     *      tags={"Authentication"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of User en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *
     *        @SWG\Parameter(
     *          name="email",
     *          description=" Email of the User ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *       @SWG\Parameter(
     *          name="facebook_id",
     *          description=" Facebook ID of the User ",
     *          type="string",
     *          in="formData"
     *      ),
     *   @SWG\Parameter(
     *          name="google_id",
     *          description=" Google ID of the User ",
     *          type="string",
     *          in="formData"
     *      ),
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

    public function socialLogin(Request $request)
    {

        $login_validation = Validator::make($request->all(), [

            'email' => 'required|email|max:255',
            'facebook_id'=>'required_without_all:google_id',
            'google_id'=>'required_without_all:facebook_id',
            'mobile_number' => 'nullable|numeric|min:12',
            'profile_image' => 'nullable|mimes:jpeg,jpg,png',

        ]);

        if ($login_validation->fails()) {
            $errors = $login_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }

        $input = $request->only('email', 'facebook_id', 'google_id');
        $user=User::where('email',$request->input('email'))->first();

        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }

        if(!$user) {
            if ($request->input('email') && $request->input('facebook_id')) {
                $user = User::where('email', $request->input('email'))->where('facebook_id', $request->input('facebook_id'))->first();

                if(!$user) {
                    $input = $request->only('name', 'email', 'facebook_id', 'mobile_number');
                    if ($request->file('profile_image')) {
                        $fileName = uploadFile($request->file('profile_image'), 'users/profile_image');
                        $media = Media::create(['image' => $fileName]);
                        $input['profile_image'] = $media->id;
                    }
                    $input['forget_code'] = bin2hex(random_bytes('3'));
                    $input['ja_id'] = bin2hex(random_bytes('3'));
                    $user = User::create($input);
                    $token = JWTAuth::fromUser($user);
                    $user=$this->transformer->transform($user);
                    return $this->respondAccepted(['token' => $token, "user" => $user]);
                }
            }

            if ($request->input('email') && $request->input('google_id')) {
                $user = User::where('email', $request->input('email'))->where('google_id', $request->input('google_id'))->first();

                if(!$user) {
                    $input = $request->only('email', 'google_id', 'name', 'mobile_number');
                    if ($request->file('profile_image')) {
                        $fileName = uploadFile($request->file('profile_image'), 'users/profile_image');
                        $media = Media::create(['image' => $fileName]);
                        $input['profile_image'] = $media->id;
                    }
                    $input['forget_code'] = bin2hex(random_bytes('3'));
                    $input['ja_id'] = bin2hex(random_bytes('3'));
                    $user = User::create($input);
                }
            }
        }
        else
        {
            $user->update($request->all());
        }
        $token = JWTAuth::fromUser($user);
        $user=$this->transformer->transform($user);
        return $this->respondAccepted(['token' => $token, "user" => $user]);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/password/change",
     *      summary="Change Password",
     *      tags={"Authentication"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of User en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
     *        @SWG\Parameter(
     *          name="old_password",
     *          description="Old Password of the user",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *     @SWG\Parameter(
     *          name="new_password",
     *          description="New password of the user",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
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

    public function changePassword(Request $request, $lang)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());

        $change_password_validation = Validator::make($request->all(), [

            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
        ]);

        if ($change_password_validation->fails()) {
            $errors = $change_password_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }
        if($user->is_blocked == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        $input = Hash::check( $request->input('old_password'),$user->password);
        if (!$input) {
            return $this->respondNotAcceptable(['msg' => 'Password is invalid']);
        }
        $user->update(['password' => $request->input('new_password')]);
        $user->save();
        return $this->respondAccepted(['msg' => 'Password has been changed']);
    }

}
