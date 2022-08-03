<?php
use Pecee\Http\Request;
use Pecee\Http\Response;
use Pecee\Http\Url;
use Pecee\SimpleRouter\SimpleRouter as Router;

/**
 * Get url for a route by using either name/alias, class or method name.
 *
 * The name parameter supports the following values:
 * - Route name
 * - Controller/resource name (with or without method)
 * - Controller class name
 *
 * When searching for controller/resource by name, you can use this syntax "route.name@method".
 * You can also use the same syntax when searching for a specific controller-class "MyController@home".
 * If no arguments is specified, it will return the url for the current loaded route.
 *
 * @param string|null $name
 * @param string|array|null $parameters
 * @param array|null $getParams
 * @return \Pecee\Http\Url
 * @throws \InvalidArgumentException
 */
function url(?string $name = null, $parameters = null, ?array $getParams = null): Url
{
    return Router::getUrl($name, $parameters, $getParams);
}

/**
 * @return \Pecee\Http\Response
 */
function response(): Response
{
    return Router::response();
}

/**
 * @return \Pecee\Http\Request
 */
function request(): Request
{
    return Router::request();
}

/**
 * Get input class
 * @param string|null $index Parameter index name
 * @param string|null $defaultValue Default return value
 * @param array ...$methods Default methods
 * @return \Pecee\Http\Input\InputHandler|array|string|null
 */
function input($index = null, $defaultValue = null, ...$methods)
{
    $data = null;
    if ($index !== null) {
        $data = request()->getInputHandler()->value($index, null, ...$methods);
    }
    if (!$data) {
        if (isset(json_decode(file_get_contents('php://input'))->$index)) {
            $data = json_decode(file_get_contents('php://input'))->$index;
        }
    }
    if ($data) {
        return $data;
    } else {
        return $defaultValue;
    }

}

/**
 * @param string $url
 * @param int|null $code
 */
function redirect(string $url, ?int $code = null): void
{
    if ($code !== null) {
        response()->httpCode($code);
    }

    response()->redirect($url);
}

/**
 * Get current csrf-token
 * @return string|null
 */
function csrf_token(): ?string
{
    $baseVerifier = Router::router()->getCsrfVerifier();
    if ($baseVerifier !== null) {
        return $baseVerifier->getTokenProvider()->getToken();
    }

    return null;
}

// Allow to provide responce and http code
function responce($data, $http_code)
{
    response()->httpCode($http_code);
    echo $data;
    exit;
}

//Allow to cut text
function cutText($data, $length, $more_icon = '...')
{
    $string = strip_tags(htmlspecialchars_decode($data, ENT_NOQUOTES));
    if (strlen($string) > $length) {
        $string = substr($string, 0, $length) . $more_icon;
    }
    return $string;
}

// Allow check if string contain some text
function isContain($string, $prefix)
{
    if (strpos($string, $prefix) !== false) {
        return true;
    } else {
        return false;
    }
}

// Allow to paginate data in array
function paginate($data, $item_to_show, $page)
{
    $Totaltems = count($data);
    $totalPage = ceil($Totaltems / $item_to_show);
    if ($page <= $totalPage && $page > 0) {
        if (($page - 1) < 0) {
            $PreviewsPage = null;
        } else {
            if ($page == 1) {
                $PreviewsPage = null;
            } else {
                $PreviewsPage = $page - 1;
            }
        }
        if (($page + 1) > $totalPage) {
            $NextPage = null;
        } else {
            $NextPage = $page + 1;
        }

        $StartItem = ($page * $item_to_show) - $item_to_show;
        if ($Totaltems > $page * $item_to_show) {
            $EndItem = $page * $item_to_show;
        } else {
            $EndItem = $StartItem + $Totaltems - (($page * $item_to_show) - $item_to_show);
        }

        $new_data = array();
        for ($i = $StartItem; $i < $EndItem; $i++) {
            array_push($new_data, $data[$i]);
        }

        $info = array(
            'totalItems' => $Totaltems,
            'StartItem' => $StartItem,
            'EndItem' => $EndItem,
            'TotalPage' => $totalPage,
            'CurrentlyPage' => $page,
            'PreviewsPage' => $PreviewsPage,
            'NextPage' => $NextPage,
            'data' => $new_data,
        );
        return $info;
    }
    return null;
}

// allow to get string token or number token
function getToken($length = 8, $type = 'string')
{
    if ($type == 'string') {
        $characters = 'cxbvjhuy78erfuwir8ycnkljafGHVHFCFGbnjvgfjfdbvgjhfuie74378321465125utrhgehfthuirfhtrghuit4hgt6juuinrjkgthh564652622656haslkdjhusghcyuabedfuhywegfbwefrheeevgfuihfih4rgterjPONJBVghnjgklnmjfkbvgjdkfbvgjdfvhdfvbfgdnhFSESOKOM230peihurfg4uihfgbvddxdcfp';
    } else if ($type == 'number') {
        $characters = '26753223687623492354782635467238453264762364383642838634865972367865734675365736237649267543675786437673675346598364785674365643876589634765345738746573478567345385932752384673583756237653745867358376237468365876347657345756783475673455675466567';
    }
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// get env value
function _env($value, $default_value)
{
    if (getenv($value)) {
        return getenv($value);
    } else {
        return $default_value;
    }
}

// validate phone
function validate_phone_number($phone)
{
    if (preg_match('/^[0-9]{10}+$/', $phone)) {
        return true;
    } else {
        return false;
    }
}

// validate email
function validate_email($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

// Validate password
function isStrong($pwd)
{
    $is_strong = true;
    if (strlen($pwd) < 7) {
        $is_strong = false;
    }
    if (!preg_match("#[0-9]+#", $pwd)) {
        $is_strong = false;
    }
    if (!preg_match("#[a-zA-Z]+#", $pwd)) {
        $is_strong = false;
    }
    return $is_strong;
}

// Dwonload file
function download($url, $dir, $file_name)
{
    $ch = curl_init();
    if (!file_exists($dir)) {
        mkdir($dir);
    }
    $downloadFile = fopen($dir . $file_name, 'w');
    if ($downloadFile) {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 65536);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_NOPROGRESS, false);
        curl_setopt($ch, CURLOPT_FILE, $downloadFile);
        curl_exec($ch);
        curl_close($ch);
    }
    return true;
}

/**
 * Get header Authorization
 * */
function getAuthorizationHeader()
{
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}
/**
 * get access token from header
 * */
function getBearerToken()
{
    $headers = getAuthorizationHeader();
// HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}
