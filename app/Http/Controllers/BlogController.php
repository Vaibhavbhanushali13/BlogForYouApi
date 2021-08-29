<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use Illuminate\Http\Request;
use DB;
use App\User;
use Redirect;
use File;
use Storage;

class BlogController extends Controller
{
  public function __construct()
  {
      set_time_limit(8000000);
  }



  //login app user
  public function loginUser(Request $Request){
    Log::debug( __METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] ' );
    try {
      $input=$Request->all();
      $validatedData = $Request->validate([
          'email' => 'required',
          'password' => 'required',
      ]);
      $email = $input['email'];
      $password = $input['password'];
      $user =User::where('email', '=', $email)->where('password', '=', $password)->first();
      if(isset($user) && !empty($user)){
          $status = 1;
          $msg =  'Login Successfull';
          $data = $user;
      }else{
        $status = 0;
        $msg =  'Please check your credentials.';
        $data = '';
      }
      $response = array('status'=>$status,'msg'=>$msg,'data'=>$data);
      return json_encode($response);
    }catch(Exception $e){
      Log::error(__METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] '.' with error '.$e->message().' at line '.__LINE__);
    }
  }

  //signup  user
  public function SignupUser(Request $Request){
    Log::debug( __METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] ' );
    try {
      $input=$Request->all();
      $validatedData = $Request->validate([
          'email' => 'required',
          'password' => 'required'
      ]);
      $email = $input['email'];
      $password = $input['password'];
      $user_check =User::where('email', '=', $email)->first();
      if(isset($user_check) && !empty($user_check)){
        $status = 0;
        $msg =  'The Account is already there with us. Please login.';
      }else{
        $user =User::insertGetId([
          'email' => $email,
          'password' => $password,
        ]);
        if(isset($user) && !empty($user) ){
          $status = 1;
          $msg =  'Signup Successfull.';
        }else{
          $status = 0;
          $msg =  'Unable to signup.please try again later...';
        }
      }
      $response = array('status'=>$status,'msg'=>$msg);
      return json_encode($response);
    }catch(Exception $e){
      Log::error(__METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] '.' with error '.$e->message().' at line '.__LINE__);
    }
  }

  //create post
  public function createPost(Request $Request){
    Log::debug( __METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] ' );
    try {
      $input=$Request->all();
      $validatedData = $Request->validate([
          'title' => 'required',
          'subtitle' => 'required',
          'description' => 'required',
          'tag' => 'required',
          'email' => 'required'
      ]);
      $user_check =User::where('email', '=',  $input['email'])->first();
      if(isset($user_check) && !empty($user_check)){
        $insert_post= DB::connection('mysql')->table('blogs')->insert(
             ['title' => $input['title'],'subtitle' => $input['subtitle'],'content' => $input['description'],
             'tag' => $input['tag'],'created_by' => $user_check->id ]
           );
          if(isset($insert_post) && $insert_post == 1 ){
            $status = 1;
            $msg =  'Post Uploaded Successfully.';
          }else{
            $status = 0;
            $msg =  'Error Occured.please try again later...';
          }
        }else{
          $status = 1;
          $msg =  'Please Signup/login first.';
        }
      $response = array('status'=>$status,'msg'=>$msg);
      return json_encode($response);
    }catch(Exception $e){
      Log::error(__METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] '.' with error '.$e->message().' at line '.__LINE__);
    }
  }

  //get post
  public function getPost(Request $Request){
    Log::debug( __METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] ' );
    try {
        $get_post= DB::connection('mysql')->table('blogs')->select()->where('delete_flag', '=',  0)->get();
          if(isset($get_post) && !empty($get_post) ){
            $status = 1;
            $msg =  '';
            $data = $get_post;
          }else{
            $status = 0;
            $msg =  'No Post Found.';
            $data = '';
          }
      $response = array('status'=>$status,'msg'=>$msg,'data'=>$data);
      return json_encode($response);
    }catch(Exception $e){
      Log::error(__METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] '.' with error '.$e->message().' at line '.__LINE__);
    }
  }

  //delete post
  public function deletePost(Request $Request){
    Log::debug( __METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] ' );
    try {
      $input=$Request->all();
      $validatedData = $Request->validate([
          'id' => 'required',
          'email' => 'required'
      ]);
      $user_check =User::where('email', '=',  $input['email'])->first();
      if(isset($user_check) && !empty($user_check)){
        $delete_post= DB::connection('mysql')->table('blogs')->where('id', '=',  $input['id'])->update(
             ['updated_by' => $user_check->id,'delete_flag' => 1,'updated_at' => date('Y-m-d H:i:s')]
           );
          if(isset($delete_post) && $delete_post == 1 ){
            $status = 1;
            $msg =  'Post Deleted Successfully.';
          }else{
            $status = 0;
            $msg =  'Error Occured.please try again later...';
          }
        }else{
          $status = 1;
          $msg =  'Please Signup/login first.';
        }
      $response = array('status'=>$status,'msg'=>$msg);
      return json_encode($response);
    }catch(Exception $e){
      Log::error(__METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] '.' with error '.$e->message().' at line '.__LINE__);
    }
  }

  //get post
  public function viewPost(Request $Request){
    Log::debug( __METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] ' );
    try {
        $input=$Request->all();
        $get_post= DB::connection('mysql')->table('blogs')->select()->where('id', '=',  $input['post_id'])->where('delete_flag', '=',  0)->get();
          if(isset($get_post) && !empty($get_post) ){
            $status = 1;
            $msg =  '';
            $data = $get_post;
          }else{
            $status = 0;
            $msg =  'No Post Found.';
            $data = '';
          }
      $response = array('status'=>$status,'msg'=>$msg,'data'=>$data);
      return json_encode($response);
    }catch(Exception $e){
      Log::error(__METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] '.' with error '.$e->message().' at line '.__LINE__);
    }
  }

  //get post
  public function getMyPost(Request $Request){
    Log::debug( __METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] ' );
    try {
      $input=$Request->all();
      $validatedData = $Request->validate([
          'email' => 'required'
      ]);
      $user_check =User::where('email', '=',  $input['email'])->first();
      if(isset($user_check) && !empty($user_check)){
        $get_post= DB::connection('mysql')->table('blogs')->select()->where('created_by', '=',  $user_check->id)->where('delete_flag', '=',  0)->get();
          if(isset($get_post) && !empty($get_post)){
            $status = 1;
            $msg =  '';
            $data = $get_post;
          }else{
            $status = 0;
            $msg =  'Error Occured.please try again later...';
            $data = '';
          }
        }else{
          $status = 1;
          $msg =  'Please Signup/login first.';
          $data = '';
        }
      $response = array('status'=>$status,'msg'=>$msg,'data'=>$data);
      return json_encode($response);
    }catch(Exception $e){
      Log::error(__METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] '.' with error '.$e->message().' at line '.__LINE__);
    }
  }

  //delete post
  public function updatePost(Request $Request){
    Log::debug( __METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] ' );
    try {
      $input=$Request->all();
      $validatedData = $Request->validate([
          'id' => 'required',
          'email' => 'required',
          'title' => 'required',
          'subtitle' => 'required',
          'content' => 'required'
      ]);
      $user_check =User::where('email', '=',  $input['email'])->first();
      if(isset($user_check) && !empty($user_check)){
        $delete_post= DB::connection('mysql')->table('blogs')->where('id', '=',  $input['id'])->update(
             ['updated_by' => $user_check->id,'title' => $input['title'],'subtitle' => $input['subtitle'],
             'content' => $input['content'],'updated_at' => date('Y-m-d H:i:s')]
           );
          if(isset($delete_post) && $delete_post == 1 ){
            $status = 1;
            $msg =  'Post Updated Successfully.';
          }else{
            $status = 0;
            $msg =  'Error Occured.please try again later...';
          }
        }else{
          $status = 1;
          $msg =  'Please Signup/login first.';
        }
      $response = array('status'=>$status,'msg'=>$msg);
      return json_encode($response);
    }catch(Exception $e){
      Log::error(__METHOD__ .' Entered in ' .' ['. __FUNCTION__ .'] '.' with error '.$e->message().' at line '.__LINE__);
    }
  }

}
