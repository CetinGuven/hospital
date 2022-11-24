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
            response(false, "Lütfen boş bırakmayınız", false);
        }

        $checkdoktor = M("kullanici")
            ->where([
                "email" => $email,
                "password" => $password,
            ])
            ->select();

        if (count($checkdoktor) == 0) {
            response(false, "Email veya Şifre Yanlış", false);
        } else {
            Session("ID", $checkdoktor[0]["id"]);
            var_dump(session("ID"));

            response(true, "Hoşgeldiniz", true);
        }
    }

    public function randevugor()
    {
        session_start();
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $randevugör=$data->randevugör;

        $checkrandevu = M("randevu")
            ->field(["tarih","dolu_saat","hastaadi_id","aciklama"])
            ->where([
              "doktor_id"=>$_SESSION["ID"]
            ])
            ->select();

        if (count($checkrandevu) == 0) {
            response(false, "Randevunuz Bulunmamaktadır.", false);
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
       
        $randevu = M("randevu"); 
        $randevu->where("hastaadi_id=$hastasec")->setField('aciklama', $aciklama );
    
        if (count($aciklama) == 1) {
            response( $aciklama,"Acıklama başarıyla eklendi.",true);
        } 
    
    }
    public function logout(){

        session('');
    
    }}


    ?>