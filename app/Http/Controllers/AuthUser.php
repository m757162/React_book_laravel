<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Book;
use Hash;
use Illuminate\Support\Facades\Auth;

class AuthUser extends Controller
{
    public function registation(Request $request)
    {

       
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:6',
            ],
        
        );
 
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }
        User::insert([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>Hash::make($request->password),
        ]);
        Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        $user= Auth::user();

        $token=$user->createToken("mytoken")->accessToken;
        return response()->json([
            "msg"=> "success",
            "token"=> $token
        ]);    
    }

    public function login(Request $request)
    {
             
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6',
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }
        if (Auth::attempt(['email' =>$request->email, 'password' => $request->password])) {
            $user= Auth::user();
            $token=$user->createToken("mytoken")->accessToken;
            return response()->json([
                "msg"=> "success",
                "token"=> $token
            ]);
        }
        return response()->json(["user Unauthorize. Your email or password or both wrong!"]);
    }
    public function logout(Request $request){
        return  $request->user()->token()->revoke(); 
    }

    public function dashboard(Request $request)
    {       
        return  $book=Book::paginate(2);
    }
    public function searchName(Request $request)
    {
        if($request->search !== ''){
            return  $book=Book::where('name','like',$request->search.'%')->get();
        }
    }
     
    public function get_data(Request $request){
        // return $request->file("selectImage")->name;
         $validator = Validator::make($request->all(), [
            'book_name' => 'required|max:20',
            'selectImage' => 'required',
            'details' => 'required|max:200',
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }
        $image=$request->file("selectImage")->store('public');
        $expoldimg=explode('/',$image)[1];
        Book::insert([
            "name"=>$request->book_name,
            "image"=>$expoldimg,
            "details"=>$request->details,
        ]);
        return "data uploaded successfully";
       
    }

}
