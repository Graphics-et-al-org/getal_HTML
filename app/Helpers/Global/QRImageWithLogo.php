<?php
/**
 * GdImage with logo output example
 *
 * @created      18.11.2020
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2020 smiley
 * @license      MIT
 *
 * @noinspection PhpComposerExtensionStubsInspection, PhpIllegalPsrClassPathInspection
 */
namespace App\Helpers\Global;

use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\{QRGdImagePNG, QRCodeOutputException};
use Intervention\Image\ImageManager;
use Intervention\Image\Image;
use Intervention\Image\Drivers\Gd\Driver;

//require_once __DIR__.'/../vendor/autoload.php';

/*
 * Class definition
 */

class QRImageWithLogo extends \chillerlan\QRCode\Output\QRGdImagePNG{

	/**
	 * @param string|null $file
	 * @param string|null $logo
	 *
	 * @return string
	 * @throws \chillerlan\QRCode\Output\QRCodeOutputException
	 */
	public function dump(string $file = null, Image $logo = null){

        return parent::dump();
		// set returnResource to true to skip further processing for now
		$this->options->returnResource = true;

        //$manager = new ImageManager(new Driver());

		// of course, you could accept other formats too (such as resource or Imagick)
		// I'm not checking for the file type either for simplicity reasons (assuming PNG)
       // dd(is_readable($logo));
		// if(!is_file($logo) || !is_readable($logo)){
        //     //dd($logo);
		// 	throw new QRCodeOutputException('invalid logo');
		// }

		// there's no need to save the result of dump() into $this->image here
		//parent::dump($file);

		//$im = imagecreatefromstring($logo);
        //dd($logo);

		// get logo image size
		$w = $logo->width();//imagesx($im);
		$h = $logo->height();

//dd($this->image);

		// set new logo size, leave a border of 1 module (no proportional resize/centering)
		$lw = (($this->options->logoSpaceWidth - 2) * $this->options->scale);
		$lh = (($this->options->logoSpaceHeight - 2) * $this->options->scale);

		// get the qrcode size
		$ql = ($this->matrix->getSize() * $this->options->scale);
        dd($ql);

		// scale the logo and copy it over. done!
        $manager = new ImageManager(new Driver());
		imagecopyresampled($this->image, $im, (($ql - $lw) / 2), (($ql - $lh) / 2), 0, 0, $lw, $lh, $w, $h);

		$imageData = $this->dumpImage();

		//$this->saveToFile($imageData, $file);

		if($this->options->outputBase64){
			$imageData = $this->toBase64DataURI($imageData);
		}

		return $imageData;
	}

}


/*
 * Runtime
 */

// $options = new QROptions;

// $options->version             = 5;
// $options->outputBase64        = false;
// $options->scale               = 6;
// $options->imageTransparent    = false;
// $options->drawCircularModules = true;
// $options->circleRadius        = 0.45;
// $options->keepAsSquare        = [
// 	QRMatrix::M_FINDER,
// 	QRMatrix::M_FINDER_DOT,
// ];
// // ecc level H is required for logo space
// $options->eccLevel            = EccLevel::H;
// $options->addLogoSpace        = true;
// $options->logoSpaceWidth      = 13;
// $options->logoSpaceHeight     = 13;


// $qrcode = new QRCode($options);
// $qrcode->addByteSegment('https://github.com');

// $qrOutputInterface = new QRImageWithLogo($options, $qrcode->getQRMatrix());

// // dump the output, with an additional logo
// // the logo could also be supplied via the options, see the svgWithLogo example
// $out = $qrOutputInterface->dump(null, __DIR__.'/octocat.png');


// header('Content-type: image/png');

// echo $out;

// exit;
