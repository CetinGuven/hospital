<?php

namespace Home\Controller;
use Think\Controller;
use PhpMyAdmin\SqlParser\Components\Condition;
use IsaEken\PhpTcKimlik\PhpTcKimlik;

class UserController extends Controller
{
    public function kayit()
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $ad = $data->ad;
        $soyad = $data->soyad;
        $tc = $data->tc;
        $dogumyili = $data->dogumyili;
        $email = $data->email;
        $password = $data->password;
        

        if (
            is_null($ad) ||
            is_null($soyad) ||
            is_null($dogumyili) ||
            is_null($tc) ||
            is_null($email) ||
            is_null($password)
        ) {
            response(false, "Lütfen boş bırakmayınız", false);
        }

        if (isset($tc)) {
            if (strlen($tc) == 11 && is_numeric($tc)) {
                $say = 0;
                for ($i = 0; $i <= 9; $i++) {
                    $say = $say + $tc[$i];
                }
            }
            if (substr($say, -1) != substr($tc, -1)) {
                response(
                    false,
                    "Girmiş Olduğunuz TC Kimlik Numarası Yanlıştır.",
                    false
                );
            }
        }

        $check = M("kullanici")
            ->where(["tc" => $tc])
            ->find();
        if (Count($check) > 0) {
            response(
                false,
                "Bu TC Kimlik Numarası İle Daha Önce Kayıt Yapılmış.",
                false
            );
        }

        if (
            !preg_match("@[0-9]+@", $password) ||
            !preg_match("@[A-Z]+@", $password) ||
            !preg_match("@[a-z]+@", $password)
        ) {
            response(
                false,
                "Lütfen en az bir tane küçük/büyük harf ve rakam giriniz",
                false
            );
        }

        //     $sonuc = PhpTcKimlik::isValidIdentity(
        //         $kullanici_tc,
        //         $kullanici_ad,
        //         $kullanici_soyad,
        //         new \DateTime($kullanici_dogumyili)
        //     );
        //    // var_dump($sonuc);

        $kullanici = M("kullanici");
        $dataList[] = [
            "ad" => strtolower($ad),
            "soyad" => strtolower($soyad),
            "dogumyili" => $dogumyili,
            "tc" => $tc,
            "email" => $email,
            "password" =>md5($password),
            "type" => "1",
        ];
        $kullanici->addAll($dataList);
        response(true, "Kayıt yapıldı.", true);
    }
    public function login()
    {
        
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $tc = $data->tc;
        $password = $data->password;

        $check = M("kullanici")
            ->where([
                "tc" =>$tc,
                md5("password") => $password,
            ])
            ->select();
            // echo "<pre>";
            // var_dump($check);
            // die();
        if (count($check) == 0) {
            response(false, "TC Kimlik Numarası veya Şifre Yanlış", false);
        }
        if($check[0]["banned"] == 1) {
          response(false, 
                "Hesabınız erişime kapalıdır.",
                  false);
        }
        Session("ID", $check[0]["id"]);
        var_dump(session("ID"));
        {
            response(true, "Hoşgeldiniz.", true);
        }


    }  
    public function randevularim()
   {

        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $listele = $data->listele;

        $check = M("randevu")
            ->field(["tarih","doktor_id","dolu_saat"])
            ->where(["hastaadi_id" => $_SESSION["ID"]])
            ->select();

        if (count($check) == 0) {
            response(false, "Randevunuz Bulunmamaktadır.", false);
        } else {
            response($check, "Randevunuzlarınız var .", true);
        }
    }
    public function randevual()
    {

        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $doktorsec = $data->doktorsec;
        $tarihsec = $data->tarihsec;
        $saatsec = $data->saatsec;

        $randevusaatleri=array();

        for($i=9;$i<=17;$i++){
            $randevusaatler[]=$i.":00:00<br>";
        }
        for($i=0;$i<count($randevusaatler);$i++){
           //echo $randevusaatler[$i];
        }
    
        $checkdolu = M("randevu")
            ->field(["dolu_saat", "tarih"])
            ->where([
                "doktor_id" => $doktorsec,
                "tarih" => $tarihsec,
            ])
            ->select();

        foreach ($checkdolu as $Key => $Element) {
            if (in_array($Element["dolu_saat"], $randevusaatleri)) {
                $randevusaatleri = array_diff($randevusaatleri, [
                    $Element["dolu_saat"],
                ]);
            }
        }

        $randevu = M("randevu");
        $datalist[] = [
            "doktor_id" => $doktorsec,
            "tarih" => $tarihsec,
            "dolu_saat" => $saatsec,
            "hastaadi_id" => $_SESSION["ID"],
        ];
        $randevu->addAll($datalist);
        //var_dump($datalist);

        if (count($datalist) == 0) {
            response(false, "Hata oluştu", false);
        } else {
            response(true, "Randevunuz Başarıyla kaydedildi.", true);
        }
    }
    public function randevuiptal()
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $randevuiptal = $data->randevuiptal;
        
        $iptal = M("randevu")
                ->where(["id" => $randevuiptal])
                ->delete();

            if (count($iptal) == 1) {
                response($iptal, "Randevu İptal edildi.", true);
            }      
    }
    public function logout(){

        session('');
        session(''); 
        
    }}

    echo "dfsf";
?>
