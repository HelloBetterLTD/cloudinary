<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/11/16
 * Time: 8:49 PM
 * To change this template use File | Settings | File Templates.
 */
use \Cloudinary\Api;
use \Cloudinary\Uploader;

class CloudinaryUpload extends FormField
{

	private $children = null;
	private $imageRecord = null;
	private static $allowed_actions = array(
		'getinfo',
		'upload',
		'getLastFileData'
	);

	public function __construct($name, $title = null, $value = null) {
		parent::__construct($name, $title, $value);
		$this->addExtraClass('text _js-upload-area');
		$this->setAttribute('placeholder', 'paste the url from cloudinary or select file to upload');

	}

	public function getAttributes()
	{
		$attributes = parent::getAttributes();
		$attributes['name'] = $this->getName() . '[url]';
		return $attributes;
	}

	public function Field($properties = array())
	{

		$config = SiteConfig::current_site_config();
		$this->setAttribute('data-cloudname', $config->CloudName);
		$this->setAttribute('data-api', $config->APIKey);
		$this->setAttribute('data-preset', $config->UploadPreset);
		$this->setAttribute('data-url', $this->Link());

		Requirements::javascript('cloudinary/javascript/thirdparty/jQuery-File-Upload-master/js/jquery.ui.widget.js');
		Requirements::javascript('cloudinary/javascript/thirdparty/jQuery-File-Upload-master/js/jquery.iframe-transport.js');
		Requirements::javascript('cloudinary/javascript/thirdparty/jQuery-File-Upload-master/js/jquery.fileupload.js');

		Requirements::javascript('cloudinary/javascript/CloudinaryUpload.js');
		Requirements::css('cloudinary/css/CloudinaryUpload.css');
		return parent::Field($properties);
	}

	public function getinfo()
	{
		$api = CloudinaryFile::get_api();

		$publicID = CloudinaryFile::get_public_id($_REQUEST['cloudinary_id']);
		$type = CloudinaryFile::get_resource_type($_REQUEST['cloudinary_id']);


		$data = $api->resource(urlencode($publicID), array(
			'resource_type'		=> $type
		));
		if($data){
			$data = $data->getArrayCopy();
			return Convert::array2json($data);
		}
		return Convert::array2json(array(
			'Error'		=> 1
		));


	}


	public function saveInto(DataObjectInterface $record) {
		if($this->name) {
			$value = $this->dataValue();

			$file = null;
			if($value['id'] && (int)$value['id']){
				$file = CloudinaryFile::get()->byID($value['id']);
			}

			if(!$file){
				$file = new CloudinaryFile();
			}

			if($value['url']) {
				if(!$file->ID) {
					$info = CloudinaryFile::get_resource_type($value['url']);
					if ($info == 'image') {
						$file = new CloudinaryImage();
					}
				}

				$file->update(array(
					'CloudinaryURL' 	=> $value['url'],
					'Title' 			=> $value['title'],
					'Size' 				=> $value['size'],
					'FileName' 			=> $value['filename'],
					'ResourceType' 		=> $value['resource_type'],
					'Height' 			=> (int)$value['height'],
					'Width' 			=> (int)$value['width'],
					'Format' 			=> $value['format'],
				));

				$file->write();

				$record->setCastedField($this->name . 'ID', $file->ID);
			}
			else {
				if($file->exists()){
					$file->delete();
				}

				$record->setCastedField($this->name . 'ID', 0);
			}

		}
	}


	public function setValue($value, $record = null) {
		if(empty($value) && $record){
			if(($record instanceof DataObject) && $record->hasMethod($this->getName())) {
				$data = $record->{$this->getName()}();
				if($data && $data->exists()){
					$this->imageRecord = $data;
				}
			}

		}


		return parent::setValue($value, $record);
	}

	public function getImageRecord()
	{
		return $this->imageRecord;
	}

	public function Value()
	{
		if($this->imageRecord){
			return $this->imageRecord->CloudinaryURL;
		}
	}

	public function upload(SS_HTTPRequest $request)
	{
		$name = $this->getName();
		$postVars = $request->postVar($name);

		$tmpFileName = null;
		if(isset($postVars['tmp_name']) && isset($postVars['tmp_name']['file']) && !empty($postVars['tmp_name']['file'])) {
			$tmpFileName = $postVars['tmp_name']['file'];
		}

		if($tmpFileName && is_uploaded_file($tmpFileName)) {
			CloudinaryFile::get_api();
			$arrRet = Uploader::upload($tmpFileName);
			Session::set('public_id', $arrRet['secure_url']);
			Session::save();
		}
	}

	public function getLastFileData()
	{
		$id = Session::get('public_id');
		$_REQUEST['cloudinary_id'] = $id;
		Session::clear('public_id');
		Session::save();
		return $this->getinfo();
	}

}