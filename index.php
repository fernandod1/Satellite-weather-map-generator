<?php
/*

MODIFY FOLLOWING LINES TO CONFIGURE SOURCES:

*/

function data_weather_provider($source){	
	if($source==1){	
		$data=array('urlimage' => 'https://dsx.weather.com/util/image/map/DCT_SPECIAL11_1280x720.jpg',
					'pathimages' => '/home/youracccount/public_html/weather/images1/',
					'outputgif' => 'animation1.gif',
					'width' => '1280',
					'height' => '720');

	} else if($source==2){
		$data=array('urlimage' => 'http://rammb.cira.colostate.edu/ramsdis/online/images/latest/tropical/tropical_ge_4km_ir4_floater_2.gif',
					'pathimages' => '/home/youracccount/public_html/weather/images2/',
					'outputgif' =>'animation2.gif',
					'width' => '640',
					'height' => '480');
					
	} else if($source==3){
		$data=array('urlimage' => 'https://weather.msfc.nasa.gov/cgi-bin/get-abi?satellite=GOESEastfullDiskband13&palette=ir2.pal&lat=16&lon=-62&type=Image&width=640&height=480&zoom=1&quality=50&map=standard',
					'pathimages' => '/home/youracccount/public_html/weather/images3/',
					'outputgif' =>'animation3.gif',
					'width' => '640',
					'height' => '480');
	}
	return $data;
}


/*********************  DO NOT MODIFY UNDER THIS LINE ************************/


function download_curl($url,$output_filename){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.12011-10-16 20:23:00");
	curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	$result = curl_exec($ch);
	curl_close($ch);
	$fp = fopen($output_filename, 'wb');
	fwrite($fp, $result);
	fclose($fp);
}

function count_files_in_dir($dir){
	$total=0;
	$dir=$dir.'*';
	$dir=glob($dir);
	foreach($dir as $file) {
	  if(is_file($file)) {
		$total++;
	  }
	}
	return $total;
}

function remove_old_files($dir){
	$totalfiles=count_files_in_dir($dir);	
	if ($totalfiles > 9 ) {
		$files = glob( $dir.'*.*' );
		array_multisort(
			array_map( 'filemtime', $files ),
			SORT_NUMERIC,
			SORT_ASC,
			$files
		);
		unlink($files[0]); // remove oldest file
	}
}

function create_animation($data){
	$i=0;
	$delay=array(30,30,30,30,30,30,30,30,30,300);
	$dir=$data[pathimages].'*';
	$dir=glob($dir);
	$animation = new Imagick();
	$animation->setFormat("GIF");
	foreach($dir as $file) {
		if(is_file($file)) {
		    $frame = new Imagick($file);
			$frame->thumbnailImage($data[width], $data[height]);
			$animation->addImage($frame);
			$animation->setImageDelay($delay[$i]);
			$animation->nextImage();
			$i++;
		}		
	}	
	$animation->writeImages('./'.$data[outputgif], true);	
}

function convert_gif_jpg_and_move($data){
	download_curl($data[urlimage],'temporary.gif');
	$i = new IMagick('temporary.gif');
	$i->setImageBackgroundColor(new ImagickPixel('white'));
	$i = $i->flattenImages();
	$i->setImageFormat('jpg');
	$i->writeImage('temporary.jpg');	
	rename('temporary.jpg',$data[pathimages].date("YmdHis").'.jpg');
	unlink('temporary.gif');
	unlink('temporary.jpg');	
}

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

/******************************* MAIN PROGRAM **********************************/


$op=explode('TIPO',$_GET['op']);
if(($op[0]=='cronjob')&&(isset($op[1]))){
	$data=data_weather_provider($op[1]);
	remove_old_files($data[pathimages]);	
	if($op[1]==2){ // origin is a gif image, case 2:
		download_curl($data[urlimage],'temporary.gif');
		convert_gif_jpg_and_move($data);	
	}else if($op[1]==3){ // origin is a jpg displayed by cgi script, case 3:
		$html=file_get_contents($data[urlimage]);
		$data[urlimage]="https://weather.msfc.nasa.gov/goes/abi/dynamic/".get_string_between($html, '<IMG SRC="/goes/abi/dynamic/', '" WIDTH="640" HEIGHT="480">');
		download_curl($data[urlimage],$data[pathimages].date("YmdHis").'.jpg');	
	}else{		
		download_curl($data[urlimage],$data[pathimages].date("YmdHis").'.jpg');	
	}
	create_animation($data);	
	echo 'New animation created';
}
else if(isset($_GET["tipo"])){	
	$data=data_weather_provider($_GET["tipo"]);
	header("Content-type: image/gif");
	echo file_get_contents($data[outputgif]);
}


 ?>