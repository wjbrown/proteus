<?php
require __DIR__ . '/../src/Image.php';
require __DIR__ . '/../src/ImageCache.php';

$imgcache = new \Proteus\ImageCache([
    'path'         => dirname(__FILE__) . '/img/cache',
]);

$request           = new StdClass();
$request->filename = 'saturn.png';
$request->query    = [
    'resize' => 'thumbnail',
    'w'      => '400',
    'h'      => '400',
    'g'      => 'ne'
];

// remember, the $img here is something that could come from the callback OR a file.  its basically a string.
$cachekey = sha1(json_encode($request));
$img = $imgcache->remember($cachekey, function() use ($request) {

    $img = new \Proteus\Image('img/' . $request->filename);
    $img->resize($request->query['w'], $request->query['h']);
    return $img;    // return the blob that is going to be cached

});

header('Content-type: ' . \Proteus\Image::getContentType($img));
header('Cache-Control: max-age=324555');
echo $img;

