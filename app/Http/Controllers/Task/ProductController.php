<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use PHPUnit\Exception;

class ProductController extends Controller
{
    public function index()
    {
        $products = json_decode(Product::select('id', 'name', 'quantity', 'slider')->get(), true);
        $count = count($products);
        if (!is_null($products)) {
            for ($i = 0; $i < $count; $i++) {
                $products[$i]['slider'] = json_decode($products[$i]['slider'], true);
                if (isset($products[$i]['slider'])) {
                    for ($x = 0; $x < count($products[$i]['slider']); $x++) {
                        if (isset($products[$i]['slider'][$x]['url'])) {
                            $product_img_url = asset("images/products/sliders/" . $products[$i]['slider'][$x]['url']);
                            $products[$i]['slider'][$x]['url'] = $product_img_url;
                        }
                    }
                }
            }
        } else {
            dump('empty');
        }
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $imageNameArr = [];
        if ($request->has('slider')) {
            $image = $request->file('slider');
            foreach ($image as $index => $file) {
                $destinationPath = public_path('images/products/sliders');
                $file_name = $file->hashName();
                $file->move($destinationPath, $file_name);
                $imageNameArr[] = [
                    'title' => $data['new_title'][$index],
                    'alt' => $data['new_alt'][$index],
                    'url' => $file_name,
                ];
            }
        }
        $data['slider'] = json_encode($imageNameArr, true);
        Product::create($data);
        return redirect()->route('index')->with(['success' => 'Created successfully']);

    }

    public function edit($id, Request $request)
    {
        $product = json_decode(Product::findOrFail($id), true);
        $product['slider'] = json_decode($product['slider'], true);
        if (isset($product['slider'])) {
            for ($i = 0; $i < count($product['slider']); $i++) {
                if (isset($product['slider'][$i]['url'])) {
                    $product_img_url = asset("images/products/sliders/" . $product['slider'][$i]['url']);
                    $product['slider'][$i]['url'] = $product_img_url;
                }
            }
        }
        if (!$product)
            return redirect()->route('index')->with(['error' => 'this product id not found']);
        return view('products.edit', compact('product'));
    }

    private function updateImage(Request $request, $fileName, $oldPath)
    {
        $file = $request->file($fileName);
        if($file==null || $file->getSize()==0){
            return $oldPath;
        }
        $destinationPath = public_path('images/products/sliders/');
        $file_name = $file->hashName();
        try{
            unlink($destinationPath . $oldPath);
        }
        catch (\Exception $e){

        }
        $file->move($destinationPath, $file_name);
        return $file_name;
    }

    private function updateNewImages(Request $request, $filename){

        $images = $request->file($filename);
        if($images == null){
            return [];
        }

        foreach ($images as $key => $image) {
            $destinationPath = public_path('images/products/sliders');
            $file_name = $image->hashName();
            $image->move($destinationPath, $file_name);
            $imageNameArr[] = [
                'title' => $request->get('new_title')[$key],
                'alt' => $request->get('new_alt')[$key],
                'url' => $file_name,
            ];
        }

        return $imageNameArr;
    }

    public function update($id, Request $request)
    {
        $data = $request->all();
        $product = json_decode(Product::findOrFail($id), true);
        if (!$product) {
            return redirect()->route('index')->with(['error' => 'this product id not found']);
        }
        $imageNameArr = [];
        $product['slider'] = json_decode($product['slider'], true);
        $newSlider = [];

        foreach($product["slider"] as $key=>$sliderItem){
            if($request->get("check_exist_$key")!=null) {
                $newSlider[] = [
                    "url" => $this->updateImage($request, 'slider_' . $key, $product['slider'][$key]['url']),
                    "title" => $request->get("old_title_$key"),
                    "alt" => $request->get("old_alt_$key"),
                ];
            }
        }

        $newImages  = $this->updateNewImages($request, 'new_slider');
        if(count($newImages)){
            $newSlider = array_merge($newSlider, $newImages);
        }
        $updateData = [
            'name' => $data['name'],
            'quantity' => $data['quantity'],
            'updated_at' => now(),
            "slider" => json_encode($newSlider)
        ];



        Product::where('id', $id)->update($updateData);

        return redirect()->route('index')->with(['success' => 'Updated successfully']);

    }

}


