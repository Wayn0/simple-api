<?php

Class Request
{
	/**
	 * A database connection tha is responsible for all data
	 * persistence and retrieval.
	 *
	 * @var PDO
	 */
	protected $db = null;

	/**
	 * The logger that handles writing logs to file.
	 * @var Log
	 */
    protected $log = null;


    protected $server = "";
    protected $method = "";
    protected $url = "";

    protected $headers = array();
    protected $post = array();
    protected $get = array();
    protected $filtered_post = array();

    protected $time  = 0;

	/**
	 * Class constructor
	 * @param array
	 * @param
	 * @return void
	 **/
    public function __construct()
    {
        foreach($params as $key => $value) {
			$this->$key = $value;
        }

        $this->setServer();
        $this->setHeaders();
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->post = $_POST;
        $this->filterPost();
        $this->get = $_GET;
        $this->url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_STRING);
        $this->time = time() - $_SERVER['REQUEST_TIME'];
    }

    private function setServer()
    {
        $this->server = explode("/",$_SERVER['SERVER_SOFTWARE'])[0];
    }

    private function setHeaders()
    {
        if($this->server == "Apache") {
            $this->headers = apache_request_headers();
        } else {
            $headers = array();
            $exceptions = array(
                'CONTENT_TYPE'   => 'Content-Type',
                'CONTENT_LENGTH' => 'Content-Length',
                'CONTENT_MD5'    => 'Content-Md5',
            );
            foreach ($_SERVER as $key => $value) {
                if (substr($key, 0, 5) === 'HTTP_') {
                    $key = substr($key, 5);
                    if (!isset($exceptions[$key]) || !isset($_SERVER[$key])) {
                        $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                        $headers[$key] = $value;
                    }
    
                } else if (isset($exceptions[$key])){
                    $headers[$exceptions[$key]] = $value;
                }
            }
            $this->headers = $headers;
        }
    }

    private function filterPost()
    {
        // remove passwords from the post variable
        // This is purely for debugging post requests
        $this->filtered_post = $this->post;

        if (array_key_exists("password", $this->filtered_post)) {
            $this->filtered_post["password"]= "**************";
        }
        if (array_key_exists("password1", $this->filtered_post)) {
            $this->filtered_post["password1"]= "**************";
        }
        if (array_key_exists("password2", $this->filtered_post)) {
            $this->filtered_post["password2"]= "**************";
        }
    }

    public function hasHeader(string $key) : bool
    {
        if (array_key_exists($key, $this->headers)) {
            return true;
        }
    
        return false;
    }

    public function getHeader(string $key) : string
    {
        if (array_key_exists($key, $this->headers)) {
            return $this->headers[$key];
        } else {
            return '';
        }
    }

    public function getPost(string $key,string $type)
    {
        if (array_key_exists($key, $this->post)) {
            
            switch($type) {

                case "int":
                case "integer":
                    return filter_var($this->post[$key], FILTER_SANITIZE_NUMBER_INT);
                    break;

                case "double":
                case "float":
                    return filter_var($this->post[$key], FILTER_SANITIZE_NUMBER_FLOAT);
                    break;

                case "str":
                case "string":
                    return filter_var($this->post[$key], FILTER_SANITIZE_STRING);
                    break;

                case "email":
                    return filter_var($this->post[$key], FILTER_SANITIZE_EMAIL);
                    break;

                default:
                    return false;
            }
        } else {
            return false;
        }
    }

    public function hasValidJWT() : bool
    {
        if($this->hasHeader("Authorization")) {

            $authHeader = $this->getHeader("Authorization");
            if(strpos($auth," ")) {
                $jwt = explode(" ",$authHeader)[1];
                try {
					$secretKey = base64_decode(JWT_SECRET);
                    $token     = \Firebase\JWT\JWT::decode($jwt, $secretKey, array('HS512'));
                    return true;

				} catch (Exception $e) {
					return false;
				}
            }
        }
        return false;
    }
}