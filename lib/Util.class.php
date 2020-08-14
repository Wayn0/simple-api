<?php

class Util 
{
	/*
	 * Respond with JSON
	 *
	 * @param int $length Length of string to generate
	 * @param bool $special	Include special charachters in string
	 *
	 * @return string
	 */
	public static function
    errorResponseJSON($message,$http_response)
	{
		$json_data['success'] = false;
		$json_data['error'] = $message;
		header('Content-Type: application/json; charset=utf-8;');
		header("HTTP/1.1 " . $http_response);
		echo json_encode($json_data);
    }

	/*
	 * Respond with JSON
	 *
	 * @param int $length Length of string to generate
	 * @param bool $special	Include special charachters in string
	 *
	 * @return string
	 */
	public static function responseJSON($message, $array = null)
	{
		$json_data['success'] = true;
		$json_data['msg'] = $message;
		if($array != null) {
			foreach($array as $key => $value) {
				$json_data[$key] = $value;
			}
		}
		header('Content-Type: application/json; charset=utf-8;');
		header("HTTP/1.1 200 Ok");
		echo json_encode($json_data);
	}

    public static function bytesSize($size)
    {
        $unit = array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
}