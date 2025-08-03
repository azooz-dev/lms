<?php

function resizeAndSaveImage($image, $width, $height, $destinationPath)
{
  $tempInputPath = $image->getRealPath();
  $resizeCommand = "magick convert " . escapeshellarg($tempInputPath) .
    " -resize {$width}x{$height}^ -gravity center -extent {$width}x{$height} " .
    escapeshellarg($destinationPath);

  $output = exec($resizeCommand, $output, $returnVar);


  if ($returnVar !== 0 || !file_exists($destinationPath)) {
    throw new \Exception('Unable to resize image');
  }
}
