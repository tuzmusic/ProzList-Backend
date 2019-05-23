<?php
function img($path) {
    return public_path($path);
}
function replace_null_with_empty_string($array) {
    foreach ($array as $key => $value) 
    {
        if(is_array($value))
            $array[$key] = replace_null_with_empty_string($value);
        else {
            if (is_null($value))
                $array[$key] = "";
        }
    }
    return $array;
}
function get_api_key($request) {
    $input = $request->all();
    $headers = $request->headers->all();
    if(isset($headers["apikey"]) && count($headers["apikey"]) > 0) {
        $input["apikey"] = $headers["apikey"][0];
    }
    if(isset($headers["userid"]) && count($headers["userid"]) > 0) {
        $input["userid"] = $headers["userid"][0];
    }
    if(isset($headers["type"]) && count($headers["type"]) > 0) {
        $input["type"] = $headers["type"][0];
    }
    return $input;
}
function aid($obj,$input) {
    if(!empty($obj)) {
        try {
            if($input["type"] != 'Delete') {
                $m = "Data has been ".$input["type"];
                $obj->update(array('status'=>$input["type"]));
            } else {
                $m = "Data deleted successfully.";
                $obj->update(array('status'=>$input["type"]));
            }
            return \Response::json(array("status"=>200,"msg"=>$m,"result"=>array()));
        } catch ( \Illuminate\Database\QueryException $ex) {
            $msg = $ex->getMessage();
            if(isset($ex->errorInfo[2])) {
                $msg = $ex->errorInfo[2];
            }
            return \Response::json(array("status" => 400, "msg" =>$msg, "result" => array("cl"=>"alert alert-danger")));
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
            if(isset($ex->errorInfo[2])) {
                $msg = $ex->errorInfo[2];
            }
            return \Response::json(array("status" => 400, "msg" =>$msg, "result" => array("cl"=>"alert alert-danger")));
        }
    } else {
        return \Response::json(array("status"=>400,"msg"=>"No data found.","result"=>array()));
    }
}
function checktype($number) {
    if($number == 1 || $number == 'Connect & Yak') {
        return array("name"=>"Connect & Yak","extra"=>"","fields"=>array('categories'),'access'=>array('Member','Admin'),'select'=>array());
    }
    if($number == 2 || $number == 'Job Board') {
        return array("name"=>"Job Board","extra"=>"rate","fields"=>array('categories','location','rate','extra2'),'access'=>array('Member','Employer','Admin'),'select'=>array(
            'extra as rate',
            'extra2 as category',
            'location'
        ));
    }
    if($number == 3 || $number == 'Buy & Sell') {
        return array("name"=>"Buy & Sell","extra"=>"price","fields"=>array('price','location','categories'),'access'=>array('Member','Admin'),'select'=>array(
            'extra as price',
            'location'
        ));
    }
    if($number == 4 || $number == 'Rideshare') {
        return array("name"=>"Rideshare","extra"=>"rideshare_date","fields"=>array('rideshare_date','categories'),'access'=>array('Member','Admin'),'select'=>array(
            'extra as rideshare_date'
        ));
    }
    if($number == 5 || $number == 'Bozzii Media') {
        return array("name"=>"Bozzii Media","extra"=>"","fields"=>array(),'access'=>array('Member','Admin'),"select"=>array());
    }
    if($number == 6 || $number == 'Bozzii Jokes') {
        return array("name"=>"Bozzii Jokes","extra"=>"","fields"=>array(),'access'=>array('Member','Admin'),"select"=>array());
    }
    if($number == 7 || $number == 'Employer Review') {
        return array("name"=>"Employer Review","extra"=>"","fields"=>array('categories'),'access'=>array('Member','Admin'),"select"=>array());
    }
    if($number == 8 || $number == 'Advert') {
        return array("name"=>"Advert","extra"=>"","fields"=>array('datetodisplay','duration','categories'),'access'=>array('Admin'),'select'=>array(
            'datetodisplay',
            'expiry'
        ));
    }
}
function pagenotfound() {
    echo 404;
    exit;
}
function begin() {
    \DB::beginTransaction();
}
function commit() {
    \DB::commit();
}
function rollback() {
    \DB::rollBack();
}
function s3($file) {
    return "https://s3-ap-southeast-2.amazonaws.com/bozzii/".$file;
}
function view_date($d) {
    return date("d/m/Y",strtotime($d));
}
function RandomString()
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randstring = '';
	for ($i = 0; $i < 6; $i++) {
		$randstring = $randstring.$characters[rand(0,61)];
	}
	return $randstring;
}
?>