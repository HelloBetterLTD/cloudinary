<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 4/11/16
 * Time: 8:36 PM
 * To change this template use File | Settings | File Templates.
 */
class CloudinaryImage extends CloudinaryFile
{

	private static $sizes = array(
		320,
		375,
		480,
		600,
		768,
		1024,
		1280,
		1400,
		1600,
		1920
	);

	private static $db = array(
		'Height'			=> 'Int',
		'Width'				=> 'Int'
	);

	public function ResponsiveImage($width, $height, $crop = 'fill', $gravity = 'faces')
	{
		$sizes = Config::inst()->get('CloudinaryImage', 'sizes');
		$bFirst = true;
		$bLast = false;


		$base = $sizes[0];
		foreach($sizes as $size){
			if($size >= $base && $size <= $width) {
				$base = $size;
			}
		}
		CloudinaryFile::get_api();

		$imageSizes = new ArrayList();
		$counter = 1;
		foreach($sizes as $size){


			$sizeWidth = (int)(($width / $base) * $size);
			$sizeHeight = (int)(($height / $width) * $sizeWidth);

			$options = array(
				'width' 	=> $sizeWidth,
				'height' 	=> $sizeHeight,
				'crop'		=> $crop,
				'quality'	=> 70,
				'gravity'	=> $gravity
			);

			$cloudinaryID = CloudinaryFile::get_public_id($this->CloudinaryURL);
			$imgURL = Cloudinary::cloudinary_url($cloudinaryID . '.' . $this->Format, $options);
			$bLast = $counter == count($sizes);


			if ($bFirst) {
				$imageSizes->push(new ArrayData(array(
					'URL'			=> $imgURL,
					'MediaQuery'	=> '(max-width: ' . $sizes[1] . 'px)'
				)));
			}
			else if ($bLast) {
				$imageSizes->push(new ArrayData(array(
					'URL'			=> $imgURL,
					'MediaQuery'	=> '(min-width: ' . ($size + 1) . 'px)'
				)));
			}
			else {
				$imageSizes->push(new ArrayData(array(
					'URL'			=> $imgURL,
					'MediaQuery'	=> '(min-width: ' . ($size + 1) . 'px) and (max-width: ' . $sizes[$counter] . 'px)'
				)));
			}

			$bFirst = false;
			$counter += 1;

		}

		$vd = new ViewableData();
		return $vd->customise(new ArrayData(array(
			'Options'		=> $imageSizes,
			'Title'			=> $this->Title,
			'Width'			=> $width,
			'Height'		=> $height
		)))->renderWith('ResponsiveImage');


	}

}