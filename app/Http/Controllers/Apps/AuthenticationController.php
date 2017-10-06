<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Authentication;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Providers\JWTAuthServiceProvider;
use JWTAuthException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Password;

class AuthenticationController extends Controller
{
    use AuthenticatesUsers;
    /**
     *
     * Constructor
     *
     */
    protected $request;
    
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return "aa";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function doLogin(Request $request)
     {

        $user = Authentication::where('email', $request->email)->first();
        
        if( $user && $user->password == md5($request->password) )
        {
            // $userA = Auth::login($user); /// will log the user in for you
        
            // return Redirect::intended('dashboard');

            //$token = $user->createToken('Token Name')->accessToken;
            $token = Password::getRepository()->createNewToken();            
            
            return ['token' =>  $token];            

        }
        else
        {
            return "gagal";
        }

        // $credentials = $request->only('email', 'password');
        // return $credentials;
        // $token = null;
        // try {
        //    if (!$token = JWTAuth::attempt($credentials)) {
        //     return response()->json(['invalid_email_or_password'], 422);
        //    }
        // } catch (JWTAuthException $e) {
        //     return response()->json(['failed_to_create_token'], 500);
        // }
        // return response()->json(compact('token'));

     }

     public function getAuthUser(Request $request){
        $user = JWTAuth::toUser($request->token);
        return response()->json(['result' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nmfile' => 'required|max:500',
            'keterangan' => 'required|max:500'
        ]);

        return Mititi::create([ 
            'nmfile' => $request->nmfile,
            'keterangan' => $request->keterangan
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Mititi::where('RecID', $id)
                ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nmfile' => 'required|max:500',
            'keterangan' => 'required|max:500'
        ]);

        return Mititi::where('RecID', $id)
        ->update([
            'nmfile' => $request->nmfile,
            'keterangan' => $request->keterangan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Mititi::where('RecID', $id)->delete();
    }
}
