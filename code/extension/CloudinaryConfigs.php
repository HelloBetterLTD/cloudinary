<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/11/16
 * Time: 8:33 PM
 * To change this template use File | Settings | File Templates.
 */
class CloudinaryConfigs extends DataExtension
{

	private static $db = array(
		'CloudName'				=> 'Varchar(100)',
		'APIKey'				=> 'Varchar(100)',
		'APISecret'				=> 'Varchar(100)',
		'UploadPreset'			=> 'Varchar(100)'
	);

	public function updateCMSFields(FieldList $fields)
	{
		$fields->addFieldsToTab('Root.Settings.Cloudinary', array(
			TextField::create('CloudName'),
			TextField::create('APIKey'),
			TextField::create('APISecret'),
			TextField::create('UploadPreset')
		));
	}

}