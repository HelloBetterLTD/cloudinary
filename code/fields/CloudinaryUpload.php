<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/11/16
 * Time: 8:49 PM
 * To change this template use File | Settings | File Templates.
 */
class CloudinaryUpload extends FormField
{

	private $children = null;
	private static $allowed_actions = array(
		'getinfo'
	);

	public function __construct($name, $title = null, $value = null) {
		parent::__construct($name, $title, $value);
		$this->addExtraClass('text _js-upload-area');
		$this->setAttribute('placeholder', 'paste the public id from cloudinary or drag and drop file');

	}

	public function Field($properties = array())
	{

		$config = SiteConfig::current_site_config();
		$this->setAttribute('data-cloudname', $config->CloudName);
		$this->setAttribute('data-api', $config->APIKey);
		$this->setAttribute('data-preset', $config->UploadPreset);
		$this->setAttribute('data-url', $this->Link());


		Requirements::javascript('cloudinary/javascript/CloudinaryUpload.js');
		Requirements::css('cloudinary/css/CloudinaryUpload.css');
		return parent::Field($properties);
	}

	public function getinfo()
	{
		$publicID = CloudinaryFile::get_public_id($_REQUEST['cloudinary_id']);
		$api = CloudinaryFile::get_api();

		$data = $api->resource($publicID);
		return Convert::array2json($data);
	}


}