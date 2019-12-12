<?php


    // required headers
//Bu dosya JSON formatında bir çıktı döndürür ve belirtilen URL'den gelen istekleri kabul eder. Doğru başlıkları ayarlayacağız
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
//  JWT'nin kodunu çözecek dosyalar

//Aşağıdaki kod, verilen JWT kodunu çözmek için gerekli dosyaların dahil edildiğini gösterir

// required to encode json web token
include_once '../config/Core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

 
// retrieve gieve jwt here 
//JWT yi al
// get posted data
$data = json_decode(file_get_contents("php://input"));
// get jwt
$jwt=isset($data->jwt) ? $data->jwt : "";
// decode jwt here
//varsa jwt kodunu çözün 
// if jwt is not empty
if($jwt){
        // if decode succeed, show user details
        try {
    
            // decode jwt
            $decoded = JWT::decode($jwt, $key, array('HS256'));
    
            // set user property values here
        }
        // catch failed decoding will be here
        //kod çözme başarısız 0lursa
        //JWT'nin kod çözme işlemi başarısız olursa, 401'lik bir yanıt kodu belirlememiz gerekir,
        // kullanıcı erişiminin reddedildiğini ve hatayla ilgili bilgileri göstermemiz gerektiğini söyleyin.
        // if decode fails, it means jwt is invalid
        catch (Exception $e){
        
            // set response code
            http_response_code(401);
        
            // show error message
            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }
}
// error message if jwt is empty will be here


?>
