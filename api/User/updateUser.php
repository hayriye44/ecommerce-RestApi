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
 
// database connection will be here
//Veritabanına ve kullanıcı tablosuna bağlan
// files needed to connect to database
include_once '../config/Database.php';
include_once '../objects/User.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate user object
$user = new User($db);
 
// retrieve given jwt here

include "../Validate_token.php";

// set user property values
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->password = $data->password;
$user->id = $decoded->data->id;
// update user will be here

//Aşağıdaki kodlardan biri, kullanıcı nesnesinin create () metodunu kullanıyoruz. O dönerse gerçek , 
//kullanıcının güncellendi anlamına gelir. Yanlış döndürürse , sistem kullanıcı bilgilerini güncelleyemez.
// update the user record
if($user->update()){
    // regenerate jwt will be here
    // we need to re-generate jwt because user details might be different
    $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => array(
            "id" => $user->id,
            "firstname" => $user->firstname,
            "lastname" => $user->lastname,
            "email" => $user->email
        )
    );
    $jwt = JWT::encode($token, $key);
    
    // set response code
    http_response_code(200);
    
    // response in json format
    echo json_encode(
            array(
                "message" => "User was updated.",
                "jwt" => $jwt
            )
        );
}
 
// message if unable to update user
else{
    // set response code
    http_response_code(401);
 
    // show error message
    echo json_encode(array("message" => "Unable to update user."));
}

