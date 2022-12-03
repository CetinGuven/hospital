<?php

namespace Home\Controller;
use Think\Controller;
use PhpMyAdmin\SqlParser\Components\Condition;

class AdminController extends Controller
{
    public function Register()
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $email = $data->email;
        $password = $data->password;

        if (is_null($email) || is_null($password)) {
            response(false, "Please do not leave blank", false);
        }

        if ($email == "" || $password == "") {
            response(false, "Please do not leave blank", false);
        }
        $check = M("kullanici")
            ->where(["email" => $email])
            ->find();
        if (Count($check) > 0) {
            response(false, "This email has already been registered.", false);
        }
    
        $admin = M("users");
        $dataList[] = [
            "email" => $email,
            "password" => $password,
            "type" => 3,
        ];
        $admin->addAll($dataList);
        response(true, "Your Registration to the Admin Panel has been done successfully.", true);
    }

    public function login()
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $email = $data->email;
        $password = $data->password;
        
        $checkadmin = M("kullanici")
            ->where([
              'email' => $email,
              'password' => $password,
            ])
            ->select();
          
        if (count($checkadmin) ==0) {

            response(false, "Email or Password Incorrect", false);

        } else {
            Session("ID", $checkadmin[0]["id"]);
           
            response( $_SESSION["ID"], "Hoşgeldiniz",true);
        }
    }
    public function change Password()
    { 
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $password = $data->password;
        $yeni=$data->yeni;

        $check = M("users")
            ->where([
                "password" => $password,
                     ])
            ->select();
        if (count($check) == 0) {

            response(false, "Please enter your current password correctly.", false);

        } 
           $kullanici = M ( "users" ); 
           $kullanici->where("password=$password")->setField('password', $yeni );
        
            if (count($yeni) == 1) {
                response( $yeni,"Your password has been successfully changed.",true);
            
        }
    }
    public function add doctor()
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $email = $data->email;
        $password = $data->password;
        $namedata->name
        $surnamea->surname

        if (
            is_null($name
            is_null($surname
            is_null($email) ||
            is_null($password)
        ) {
            response(false, "Please do not leave blank", false);
        }
        $doktorekle = M("users")
            ->where(["email" => $email])
            ->find();
        if (Count($doktorekle) > 0) {
            response(false, "This email has already been registered.", false);
        }

        $admin = M("users")
        $dataList[] = [
            "name" =>$name,
            "surname =>surname,
            "email" => $email,
            "password" => $password,
            "type" => "2",
        ];
        $admin->addAll($dataList);
        response(true, "Successfully Registered to the Physician Panel.", true);
    }

    public function doktorsil()
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $doktorsil = $data->doktorsil;

        $check = M("users")
            ->where(["id" => $doktorsil])
            ->delete();

        if (count($check) == 1) {
            $check = M("randevu")
                ->where(["doktor_id" => $doktorsil])
                ->delete();

            if (count($check) == 1) {
                response($check, "Doctor deleted successfully", true);
            }
        }
    }
     public function doktorlistele(){

        $json = file_get_contents("php://input");
        $data = json_decode($json);
    
        $listele=$data->listele; 
    
        $check = M("users")
        ->field(["name","surname","email","password"])
        ->where
                (["type"=>2])
        ->select();
    
        if (count($check) == 0) {
            response(false, "There Is No Doctor To List.", false);
        } else {
            response($check, "Below is the list of doctors", true);
        }
    }
    public function logout(){

        session('');
        session(''); 
    
    }}


?>
