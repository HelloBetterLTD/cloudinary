<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/11/16
 * Time: 8:36 PM
 * To change this template use File | Settings | File Templates.
 */

use \Cloudinary\Api;

class CloudinaryFile extends DataObject
{

	private static $api = null;

	private static $db = array(
		'CloudinaryURL'			=> 'Varchar(500)',
		'Format'				=> 'Varchar(500)',
		'Title'					=> 'Varchar(200)',
		'Size'					=> 'Int',
		'FileName'				=> 'Varchar(200)',
		'ResourceType'		 	=> 'Varchar(200)'
	);


	public static function is_url($haystack)
	{
		return strpos($haystack, 'http://') == 0 || strpos($haystack, 'https://') == 0;
	}

	public static function get_public_id($haystack)
	{
		if(self::is_url($haystack)){
			preg_match('/^.+?\/v\d+\/(.+?)\.(?=[^.]*$)/', $haystack, $patterns);
			return isset($patterns[1]) ? $patterns[1] : '';
		}
		return $haystack;
	}

	public static function get_resource_type($haystack)
	{
		$config = SiteConfig::current_site_config();
		$cloudName = $config->CloudName;


		if(($nameStartsAt = strpos($haystack, $cloudName . '/')) >= 0){
			$haystack = substr($haystack, $nameStartsAt + strlen($cloudName . '/'));
			if(strpos($haystack, '/')){
				$haystack = substr($haystack, 0, strpos($haystack, '/'));
				return $haystack;
			}
		}

		return null;
	}

	public static function get_api ()
	{
		if(self::$api){
			return self::$api;
		}

		$config = SiteConfig::current_site_config();
		\Cloudinary::config(array(
			"cloud_name"    => $config->CloudName,
			"api_key"       => $config->APIKey,
			"api_secret"    => $config->APISecret,
		));

		self::$api = new Api();
		return self::$api;
	}

}