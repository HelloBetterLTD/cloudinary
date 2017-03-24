<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 3/24/17
 * Time: 9:14 PM
 * To change this template use File | Settings | File Templates.
 */
class MigrateAssetsCloudinary extends BuildTask
{

	protected $title = 'Migrate assets to cloudinary';
	protected $description = 'Migrate assets to cloudinary';


	public function run($request)
	{
		$files = File::get()->exclude('ClassName', 'Folder');
		$api = CloudinaryFile::get_api();

		foreach($files as $file) {
			if($file->exists()) {
				$path = $file->getFullPath();
				$info = \Cloudinary\Uploader::upload($path);

				$newFile = new CloudinaryFile();
				if($info['resource_type'] == 'image'){
					$newFile = new CloudinaryImage();
				}
				$newFile->update(array(
					'Title'					=> $file->Title,
					'CloudinaryURL'			=> $info['secure_url'],
					'Format'				=> $info['format'],
					'Size'					=> $info['bytes'],
					'FileName'				=> $info['original_filename'],
					'ResourceType'		 	=> $info['resource_type']
				));
				if($info['resource_type'] == 'image'){
					$newFile->Width = $info['width'];
					$newFile->Height = $info['height'];
				}
				$newFile->write();

				try {
					DB::query('UPDATE `CloudinaryFile` SET ID = ' . $file->ID . ' WHERE ID = ' . $newFile->ID);
					DB::query('UPDATE `CloudinaryImage` SET ID = ' . $file->ID . ' WHERE ID = ' . $newFile->ID);

					DB::query('ALTER TABLE `CloudinaryFile` AUTO_INCREMENT = ' . ($file->ID + 1));
					DB::query('ALTER TABLE `CloudinaryImage` AUTO_INCREMENT = ' . ($file->ID + 1));
					
				} catch(Exception $e) {}
				echo $file->ID . ' - ' . $file->getTitle() . '<br>';


			}
		}
		echo "Importing finished";
		die();

	}

}