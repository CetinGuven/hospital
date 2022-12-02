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
            response(false, "Lütfen boş bırakmayınız", false);
        }

        if ($email == "" || $password == "") {
            response(false, "Lütfen boş bırakmayınız", false);
        }
        $check = M("kullanici")
            ->where(["email" => $email])
            ->find();
        if (Count($check) > 0) {
            response(false, "Bu email ile Daha Önce Kayıt Yapılmış.", false);
        }
    
        $admin = M("kullanici");
        $dataList[] = [
            "email" => $email,
            "password" => $password,
            "type" => 3,
        ];
        $admin->addAll($dataList);
        response(true, "Admin Paneline Kaydınız Başarıyla yapıldı.", true);
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

            response(false, "Email veya Şifre Yanlış", false);

        } else {
            Session("ID", $checkadmin[0]["id"]);
            //var_dump(session("ID"));

            response( $_SESSION["ID"], "Hoşgeldiniz",true);
        }
    }
    public function change Password()
    { 
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $password = $data->password;
        $yeni=$data->yeni;

        $check = M("kullanici")
            ->where([
                "password" => $password,
                     ])
            ->select();
        if (count($check) == 0) {

            response(false, "Mevcut şifrenizi doğru giriniz.", false);

        } 
           $kullanici = M ( "kullanici" ); 
           $kullanici->where("password=$password")->setField('password', $yeni );
        
            if (count($yeni) == 1) {
                response( $yeni,"Şifreniz başarıyla değiştirildi.",true);
            
        }
    }
    public function add doctor()
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $email = $data->email;
        $password = $data->password;
        $ad = $data->ad;
        $soyad = $data->soyad;

        if (
            is_null($ad) ||
            is_null($soyad) ||
            is_null($email) ||
            is_null($password)
        ) {
            response(false, "Please do not leave blank", false);
        }
        $doktorekle = M("kullanici")
            ->where(["email" => $email])
            ->find();
        if (Count($doktorekle) > 0) {
            response(false, "This email has already been registered.", false);
        }

        $admin = M("kullanici");
        $dataList[] = [
            "ad" => $ad,
            "soyad" => $soyad,
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

        $check = M("kullanici")
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
    
        $check = M("kullanici")
        ->field(["ad","soyad","email","password"])
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
