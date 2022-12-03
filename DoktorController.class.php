<?php

namespace Home\Controller;
use Think\Controller;
use PhpMyAdmin\SqlParser\Components\Condition;


class DoktorController extends Controller
{
    public function login()
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $email = $data->email;
        $password = $data->password;

        if (is_null($email) || is_null($password)) {
            response(false, "Please do not leave blank", false);
        }

        $checkdoktor = M("users")
            ->where([
                "email" => $email,
                "password" => $password,
            ])
            ->select();

        if (count($checkdoktor) == 0) {
            response(false, "Email or Password Incorrect", false);
        } else {
            Session("ID", $checkdoktor[0]["id"]);
            var_dump(session("ID"));

            response(true, "Welcome", true);
        }
    }

    public function randevugor()
    {
        session_start();
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $randevugör=$data->randevugör;

        $checkrandevu = M("appointment")
            ->field(["tarih","dolu_saat","hastaadi_id","aciklama"])
            ->where([
              "doktor_id"=>$_SESSION["ID"]
            ])
            ->select();

        if (count($checkrandevu) == 0) {
            response(false, "You do not have an appointment.", false);
        } else {
            response($checkrandevu, true,true);
        } 
    }

    public function aciklamaekle()
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $hastasec=$data->hastasec;
        $aciklama=$data->aciklama;
       
        $randevu = M("appointment"); 
        $randevu->where("hastaadi_id=$hastasec")->setField('aciklama', $aciklama );
    
        if (count($aciklama) == 1) {
            response( $aciklama,"Succesfully added.",true);
        } 
    
    }
    public function logout(){

        session('');
    
    }}


    ?>
