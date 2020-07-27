<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\DateTimeAPI;
use App\Models\Article;
use App\ActivityRecords;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Route;

class AuthController extends Controller
{
    public function search_user(Request $request) {
        
        if ($request->email != "") {
            $email = $request->email;
            $search_email_query = "SELECT company_id, ACNo, name, email, created_at, deleted FROM `users` WHERE email = '".$email."'";
            $search_email = DB::connection('mysql')->select($search_email_query);
            
            if(!empty($search_email)){
                return $search_email;
            }else{
                return response(['status'=>'error','message'=>'No user found!']);
            }
            
        } else if( $request->companyID != "" ) {
            $company_id = $request->companyID;
            $search_company_id_query = "SELECT company_id, ACNo, name, email, created_at, deleted FROM `users` WHERE company_id = '".$company_id."'";
            $search_company_id = DB::connection('mysql')->select($search_company_id_query);
            
            if ( !empty($search_company_id) ) {
                return $search_company_id;
            } else {
                return response(['status'=>'error','message'=>'No user found!']);
            }
        }
        else
        {
            return response(['status'=>'error','message'=>'Search for companyID/email!']);
        }
    }

    public function show_dtr_list(Request $request) {
        $validatedData = $request->validate([
            'start_date'=>'required',
            'end_date'=>'required'
        ]);

        $start_date = date("Y-m-d",strtotime($request->start_date));
        $end_date = date("Y-m-d",strtotime($request->end_date));

        $actRecords = new ActivityRecords;
        $actRecords->token_id = $tokenArray;
        $actRecords->by_user_id = Auth::user()->id;
        $actRecords->type = "SHOW_DTR_LIST";
        $actRecords->save();


        $show_dtr_list_query = "SELECT ACNo, datetime, state, deviceID, id, company_id FROM `date_time_records` WHERE (DATE(datetime) BETWEEN '".$start_date."' AND '".$end_date."')";
        $show_dtr_list = DB::connection('mysql')->select($show_dtr_list_query);

        
        return $show_dtr_list;
    }
    
    public function timekeeping(Request $request) {
        $validatedData = $request->validate([
            'email'=>'required',
            'password'=>'required',
            'ACNo'=>'required',
            'name'=>'required',
            'datetime'=>'required',
            'state'=>'required',
            'deviceID'=>'required',
            'status'=>'required'
        ]);
        
        $user_info_email = User::where('email',$request->email)->first();
        
        $datetime = date("Y-m-d H:i:s",strtotime($request->datetime));
        
        if ( !empty($user_info_email) ) {
            if(Hash::check($request->password, $user_info_email->password)){

                $DTR = new DateTimeAPI;
                $DTR->company_id = $user_info_email->company_id;
                $DTR->user_id = $user_info_email->id;
                $DTR->ACNo = $request->ACNo;
                $DTR->name = $request->name;
                $DTR->email = $request->email;
                $DTR->datetime = $datetime;
                $DTR->state = $request->state; 
                $DTR->deviceID = $request->deviceID;
                $DTR->status = $request->status;
                $DTR->save();
    
                return response(['status'=>'ok','message'=>'Upload Success']);
                
            } else {
                return response(['status'=>'error','message'=>'Incorrect username/password!']);
            }
        } else {
            return response(['status'=>'error','message'=>'Incorrect username/password!']);
        }
    }

    public function timekeeping_app(Request $request) {
            $validatedData = $request->validate([
                'state'=>'required',
                'deviceID'=>'required',
                'address'=>'required',
                'longitude'=>'required',
                'latitude'=>'required'
            ]);
            $datetime = date("Y-m-d H:i:s",strtotime($request->datetime));
            $DTR = new DateTimeAPI;
            $DTR->user_id = Auth::user()->id;
            $DTR->company_id = $user_info->company_id;
            $DTR->ACNo = $user_info->ACNo;
            $DTR->name = $user_info->name;
            $DTR->email = $user_info->email;
            $DTR->datetime = now();
            $DTR->state = $request->state; 
            $DTR->deviceID = $request->deviceID;
            $DTR->address = $request->address;
            $DTR->longitude = $request->longitude;
            $DTR->latitude = $request->latitude;

            if ($request->report != "") {
                $DTR->report = $request->report;
            } else {
                $DTR->report = NULL;
            }

            $DTR->save();

            if ($request->state == "C/In") {
                return response(['status'=>'ok','message'=>'Time In Successful']);
            } else if ($request->state == "C/Out") {
                return response(['status'=>'ok','message'=>'Time Out Successful']);
            } else {
                return response(['status'=>'ok','message'=>'Upload Success']);
            }
    }

    public function login(Request $request) {
        $request->validate([
            'companyID'=>'required',
            'password'=>'required'
        ]);
        
        $user = User::where('company_id',$request->companyID)->first();

        if ( !$user ) {
            return response(['status'=>'error','message'=>'Incorrect username/password!']);
        } else if($user->deleted == 1){
            return response(['status'=>'error','message'=>'This account is deleted!']);
        } else if( Hash::check($request->password, $user->password) ) {

            $http = new Client;
            $response = $http->post(url('oauth/token'), [
                'form_params' => [
                'grant_type' => 'password',
                'client_id' => '2',
                'client_secret' => 'gsPZSYc8by6zXNiq1wMcgKmlC7WxUenn1CdhtRxm',
                'username' => $user->email,
                'password' => $request->password,
                'scope' => '',
                ],
            ]);
        
            return response(['data'=>json_decode((string) $response->getBody(), true),
                'status'=>'Success',
                'message'=>'Authenticated',
                'employee_name'=>$user->name
            ]);
        
        }
        else
        {
            return response(['status'=>'error','message'=>'Incorrect user/password!']);
        }
    }

    public function register(Request $request) {
        $validatedData = $request->validate([
            'company_id'=>'required',
            'email'=>'required',
            'password'=>'required',
            'name'=>'required',
            'ACNo'=>'required'
        ]);

        $user_info_email = User::where('email',$request->email)->first();
        $user_info_id = User::where('company_id',$request->company_id)->first();

        if ( !empty($user_info_email) ) {
            return response(['status'=>'error','message'=>'email already exists!']);
        } else if( !empty($user_info_id) ) {
            $user->email = $request->email;
            return response(['status'=>'error','message'=>'company_id already exists!']);
        } else {
            $user = new User;
            $user->company_id = $request->company_id;
            $user->email = $request->email;
            $user->ACNo = $request->ACNo;
            $user->password = Hash::make($request->password);
            $user->deleted = 0;
            $user->name = $request->name;
            $user->created_by = Auth::user()->company_id;
            $user->save();
            return response(['status'=>'ok','message'=>'Upload Success']);
        }
    }

    public function logout(Request $request)
    {
        $tokenArray = Auth::user()->token()->id;
        //Update revoke field to destroy specific token logged in by the user.
        DB::table('oauth_access_tokens')
        ->where('user_id', Auth::user()->id)
        ->update([
        'revoked' => true
        ]);
        return response(['status'=>'oK','message'=>'Successfully logout!']);
    }

    public function update(Request $request) {
        $validatedData = $request->validate([
            'company_id'=>'required'
        ]);
        
        $company_id = $request->company_id;
        $user_info = User::where('company_id',$company_id)->first();
        
        if ( !empty( $user_info ) ) {

            if($request->name == ""){
                $name = $user_info->name;
            }else{
                $name = $request->name;
            }

            if($request->email == ""){
                $email = $user_info->email;
            }else{
                $email = $request->email;
            }

            if($request->password == ""){
                $password = bcrypt($user_info->password);
            }else{
                $password = bcrypt($request->password);
            }

            DB::table('users')
            ->where('company_id', $request->company_id)
            ->update([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'updated_by' => Auth::user()->company_id
            ]);

            $actRecords = new ActivityRecords;
            $actRecords->token_id = $tokenArray;
            $actRecords->by_user_id = Auth::user()->id;
            $actRecords->for_company_id = $company_id;
            $actRecords->type = "UPDATE";
            $actRecords->save();
            

            return response(['status'=>'oK','message'=>'Successfully Updated!']);
            
        } else {
            return response(['status'=>'error','message'=>'company_id not found!']);
        }
    }

    public function delete(Request $request) {
        $validatedData = $request->validate([
            'company_id'=>'required'
        ]);
        $company_id = $request->company_id;

        $user_info = User::where('company_id',$company_id)->first();

        if (!empty($user_info)) {

            DB::table('users')
            ->where('company_id', $company_id)
            ->update([
            'deleted' => '1',
            'updated_by' => Auth::user()->company_id
            ]);
            
            return response(['status'=>'oK','message'=>'Successfully Deleted!']);
            
        } else {
            return response(['status'=>'error','message'=>'company_id not found!']);
        }
    }
    public function current_time()
    {
        return response([
            'status'=>'oK',
            'name'=>auth()->user()->name,
            'companyID'=>auth()->user()->company_id,
            'date'=>date("F d Y",strtotime(now())),
            'time'=>date("h:i a",strtotime(now())),
            'datetime'=>now()
        ]);
    }

    public function getArticle () {
        $data = Article::orderBy('created_at','DESC')->paginate(1);
        return $data;
    }
}
