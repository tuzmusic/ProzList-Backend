<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;

//use App\Http\Controllers\CommonController;

use App\User;

use App\ServiceCategory;

use App\Images;

use App\ServiceRequest;

use App\UserService;

use App\Certificates;

use App\Subscription;

use App\UserServiceStatus;

use App\UserRating;

use App\Strikes;

use App\UserAwards;

use App\Awards;

use Config;

use Auth;

use DB;

use Illuminate\Support\Facades\Validator;

use Illuminate\Contracts\Auth\Authenticatable;

use App\Helper;

use Password;



// use Validator;



class APIV1Controller extends Controller {



    private $appkey = "";

    private $upload_path = "";

    private $upload_url = "";

    private $url = "";

    private $admin_url = "";

    private $common;

    private $apikey;

    protected $request;



    public function __construct(Request $request) {

        $this->upload_path = base_path('upload/');

        $this->apikey = Config::get('app.apikey');

        //  $this->upload_path = Config::get('app.upload_path');

        $this->upload_url = Config::get('app.upload_url');

        $this->url = Config::get('app.url');

        $this->admin_url = Config::get('app.admin_url');

//        $this->common = new CommonController();

        $this->request = $request;

    }



    // protected function registration(Request $request) {

    //     $status = "";

    //     $result = "";

    //     $input = get_api_key($this->request);



    //     $valid = Validator::make($input, [

    //         'apikey' => "required|in:$this->apikey",

    //         'name' => 'required|string|max:255',

    //         'email' => 'required|string|email|max:255|unique:users',

    //         'password' => 'required|string|min:8',

    //         'phone' => 'required|string|min:8',

    //         'role' => 'required|string|in:Customer,General,Service',

    //     ]);

    //     if ($valid->fails()) {

    //         $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => array());

    //     } else {

    //         if ($input['role'] == "Customer") {

    //             $status = "Active";

    //         } else {

    //             $status = "Pending";

    //         }

    //         $user = User::create([

    //             'name' => $input['name'],

    //             'email' => $input['email'],

    //             'password' => bcrypt($input['password']),

    //             'phone' => $input['phone'],

    //             'role' => $input['role'],

    //             'status' => $status,

    //         ]);

    //         if ($input['role'] == "Customer") {

    //             $result = array("response" => "true", "msg" => "Your account successfully created.", "data" => $user);

    //         } else {

    //             $result = array("response" => "true", "msg" => "Please wait while administrator activate your account.", "data" => $user);

    //         }

    //     }



    //     return response($result);

    // }

    protected function registration(Request $request) {

        $status = "";

        $result = "";

        $input = get_api_key($this->request);



        $validFields = [

            'apikey' => "required|in:$this->apikey",

            'name' => 'required|string|max:255',

            'email' => 'required|string|email|max:255|unique:users',

            'password' => 'required|string|min:8',

            'phone' => 'required|string|min:8',

            'role' => 'required|string|in:Customer,General,Service',

        ];

        



        if ($input['role'] == "Customer") {

            $status = "Active";

        } else {

            $status = "Pending";

        }

        $insertArr= [

                'name' => $input['name'],

                'email' => $input['email'],

                'password' => bcrypt($input['password']),

                'phone' => $input['phone'],

                'role' => $input['role'],

                'status' => $status,

                'latitude' => isset($input['latitude']) ? $input['latitude'] : '',

                'longitude' => isset($input['longitude']) ? $input['longitude'] : '',

            ];





        if($input['role'] == 'Service')

        {

            $validFields['country'] = 'required|string|max:255';

            $validFields['state'] = 'required|string|max:255';

            $validFields['service_json'] = 'required';

            $validFields['working_area_radius'] = 'required';

            $validFields['latitude'] = 'required';

            $validFields['longitude'] = 'required';





            

            $insertArr['address'] = $input['address'];

            $insertArr['licence_number'] = $input['licence_number'];

            $insertArr['licence_type'] = $input['licence_type'];

            $insertArr['country'] = $input['country'];

            $insertArr['state'] = $input['state'];

            $insertArr['social_security_number'] = $input['social_security_number'];

            $insertArr['tax_id'] = $input['tax_id'];

            $insertArr['working_area_radius'] = $input['working_area_radius'];





        }

        $valid = Validator::make($input,$validFields);

        if ($valid->fails()) {

            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => array());

        } else {

            

            $user = User::create($insertArr);

            

            if(isset($input['service_json']) && $input['service_json'] !='' && $input['role'] == 'Service')

            {

                $add_userService = json_decode($input['service_json']);



                if(!empty($add_userService))

                {

                    $user_id = $user['id'];

                    $serviceInsArr= array();

                    foreach ($add_userService as $key => $value) {

                        if($value->price > 0)

                        {

                            $value->user_id = $user_id;

                            $value->created_at = date('Y-m-d H:i:s');

                            $value->updated_at = date('Y-m-d H:i:s');

                            $serviceDetail = ServiceCategory::find($value->service_id);
                            if($serviceDetail)
                            {
                                $serviceReg =  UserService::create(json_decode(json_encode($value), true));
                                $serviceReg->name = $serviceDetail->name;
                                $serviceInsArr[] =$serviceReg;
                            }

                        }

                    }
                }

            }



            if(!empty($serviceInsArr))

                $user['service_json'] = $serviceInsArr;

                

            $user['subscribed'] = 'false';

            $user['certified'] = 'false';



            if ($input['role'] == "Customer") {

                $result = array("response" => "true", "msg" => "Your account successfully created.", "data" => $user);

            } else {

                $result = array("response" => "true", "msg" => "Please wait while administrator activate your account.", "data" => $user);

            }

        }



        return response($result);

    }



    protected function login(Request $request) {

        $result = "";

        $input = get_api_key($this->request);



        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

            'email' => 'required|string|email|exists:users|max:255',

            'password' => 'required|string|min:8',

        ]);

        if ($valid->fails()) {

            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => array());

        } else {

            $find = User::where('email', $input["email"])->first();

            if (!empty($find)) {

                $pass = $input["password"];

                if (\Hash::check($pass, $find->password)) {

                    if ($find->status == 'Active') {

                        if ($find->role == 'Customer') {

                            $result = array("response" => "true", "msg" => "Customer success login", "data" => $this->removeNull($find->toArray()));

                        } else if ($find->role == 'General') {

                            $result = array("response" => "true", "msg" => "General Contractor success login", "data" => $this->removeNull($find->toArray()));

                        } else if ($find->role == 'Service') {



                            $usrArr= $find->toArray();

                            $subscribed = Subscription::where('user_id',$find->id)->first();



                            if($subscribed && !empty($subscribed))

                                $usrArr['subscribed'] = 'true';

                            else

                                $usrArr['subscribed'] = 'false';



                            $Certified = Certificates::where('user_id',$find->id)->first();



                            if($Certified && !empty($Certified))

                                $usrArr['certified'] = 'true';

                            else

                                $usrArr['certified'] = 'false';

                            

                            $result = array("response" => "true", "msg" => "Service Provider success login", "data" => $this->removeNull($usrArr));

                        }

                    } else if ($find->status == 'Inactive') {

                        $result = array("response" => "false", "msg" => "Your account is inactive. Please contact administrator.", "data" => []);

                    } else if ($find->status == 'Pending') {



                        $usrArr= $find->toArray();

                        $sendBlank = 0;

                        if ($find->role == 'Service') {



                            $subscribed = Subscription::where('user_id',$find->id)->first();



                            if($subscribed && !empty($subscribed))

                                $usrArr['subscribed'] = 'true';

                            else

                                $usrArr['subscribed'] = 'false';



                            $Certified = Certificates::where('user_id',$find->id)->first();



                            if($Certified && !empty($Certified))

                                $usrArr['certified'] = 'true';

                            else

                                $usrArr['certified'] = 'false';

                            

                            if($subscribed && !empty($subscribed) && $Certified && !empty($Certified))

                                $sendBlank = 1;

                        }

                        

                        if($sendBlank == 0)

                            $result = array("response" => "true", "msg" => "Service Provider login", "data" => $this->removeNull($usrArr));

                        else

                            $result = array("response" => "true", "msg" => "Your request in pending. Please wait while administrator activate your account.", "data" => $this->removeNull($usrArr));

                    } else {

                        $result = array("response" => "false", "msg" => "This account does not exists.", "data" => []);

                    }

                } else {

                    $result = array("response" => "false", "msg" => "Please provide valid password.", "data" => []);

                }

            } else {

                $result = array("response" => "false", "msg" => "Email doesn't exist in our database.", "data" => []);

            }

        }



        return response($result);

    }



    public function forgot_password(Request $request) {

        $input = $request->all();

        $rules = array(

            'email' => "required|email",

        );

        $valid = Validator::make($input, $rules);

        if ($valid->fails()) {

            $result = array("response" => "false", "msg" => $valid->errors()->first(), "result" => []);

        } else {

            try {

                $response = Password::sendResetLink($request->only('email'), function (Message $message) {

                    $message->subject($this->getEmailSubject());

                });

                switch ($response) {

                    case Password::INVALID_USER:

                    $result = array("response" => "false", "msg" => trans($response), "result" => []);

                    break;

                    case Password::RESET_LINK_SENT:

                    $result = array("response" => "true", "msg" => trans($response), "result" => []);

                    break;

                }

            } catch (\Swift_TransportException $ex) {

                $result = array("response" => "false", "msg" => $ex->getMessage(), "result" => []);

            } catch (Exception $ex) {

                $result = array("response" => "false", "msg" => $ex->getMessage(), "result" => []);

            }

        }

        return response($result);

    }

    public function removeNull($input)

    {

       foreach ($input as $key => $value) {

            if(is_array($value) && !empty($value))

            {

                $input[$key] = $this->removeNull($value);

            }

            elseif(is_null($value)) {

                 $input[$key] = "";

            }

        }

        return $input;

    }

    public function get_profile($id) {

        $result = "";

        $input = get_api_key($this->request);

        $input['id'] = $id;

        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

        ]);



        if ($valid->fails()) {

            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => []);

        } else {

            $value = User::with('Certificate_image')->find($id);

            if (count($value) != '' || count($value) != '0') {

                //$result = array("response" => "true", "msg" => 'User profile', "data" => $value);

                if ($value->status == "Inactive") {

                    $result = array("response" => "false", "msg" => 'Your account is inactive. Please contact administrator.', "data" => []);

                } else if ($value->status == 'Pending') {

                    $result = array("response" => "false", "msg" => "Your request in pending. Please wait while administrator activate your account.", "data" => []);

                } else if ($value->status == "Delete") {

                    $result = array("response" => "false", "msg" => 'Your account not exists', "data" => []);

                } else {



                    if($value['role'] == 'Service')

                    {

                       $serviceCat = DB::table('user_service')

                        ->leftJoin('service_category', 'user_service.service_id', '=', 'service_category.id')

                        ->where('user_service.user_id', '=', $id)

                        ->select('user_service.id  as user_service_id','user_service.service_id','user_service.price','user_service.discount','service_category.name','service_category.image','service_category.status')

                        ->get();

                        $serviceCat = $this->removeNull($serviceCat->toArray());

                        $value['service_json'] = $serviceCat;

                    }

                    $proDetail = $this->removeNull($value->toArray());

                    $result = array("response" => "true", "msg" => 'Get Profile', "data" =>$proDetail);

                }

            } else {

                $result = array("response" => "false", "msg" => 'User not found', "data" => []);

            }

        }

        return response($result);

    }



    public function get_service_category() {

        $result = "";

        $input = get_api_key($this->request);

        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

        ]);

        if ($valid->fails()) {

            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => []);

        } else {

            $value = ServiceCategory::where("Status", "Active")->get();

            if (count($value) != '' || count($value) != '0') {

                $result = array("response" => "true", "msg" => 'Get Service Category', "data" => $value);

            } else {

                $result = array("response" => "false", "msg" => 'Service Category not found', "data" => []);

            }

        }

        return response($result);

    }



    public function update_profile(Request $request, $id) {

        $result = "";

        $input = get_api_key($this->request);

        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

        ]);

        if ($valid->fails()) {

            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => array());

        } else {

            $data = User::find($id);



            if ( $data && ($data->role == "Customer" || $data->role=='Service') && $data->status == "Active") {



                $insertArr= [

                    'name' => $input['name'],

                    'email' => $input['email'],

                    'city' => isset($input['city']) ? $input['city'] : '',

                    'phone' => $input['phone'],

                ];

                 $validFields = [

                    'name' => 'required|string|max:255',

                    'email' => 'required|string|email|max:255|unique:users,email,' . $id,

                    //'city' => 'required|string',

                    'phone' => 'required|string|min:8',

                    'profile_pic' => 'required'

                ];



                if( $data->role == 'Service')

                {

                    $validFields['service_json'] = 'required';

                    $validFields['working_area_radius'] = 'required';

                    

                    if(isset($input['address']))

                        $insertArr['address'] = $input['address'];

                    if(isset($input['licence_number']))

                        $insertArr['licence_number'] = $input['licence_number'];

                    if(isset($input['licence_type']))

                        $insertArr['licence_type'] = $input['licence_type'];

                    if(isset($input['social_security_number']))

                        $insertArr['social_security_number'] = $input['social_security_number'];

                    if(isset($input['tax_id']))

                        $insertArr['tax_id'] = $input['tax_id'];

                    if(isset($input['working_area_radius']))

                        $insertArr['working_area_radius'] = $input['working_area_radius'];



                    if(isset($input['latitude']) && trim($input['latitude']) != '')

                        $insertArr['latitude'] = $input['latitude'];

                    if(isset($input['longitude']) && trim($input['longitude']) != '')

                        $insertArr['longitude'] = $input['longitude'];

                }

                $valid = Validator::make($input,$validFields);

                if ($valid->fails()) {

                    $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => array());

                } else {

                   

                    $user = User::findOrFail($id);

                    if ($request->file('profile_pic')) {

                        $image = $request->file('profile_pic');

                        $imagename = str_replace(' ','',trim($image->getClientOriginalName()));

                        $imagename = time().'__'.$imagename;

                        $insertArr['profile_pic'] = 'customer/' .$imagename;

                        $destinationPath = public_path('upload/customer');

                        $image->move($destinationPath, $imagename);

                    }

                    $user->update($insertArr);

                    $returnVal = User::with('Certificate_image')->find($id)->toArray();

                    

                    if($data->role == 'Service' && isset($input['service_json']) && $input['service_json'] !='')

                    {

                        $add_userService = json_decode($input['service_json']);



                        if(!empty($add_userService))

                        {

                            $user_id = $id;

                            $serviceInsArr= array();

                            UserService::where('user_id', $user_id)->delete();

                            foreach ($add_userService as $key => $value) {

                                if($value->price > 0)

                                {

                                    $value->user_id = $user_id;

                                    $value->created_at = date('Y-m-d H:i:s');

                                    $value->updated_at = date('Y-m-d H:i:s');

                                    $serviceInsArr[] = UserService::create(json_decode(json_encode($value), true));    

                                }

                            }

                            /*if(!empty($serviceInsArr))

                                $returnVal['service_json'] = $serviceInsArr;*/

                        }

                    }

                    $returnVal['service_json'] = [];

                    if($data->role == 'Service')

                    {

                       $serviceCat = DB::table('user_service')

                        ->leftJoin('service_category', 'user_service.service_id', '=', 'service_category.id')

                        ->where('user_service.user_id', '=', $id)

                        ->select('user_service.id  as user_service_id','user_service.service_id','user_service.price','user_service.discount','service_category.name','service_category.image','service_category.status')

                        ->get();

                        $serviceCat = $this->removeNull($serviceCat->toArray());

                        $returnVal['service_json'] = $serviceCat;

                    }

                    





                    $result = array("response" => "true", "msg" => 'Profile updated', "data" => $this->removeNull($returnVal));

                }

            } else {

                $result = array("response" => "false", "msg" => 'Profile not found', "data" => []);

            }

        }

        return response($result);

    }

    public function create_request(Request $request){

        $result = "";

        $input = get_api_key($this->request);

        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

            'user_id' => "required",

            'cat_id' => "required",

            'address' => "required",

            'lat' => "required",

            'lng' => "required",

           // 'image' => "required",

            'request_desc' => "required",

           // 'status' => "required"

        ]);

        if ($valid->fails()) {

            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => array());

        } else {

            $ches = ServiceCategory::find($input['cat_id']);

            $cheu = user::find($input['user_id']);

            if($cheu !='' && !empty($cheu)){

                if($ches !='' && !empty($ches)){

                    $service = ServiceRequest::create([

                        'user_id' => $input['user_id'],

                        'cat_id' =>  $input['cat_id'],

                        'request_desc' => $input['request_desc'],

                        'status' => "Pending",

                        'address' => $input['address'],

                        'lat' => $input['lat'],

                        'lng' => $input['lng'],



                    ]);

                    $id = $service["id"];

                    $imagename = array();

                    $service1 = new Images;

                    if($request->file('images')!= '' && !empty($request->file('images'))){

                        if ($files=$request->file('images')) {

                            foreach($files as $file){

                                $imagename = $file->getClientOriginalName();

                                $service1['image'] = 'service_request/' .$imagename;

                                $destinationPath = public_path('upload/service_request');

                                $file->move($destinationPath, $imagename);

                            }

                        }

                        foreach($request->file('images') as $file){

                         $imagename = $file->getClientOriginalName();

                         $images = Images::create([

                            'image' => 'service_request/' .$imagename,

                            'width' => 1,

                            'height' => 1,

                            'image_id' => $id,

                            'image_type' => "service_request"

                        ]);



                         $service['image'] = array();

                         $service['image'] = $images;

                     }}

         // foreach($images as $file){

         //     print_r($images);

         // }die();

            // print_r($service1['image']);die();

            // //foreach($imagename as $image){

            // $service1['width'] = 1;

            // $service1['height'] = 1;

            // $service1['image_id'] = $id;

            // $service1['image_type'] = 'service_request';

            // $service1->save();

            // //}

                       // $result = array("response" => "true", "msg" => "Service request successfully created", "data" => $service);

                     $result = array("response" => "true", "msg" => "Service request successfully created", "data" => []);

                 }else{

                    $result = array("response" => "false", "msg" => "Category id does't exists", "data" => array());

                }

            }else{

                $result = array("response" => "false", "msg" => "User id does't exists", "data" => array());

            }

        }

        return response($result);

    }

    

    public function get_service_request($id,$type){

        //echo "I'm in Die";die();

        $result = "";

        $input = get_api_key($this->request);

        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

        ]);

        if ($valid->fails()) {



            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => []);

        } else {

            $value = User::find($id);
			$us = User::find($id);

            if (count($value) != '' || count($value) != '0') {

                if($type != '' || $type != '0'){

                    if($type == 1){

                        //$request = ServiceRequest::get()->where('user_id', $id)->where('status','Pending');

                        $request = ServiceRequest::with('Service_request_image','Service_category_name','Service_provider_detail')->where('user_id', $id)->whereIn('status',['Pending','Accepted'])->orderBy('id', 'desc')->get();
                        $returnData = $request->toArray();
                        if($returnData && !empty($returnData))
                        {
                            foreach ($returnData as $key => $value) {
                                if($value['service_provider_id'] > 0)
                                {
									//$returnData[$key]['avg_rating'] = UserRating::where('service_provider_id','=',$value['service_provider_id'])->avg('rating');
                                   $returnData[$key]['service_provider_detail']['avg_rating'] = UserRating::where('service_provider_id','=',$value['service_provider_id'])->avg('rating');
								   
                                }
                                else
                                {
                                   unset($returnData[$key]['service_provider_detail']);
                                }
                            }
                        }
                        $returnData = $this->removeNull($returnData);
                        $result = array("response" => "true", "msg" => "Service requests", "data" =>$returnData);

                    }else if($type == 2){

                        $request = ServiceRequest::with('Service_request_image','Service_category_name','Service_provider_detail')->where('user_id', $id)->whereNotIn('status',['Pending','Accepted','Deleted'])->orderBy('id', 'desc')->get();

                        $returnData = $request->toArray();
                        if($returnData && !empty($returnData))
                        {
                            foreach ($returnData as $key => $value) {
                                if($value['service_provider_id'] > 0)
                                {
									//$returnData[$key]['avg_rating'] = UserRating::where('service_provider_id','=',$value['service_provider_id'])->avg('rating');
                                   $returnData[$key]['service_provider_detail']['avg_rating'] = UserRating::where('service_provider_id','=',$value['service_provider_id'])->avg('rating');
                                }
                                else
                                {
                                   $returnData[$key]['service_provider_detail'] = '';
                                }
                            }
							foreach ($returnData as $key => $value) {
									
								  if($us['role']=='Customer'){
												$chkuser = UserRating::where('customer_id', '=', $id)->where('service_provider_id','=',$value['service_provider_id'])->where('service_request_id','=',$value['id'])->get();
												$returnData[$key]['rating']=$chkuser;
										  }
								 if($us['role']=='Service'){
									$chkuser = UserRating::where('service_provider_id', '=', $id)->where('customer_id','=',$value['customer_id'])->where('service_request_id','=',$value['id'])->get();
												$returnData[$key]['rating']=$chkuser;
											   
								}
							}
							
                        }
                        $returnData = $this->removeNull($returnData);
                        $result = array("response" => "true", "msg" => "Service requests", "data" =>$returnData);
                        

                    }else{

                        $result = array("response" => "false", "msg" => 'Type not define!', "data" => []);    

                    }

                }else {

                    $result = array("response" => "false", "msg" => 'Type not define!', "data" => []);

                }

            } else {

                $result = array("response" => "false", "msg" => 'User not found!', "data" => []);

            }   

        }





        return response($result);

    }



    public function upload_certificate(Request $request){

        $result = "";

        $input = get_api_key($this->request);

        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

            'user_id' => "required",

            'images'=> "required",

            'images.*' => 'image|mimetypes:image/jpeg,image/jpg,image/png'  

        ]);

        if ($valid->fails()) {

            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => array());

        } else {



            $checkUser = user::find($input['user_id']);



            if($checkUser !='' && !empty($checkUser)){

                

                    $certificates =  array();

                    

                    if($request->file('images')!= '' && !empty($request->file('images'))){

                        if ($files=$request->file('images')) {

                            foreach($files as $file){

                                $imagename = str_replace(' ','',trim($file->getClientOriginalName()));

                                $imagename = time().'__'.$imagename;

                                $destinationPath = public_path('upload/certificates');

                                $file->move($destinationPath, $imagename);



                                $images = Certificates::create([

                                    'image' => 'certificates/' .$imagename,

                                    'width' => 1,

                                    'height' => 1,

                                    'user_id' => $checkUser['id'],

                                    'created_at' => date('Y-m-d H:i:s'),

                                ]);

                                $certificates['certificates'][] = $images;

                            }

                        }

                        

                    }

                        // $result = array("response" => "true", "msg" => "Service request successfully created", "data" => $service);

                     $result = array("response" => "true", "msg" => "Certificated uploaded successfully", "data" => $certificates);

            }

            else{

                $result = array("response" => "false", "msg" => "User id does't exists", "data" => array());

            }

        }

        return response($result);

    }



    public function subscription_save(Request $request){

        $result = "";

        $input = get_api_key($this->request);

        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

            'user_id' => "required",

            'total_amount'=> "required",

            'transaction_id' => 'required'

        ]);

        if ($valid->fails()) {

            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => array());

        } else {



            $checkUser = user::find($input['user_id']);



            if($checkUser !='' && !empty($checkUser)){

                    

                    $tran = Subscription::create([

                        'user_id' => $input['user_id'],

                        'total_amount' => $input['total_amount'],

                        'transaction_id' => $input['transaction_id'],

                        'created_at' => date('Y-m-d H:i:s'),

                    ]);



                        

                        // $result = array("response" => "true", "msg" => "Service request successfully created", "data" => $service);

                     $result = array("response" => "true", "msg" => "Transaction saved successfully", "data" => []);

            }

            else{

                $result = array("response" => "false", "msg" => "User id does't exists", "data" => array());

            }

        }

        return response($result);

    }



    public function get_nearby_job(){

        //echo "I'm in Die";die();

        $result = "";

        $input = get_api_key($this->request);

        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

        ]);

        if ($valid->fails()) {
             $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => []);

        } else {

        	$user_id = $_POST['user_id'];

        	$latitude = $_POST['latitude'];

        	$longitude = $_POST['longitude'];



            $value = User::where([['role', '=','Service'],['id', '=', $user_id]])->first();

            

            if (count($value) != '' || count($value) != '0') {

                //$result = array("response" => "true", "msg" => 'User profile', "data" => $value);

                if ($value->status == "Inactive") {

                    $result = array("response" => "false", "msg" => 'Your account is inactive. Please contact administrator.', "data" => []);

                } else if ($value->status == 'Pending') {

                    $result = array("response" => "false", "msg" => "Your request in pending. Please wait while administrator activate your account.", "data" => []);

                } else if ($value->status == "Delete") {

                    $result = array("response" => "false", "msg" => 'Your account not exists', "data" => []);

                }

                else {



                     $locationQuery =' ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( service_request.lat ) ) * cos( radians( service_request.lng ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin(radians(service_request.lat)) ) ) AS distance  ';

                       // $facilityWhere .= ' HAVING distance < '.$distance;





                    $jobs =ServiceRequest::with('Service_customer_detail','Service_request_image')->leftJoin('user_service', 'user_service.service_id', '=', 'service_request.cat_id')

                        ->leftJoin('service_category', 'service_category.id', '=', 'service_request.cat_id')

                        ->leftJoin('users', 'users.id', '=', 'user_service.user_id')

                        ->leftJoin('user_service_status', 'user_service_status.request_id', '=', 'service_request.id')

                        ->where([['users.id', '=', $user_id],['service_request.status', '=', 'Pending'],[DB::raw('user_service_status.status')]])

                        ->select('service_request.*','users.working_area_radius','service_category.name','service_category.image','users.working_area_radius',DB::raw($locationQuery))

                        ->groupBy('distance',[DB::raw('service_request.id')])

                        ->havingRaw('distance <= working_area_radius')

                        ->get();

                        /*echo $jobs;

                        exit;*/

                   

                    $jobList = $this->removeNull($jobs->toArray());

                    $result = array("response" => "true", "msg" => 'Job list', "data" =>$jobList);

                }

            } else {

                $result = array("response" => "false", "msg" => 'User not found', "data" => []);

            } 

        }

        return response($result);

    }



    public function save_request_status(Request $request){

        $result = "";

        $input = get_api_key($this->request);

        $serviceStatusArr = ['Accepted','Completed','Not Completed','Rejected','Deleted'];
        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

            'user_id' => "required",

            'request_id'=> "required",

            'status' => 'required|in:'.implode(',', $serviceStatusArr),

        ]);

        if ($valid->fails()) {

            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => array());

        } else {

            $checkUser = user::find($input['user_id']);



            if($checkUser !='' && !empty($checkUser)){

                    

                    if($input['status'] != 'Completed')
                    {

                        $alreadySaved = UserServiceStatus::where([['user_id', '=',$input['user_id']],['request_id', '=', $input['request_id']]])->get();

                        

                        if(empty($alreadySaved->toArray()))

                        {

                            $tran = UserServiceStatus::create([

                                'user_id' => $input['user_id'],

                                'request_id' => $input['request_id'],

                                'status' => $input['status'],

                                'sp_distance' =>isset($input['distance']) ? $input['distance'] : '',

                                'created_at' => date('Y-m-d H:i:s'),

                                'updated_at' => date('Y-m-d H:i:s'),

                            ]);        

                        }
						 

                    }
					//if completed
					if($input['status'] == 'Completed')
					{
						
							if($checkUser['role'] =='Service'){
									$stat = UserServiceStatus::where('request_id','=',$input['request_id'])->first();
									if($stat['SPStatus'] == 'Completed'){
										$result = array("response" => "false", "msg" => "Request Already Completed Wait For Customer to Complete", "data" => []);
										return $result;
										exit;
									}
								$updateStatus = UserServiceStatus::where([['user_id', '=',$input['user_id']],['request_id', '=', $input['request_id']]]);
								$upArr = array('SPStatus'=>$input['status']);
								$updateStatus->update($upArr);
							}
							if($checkUser['role'] =='Customer'){
									$stat = UserServiceStatus::where('request_id','=',$input['request_id'])->first();
									if($stat['CustStatus'] == 'Completed'){
										$result = array("response" => "false", "msg" => "Request Already Completed", "data" => []);
										return $result;
										exit;
									}
								$updateStatus = UserServiceStatus::where([['request_id', '=', $input['request_id']]]);
								$upArr = array('CustStatus'=>$input['status']);
								$updateStatus->update($upArr);
								
								//update in service request
							$serviceUpdate = ServiceRequest::find($input['request_id']);

							$serviceUpdate->update(array('status'=>$input['status'],'service_provider_id'=>$serviceUpdate['service_provider_id']));

							$updateStatus = UserServiceStatus::where([['request_id', '=', $input['request_id']]]);

							$upArr = array('status'=>$input['status']);

							if(isset($input['distance']) && $input['distance'] != '')
								$upArr['sp_distance'] = $input['distance'];

							$updateStatus->update($upArr);
							}
						
					}

                    if($input['status'] == 'Accepted')
                    {

                        $serviceUpdate = ServiceRequest::find($input['request_id']);

                        $serviceUpdate->update(array('status'=>$input['status'],'service_provider_id'=>$input['user_id']));

                        $updateStatus = UserServiceStatus::where([['user_id', '=',$input['user_id']],['request_id', '=', $input['request_id']]]);

                        
                        $upArr = array('status'=>$input['status']);

                        if(isset($input['distance']) && $input['distance'] != '')
                            $upArr['sp_distance'] = $input['distance'];

                        $updateStatus->update($upArr);

                    }    

                    $result = array("response" => "true", "msg" => "Status saved successfully", "data" => []);

            }

            else{

                $result = array("response" => "false", "msg" => "User id does't exists", "data" => array());

            }

        }

        return response($result);

    }



     public function get_current_request($user_id,$type){

        //echo "I'm in Die";die();



        //$type= 1  = accepted , 2 = != accepted

        $result = "";

        $input = get_api_key($this->request);

        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

        ]);

        if ($valid->fails()) {



            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => []);

        } else {

            $value = User::find($user_id);
			$us = User::find($user_id);

            if (count($value) != '' || count($value) != '0') {

                

                $currentService =ServiceRequest::with('Service_customer_detail','Service_request_image','Service_category_name')

                        ->leftJoin('user_service_status', 'user_service_status.request_id', '=', 'service_request.id');
						

                if($type == 1)
                {

                    $currentService->where([['user_service_status.user_id', '=', $user_id],['user_service_status.status', '=', 'Accepted']]);

                }

                if($type == 2)

                {

                    $currentService->where([['user_service_status.user_id', '=', $user_id]]);
                    $currentService->whereIn('user_service_status.status',['Completed','Not Completed']);

                    //$currentService->orWhere([['service_request.service_provider_id', '=', $user_id],['service_request.status', '=', 'Completed']]);

                }

                $currentService = $currentService->groupBy('service_request.id')

                        ->select('service_request.*','user_service_status.status as user_service_status','user_service_status.sp_distance')
                        ->orderByRaw('service_request.created_at DESC')
                        ->get();
				
						$returnData = $currentService->toArray();
                        if($returnData && !empty($returnData))
                        {
                            foreach ($returnData as $key => $value) {
                                if($value['service_provider_id'] > 0)
                                {
									//$returnData[$key]['avg_rating'] = UserRating::where('service_provider_id','=',$value['service_provider_id'])->avg('rating');
                                   $returnData[$key]['service_customer_detail']['avg_rating'] = UserRating::where('customer_id','=',$value['user_id'])->avg('rating');
								   
                                }
                                else
                                {
                                   unset($returnData[$key]['service_provider_detail']);
                                }
                            }
                        }
						foreach ($returnData as $key => $value) {
									
								  if($us['role']=='Customer'){
												$chkuser = UserRating::where('customer_id', '=', $user_id)->where('service_request_id','=',$value['id'])->where('review_from','=','Service')->get();
												$returnData[$key]['rating']=$chkuser;
										  }
								 if($us['role']=='Service'){
									$chkuser = UserRating::where('service_provider_id', '=', $value['service_provider_id'])->where('customer_id','=',$value['user_id'])->where('service_request_id','=',$value['id'])->where('review_from','=','Customer')->get();
												$returnData[$key]['rating']=$chkuser;
											   
								}
							}
						
                $result = array("response" => "true", "msg" => "Current Service requests", "data" => $this->removeNull($returnData));



            } else {



                $result = array("response" => "false", "msg" => 'User not found!', "data" => []);



            }   

        }





        return response($result);

    }



    public function give_rating(Request $request){

        $result = "";

        $input = get_api_key($this->request);

        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

            'customer_id' => "required",

            'service_request_id'=> "required",

            'service_provider_id' => 'required',

            'rating' => 'required',

            'review' => 'required',

            'review_from'=> 'required',

        ]);

        if ($valid->fails()) {

            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => array());

        } else {
				if($input['review_from'] == 'Service'){
					$find = UserRating::where([['customer_id', $input["customer_id"]],['service_request_id', $input["service_request_id"]],['service_provider_id', $input["service_provider_id"]],['review_from', $input["review_from"]]])->first();

					if(!$find)

					{



						$tran = UserRating::create([

							'customer_id' => $input['customer_id'],

							'service_request_id'=> $input['service_request_id'],

							'service_provider_id' => $input['service_provider_id'],

							'rating' => $input['rating'],

							'review' => $input['review'],

							'review_from'=>$input['review_from'],

							'created_at' => date('Y-m-d H:i:s'),

						]);

						/*$serviceUpdate = ServiceRequest::find($input['service_request_id']);

						$serviceUpdate->update(array('status'=>'Completed'));*/





						$result = array("response" => "true", "msg" => "Rating/Review saved successfully", "data" =>$tran);

					}

					else

					{

						$result = array("response" => "false", "msg" => "You have aleady submitted rating/review for this service", "data" =>[]);   

					}

            }
			if($input['review_from'] == 'Customer'){
					$find = UserRating::where([['customer_id', $input["customer_id"]],['service_request_id', $input["service_request_id"]],['service_provider_id', $input["service_provider_id"]],['review_from', $input["review_from"]]])->first();

					if(!$find)

					{



						$tran = UserRating::create([

							'customer_id' => $input['customer_id'],

							'service_request_id'=> $input['service_request_id'],

							'service_provider_id' => $input['service_provider_id'],

							'rating' => $input['rating'],

							'review' => $input['review'],

							'review_from'=>$input['review_from'],

							'created_at' => date('Y-m-d H:i:s'),

						]);

						/*$serviceUpdate = ServiceRequest::find($input['service_request_id']);

						$serviceUpdate->update(array('status'=>'Completed'));*/





						$result = array("response" => "true", "msg" => "Rating/Review saved successfully", "data" =>$tran);

					}

					else

					{

						$result = array("response" => "false", "msg" => "You have aleady submitted rating/review for this service", "data" =>[]);   

					}

            }



            

        }

        return response($result);

    }



    public function get_my_review($user_id,$role){

        //echo "I'm in Die";die();

        $result = "";

        $input = get_api_key($this->request);

        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

        ]);

        if ($valid->fails()) {



            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => []);

        } else {

            $value = User::where([['role', '=',$role],['id', '=', $user_id]])->first();

            

            if (count($value) != '' || count($value) != '0') {

                if($role == 'Service')

                {

                    $review =UserRating::with('Service_provider_detail','Customer_detail')->where([['service_provider_id', '=', $user_id]])

                    ->get();

                }

                else

                {   

                    $review =UserRating::with('Service_provider_detail','Customer_detail')->where([['customer_id', '=', $user_id]])

                    ->get();



                }



                $reviewList = $this->removeNull($review->toArray());

                $result = array("response" => "true", "msg" => 'Review list', "data" =>$reviewList);

                

            } else {

                $result = array("response" => "false", "msg" => 'User not found', "data" => []);

            } 

        }

        return response($result);

    }

    public function get_strike($user_id){

        //echo "I'm in Die";die();

        $result = "";

        $input = get_api_key($this->request);

        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

        ]);

        if ($valid->fails()) {



            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => []);

        } else {

            $value = User::where([['id', '=', $user_id]])->first();

            

            if (count($value) != '' || count($value) != '0') {

                $strikes =Strikes::where([['user_id', '=', $user_id]])->get();

                $strikeList = $this->removeNull($strikes->toArray());

                $result = array("response" => "true", "msg" => 'Strike list', "data" =>$strikeList);

                

            } else {

                $result = array("response" => "false", "msg" => 'User not found', "data" => []);

            } 

        }

        return response($result);

    }

    public function get_award($user_id){

        //echo "I'm in Die";die();

        $result = "";

        $input = get_api_key($this->request);

        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

        ]);

        if ($valid->fails()) {



            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => []);

        } else {

            $value = User::where([['id', '=', $user_id]])->first();

            

            if (count($value) != '' || count($value) != '0') {

                $awardList =UserAwards::with('Award_detail')->where([['user_id', '=', $user_id]])->get();

                $awardList = $this->removeNull($awardList->toArray());

                $result = array("response" => "true", "msg" => 'Award list', "data" =>$awardList);

                

            } else {

                $result = array("response" => "false", "msg" => 'User not found', "data" => []);

            } 

        }

        return response($result);

    }
	public function updatelocation(Request $request){
		$req = $request->all();
		$result = "";
	
        $input = get_api_key($this->request);

        $valid = Validator::make($input, [

            'apikey' => "required|in:$this->apikey",

            'user_id' => "required",

            'latitude'=> "required",

            'longtitude' => 'required',


        ]);
		
		 if ($valid->fails()) {

            $result = array("response" => "false", "msg" => $valid->errors()->first(), "data" => []);
			
        }
		else{
			
			$user = User::where([['id', '=', $input['user_id']]])->first();
			if(!empty($user)){
				if(isset($input['request_id'])){
					if($user->update(array('latitude'=>$input['latitude'],'longitude'=>$input['longtitude'])))
					{
							$requestid = User::where([['id', '=', $input['request_id']]])->first();
							$result = array("response" => "true", "msg" => "location updated", "data" => $requestid);
					}
				}
				else{
					$user->update(array('latitude'=>$input['latitude'],'longitude'=>$input['longtitude']));
					$result = array("response" => "true", "msg" => "location updated");
				}
				
			}
			else{
				$result = array("response" => "true", "msg" => "User Not Found", "data" => []);
			}
			
			
		}

		return $result;
	}

}

