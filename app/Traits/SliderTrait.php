<?php

namespace App\Traits;

trait SliderTrait
{
    public function uploadNewSlider($request, $fileName, $path)
    {
        if ($request->has($fileName)) {
            $files = $request->file($fileName);
            foreach ($files as $key => $file) {
                $destinationPath = $path;
                $hashName = $file->hashName();
                $file->move($destinationPath, $hashName);
                $imageNameArr[] = [
                    'title' => $request->get('new_title')[$key],
                    'alt' => $request->get('new_alt')[$key],
                    'url' => $hashName,
                ];
            }
            return $imageNameArr;
        } else {
            return [];
        }
    }
    public function updateSliderExisting($request, $fileName, $oldPath,$path)
    {
        $file = $request->file($fileName);
        if ($file == null || $file->getSize() == 0) {
            return $oldPath;
        }
        $destinationPath = $path;
        $file_name = $file->hashName();
        try {
            unlink($destinationPath . $oldPath);
        } catch (\Exception $e) {
        }
        $file->move($destinationPath, $file_name);
        return $file_name;
    }



    
}
