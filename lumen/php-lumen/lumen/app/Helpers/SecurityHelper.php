<?php
use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;

function generatetoken($length = 16, $level = 2) // function to generate new password
{
    //$length = rand(6,16);
    list($usec, $sec) = explode(' ', microtime());
    srand((float) $sec + ((float) $usec * 100000));

    $validchars[0] = "abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $validchars[1] = "0123456789";
    $validchars[2] = "abcdfghjkmnpqrstvwxyz";
    $validchars[3] = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    //$validchars[4] = "_!@#*()~%^|";
    //$validchars[4] = "_@#*()^|";

    $password = "";
    $counter = 0;

    while ($counter < $length)
    {
        $level = rand(0, 3);
        $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level]) - 1), 1);
        // All character must be different
        if (!strstr($password, $actChar))
        {
            $password .= $actChar;
            $counter++;
        }

        $actChar = substr($validchars[1], rand(0, strlen($validchars[1]) - 1), 1);
        // All character must be different
        if (!strstr($password, $actChar))
        {
            $password .= $actChar;
            $counter++;
        }

        $actChar = substr($validchars[0], rand(0, strlen($validchars[0]) - 1), 1);
        // All character must be different
        if (!strstr($password, $actChar))
        {
            $password .= $actChar;
            $counter++;
        }

        /*$actChar = substr($validchars[4], rand(0, strlen($validchars[4])-1), 1);
    // All character must be different
    if (!strstr($password, $actChar))
    {
    $password .= $actChar;
    $counter++;
    }*/
    }
    return $password;
}

function generatepassword($length = 8, $level = 4) // function to generate new password
{
    //$length = rand(6,16);
    list($usec, $sec) = explode(' ', microtime());
    srand((float) $sec + ((float) $usec * 100000));

    $validchars[0] = "abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $validchars[1] = "0123456789";
    $validchars[2] = "abcdfghjkmnpqrstvwxyz";
    $validchars[3] = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $validchars[4] = "_!@#*()~%^|";
   // $validchars[4] = "_@#*()^|";

    $password = "";
    $counter = 0;

    while ($counter < $length)
    {
        $level = rand(0, 4);
        $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level]) - 1), 1);
        // All character must be different
        if (!strstr($password, $actChar))
        {
            $password .= $actChar;
            $counter++;
        }

        $actChar = substr($validchars[1], rand(0, strlen($validchars[1]) - 1), 1);
        // All character must be different
        if (!strstr($password, $actChar))
        {
            $password .= $actChar;
            $counter++;
        }

        $actChar = substr($validchars[0], rand(0, strlen($validchars[0]) - 1), 1);
        // All character must be different
        if (!strstr($password, $actChar))
        {
            $password .= $actChar;
            $counter++;
        }

        $actChar = substr($validchars[2], rand(0, strlen($validchars[2]) - 1), 1);
        // All character must be different
        if (!strstr($password, $actChar))
        {
            $password .= $actChar;
            $counter++;
        }

        $actChar = substr($validchars[3], rand(0, strlen($validchars[3]) - 1), 1);
        // All character must be different
        if (!strstr($password, $actChar))
        {
            $password .= $actChar;
            $counter++;
        }

        $actChar = substr($validchars[4], rand(0, strlen($validchars[4])-1), 1);
        // All character must be different
        if (!strstr($password, $actChar))
        {
            $password .= $actChar;
            $counter++;
        }
    }
    return $password;
}

function stringencrypt($string, $key = 'emagic@2018')
{
    $key = trim($key);
    if ($key == '')
    {
        $key = hex2string('73736f4065736473646324');
    }
    $result = '';
    $i = 0;
    while($i < strlen($string))
    {
        $char = substr($string, $i, 1);
        $keychar = substr($key, $i % strlen($key) - 1, 1);
        $char = chr(ord($char) + ord($keychar));
        $result .= $char;
        ++$i;
    }
    return base64_encode($result);
}

function stringdecrypt($string, $key = 'emagic@2018')
{
    $key = trim($key);
    if ($key == '')
    {
        $key = hex2string('73736f4065736473646324');
    }
    
    $result = '';
    $string = base64_decode($string);
    $i = 0;

    while($i < strlen($string))
    {
        $char = substr($string, $i, 1);
        $keychar = substr($key, $i % strlen($key) - 1, 1);
        $char = chr(ord($char) - ord($keychar));
        $result .= $char;
        ++$i;
    }
    return $result;
}

function hex2string($hex)
{
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}

/*
* This is controller function use to remove html tags of input array

* @author       Vishal Chaudhari
* @access       public
* @param        array $inputs
* @param        array|null $excepts
* @return       Array
* @tables       NA
*/
function removeHtmlTagsOfFields(array $inputs, array $excepts = null)
{
    $inputOriginal = $inputs;

    $inputs = array_except($inputs, $excepts);

    foreach ($inputs as $index => $in){
        $inputs[$index] = strip_tags($in);
    }

    if(!empty($excepts)){

        foreach ($excepts as $except){
            $inputs[$except] = $inputOriginal[$except];
        }
    }

    return $inputs;
}


/*
* This is controller function use to remove html from given field

* @author       Vishal Chaudhari
* @access       public
* @param        array $field
* @return       string
* @tables       NA
*/
function removeHtmlTagsOfField(string $field){
    //return htmlentities(strip_tags($field), ENT_QUOTES, 'UTF-8');
    return htmlentities($field, ENT_QUOTES, 'UTF-8');
}
// Not working properly
function _BIN_TO_UUID($uuid)
{
	$uuidReadable = unpack("h*",$uuid);
	$uuidReadable = preg_replace("/([0-9a-f]{8})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{12})/", "$1-$2-$3-$4-$5", $uuidReadable);
	$uuidReadable = array_merge($uuidReadable);
	return $uuidReadable;
}
function getAssetId()
{    
    $s = strtoupper(md5(uniqid(rand(),true))); 
    $guidText = 
        substr($s,0,6); 
       // substr($s,8,4) . '-' . 
       // substr($s,12,4). '-' . 
       // substr($s,16,4). '-' . 
       // substr($s,20); 
    return $guidText;

}
/*
 * Create a new token.
 * @author Vishal Chaudhari
 * @access public
 * @param \App\User   $user
 * @request      POST
 * @return string
 * @category      Auth.
 *
 */
function genratejwttoken($user_id=null)
{
    $payload = [
        'issuer' => "lumen-jwt", // Issuer of the token
        'subject' => $user_id, // Subject of the token
        'genat' => time(), // Time when JWT was issued. 
        //'ipaddress' => $this->request->ip(), // Time when JWT was issued. 
        'expireon' => time() + 60*60 // Expiration time
    ];
    // As you can see we are passing `JWT_SECRET` as the second parameter that will 
    // be used to decode the token in the future.
    return JWT::encode($payload, env('JWT_KEY'));
}