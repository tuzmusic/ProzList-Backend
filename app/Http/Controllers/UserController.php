<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use App\User;

class UserController extends Controller {

    public function __construct(Request $request) {
        $r = \Route::current();
        if (!empty($r->getName())) {
            $a = explode(".", $r->getName());
            $this->controller = $a[0];
            $this->action = $a[1];
        }
        $this->request = $request;
    }

    public function index() {
        $this->title = "Customers";
        $arr = array('controller' => $this->controller, 'action' => $this->action, 'title' => $this->title);
        return view('user.index', $arr);
    }
    public function service_providers() {
        $this->title = "Service Providers";
        $arr = array('controller' =>'user', 'action' => 'service_providers', 'title' => $this->title);
        return view('user.service', $arr);
    }
    public function general_contractor() {
        $this->title = "General Contractor";
        $arr = array('controller' =>'user', 'action' => 'general_contractor', 'title' => $this->title);
        return view('user.general', $arr);
    }

    public function getall() {
        $data = User::where([["id", "!=", "1"]])->where('role','Customer')->orderby('id', 'desc');
        return Datatables::of($data->get())
                        ->addColumn('id', '<input class="innerallchk" onclick="chkmain();" type="checkbox" name="allchk[]" value="{{ $id }}">')
                        ->addColumn('name', function ($q) {
                            return $q->name;
                        })
                        ->addColumn('email', function ($q) {
                            return $q->email;
                        })
                        ->addColumn('role', function ($q) {
                            return $q->role;
                        })
                        ->addColumn('status', function ($q) {
                            return $q->status;
                        })
                        ->addColumn('action', '<a href="user/{{$id}}/edit" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a class="delsing cpforall" id="{{$id}}" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>')
                        ->rawColumns(['id', 'action'])
                        ->make(true);
    }
    public function getalls() {
        $data = User::where([["id", "!=", "1"]])->where('role','Service')->orderby('id', 'desc');
        return Datatables::of($data->get())
                        ->addColumn('id', '<input class="innerallchk" onclick="chkmain();" type="checkbox" name="allchk[]" value="{{ $id }}">')
                        ->addColumn('name', function ($q) {
                            return $q->name;
                        })
                        ->addColumn('email', function ($q) {
                            return $q->email;
                        })
                        ->addColumn('role', function ($q) {
                            return $q->role;
                        })
                        ->addColumn('status', function ($q) {
                            return $q->status;
                        })
                        ->addColumn('action', '<a href="user/{{$id}}/edit" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a class="delsing cpforall" id="{{$id}}" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>')
                        ->rawColumns(['id', 'action'])
                        ->make(true);
    }
    public function getallg() {
        $data = User::where([["id", "!=", "1"]])->where('role','General')->orderby('id', 'desc');
        return Datatables::of($data->get())
                        ->addColumn('id', '<input class="innerallchk" onclick="chkmain();" type="checkbox" name="allchk[]" value="{{ $id }}">')
                        ->addColumn('name', function ($q) {
                            return $q->name;
                        })
                        ->addColumn('email', function ($q) {
                            return $q->email;
                        })
                        ->addColumn('role', function ($q) {
                            return $q->role;
                        })
                        ->addColumn('status', function ($q) {
                            return $q->status;
                        })
                        ->addColumn('action', '<a href="user/{{$id}}/edit" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a class="delsing cpforall" id="{{$id}}" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>')
                        ->rawColumns(['id', 'action'])
                        ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $this->title = "Add New User";
        $arr = array('controller' => $this->controller, 'action' => $this->action, 'title' => $this->title);
        return view('user.add', $arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $input = $request->all();
        $rules = array(
            'name' => "required|max:100",
            'role' => "required",
        );
        $messages = [
            'name.required' => 'Please Enter Name.',
            'role.required' => 'Please Select User Role.'
        ];
        $msg = "Account created successfully.";
        if (isset($input["id_for_update"]) && !empty($input["id_for_update"])) {
            $msg = "Profile updated successfully.";
            $id = $input["id_for_update"];
            $user = User::find($id);
            if (!empty($user)) {
                $rules["email"] = 'required|email|unique:users,email,' . $id;
            }
            $rules["password"] = 'nullable|min:8';
        } else {
            $rules["password"] = 'required|min:8';
            $rules["email"] = 'required|email|unique:users';
            $user = new User();
        }
        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            $arr = array("status" => 400, "msg" => $validator->errors()->first(), "result" => array());
        } else {
            if (isset($input["password"])) {
                $password = \Hash::make($input["password"]);
                $input["password"] = $password;
            } else if (!isset($input["password"]) && empty($input["password"]) && !empty($user)) {
                $input["password"] = $user->password;
            }
            $input["status"] = 'Active';
            try {
                if (!empty($user)) {
                    $user->fill($input)->save();
                    $arr = array("status" => 200, "msg" => $msg, "data" => $user->toArray());
                } else {
                    $arr = array("status" => 400, "msg" => "User not found.", "data" => []);
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "msg" => $msg, "data" => []);
            }
        }
        return \Response::json($arr);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $data = User::find($id);
        if (!empty($data)) {
            $this->title = "Edit user";
            $arr = array('controller' => $this->controller, 'action' => $this->action, 'title' => $this->title, 'data' => $data);
            return view('user.add', $arr);
        } else {
            pagenotfound();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $request->request->add(['id_for_update' => $id]); //send id into store function
        return $this->store($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $input = $this->request;
        $id = explode(",", $id);
        if (in_array(1, $id)) {
            return \Response::json(array("status" => 400, "msg" => "You can't perform this action. Please provide valid id of user you want to delete.", "result" => array()));
        }
        if ($input->is('api/*')) {
            $input = get_api_key($input);
            $rules = array(
                'apikey' => "required|in:$this->apikey",
                'adminuserid' => "required|numeric|exists:users,id",
                'type' => "required",
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return \Response::json(array("status" => 400, "msg" => $validator->errors()->first(), "result" => array()));
            }
            $find_user = User::where(['id' => $input["adminuserid"], 'status' => "Active", 'type' => "Admin"])->first();
            if (empty($find_user)) {
                return \Response::json(array("status" => 404, "msg" => "You don't have access to perform this action.", "result" => array()));
            }
        }
        $data = User::whereIn('id', $id);
        if (count($data->get()) == 0) {
            return \Response::json(array("status" => 400, "msg" => "No users found.", "result" => array()));
        }
        try {
            $for_delete = $data->get();
            $result = aid($data, $input);
            $json = json_decode($result->getContent(), true);
            if ($json["status"] == 200) {
                if ($input["type"] == 'Delete') {
                    foreach ($for_delete as $u) :
                        if (isset($u->company->image) && !empty($u->company->image)) {
                            $s3 = \Storage::disk('s3');
                            $s3->delete($u->company->image);
                        }
                    endforeach;
                }
            }
            return $result;
        } catch (\Illuminate\Database\QueryException $ex) {
            $msg = $ex->getMessage();
            if (isset($ex->errorInfo[2])) {
                $msg = $ex->errorInfo[2];
            }
            $arr = array("status" => 400, "msg" => $msg, "result" => array());
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
            if (isset($ex->errorInfo[2])) {
                $msg = $ex->errorInfo[2];
            }
            $arr = array("status" => 400, "msg" => $msg, "result" => array());
        }
        return \Response::json($arr);
    }

}
