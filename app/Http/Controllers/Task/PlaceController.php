<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Traits\SliderTrait;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    use SliderTrait;


    public function index()
    {
        try {
            $places = json_decode(Place::select('id', 'name', 'description', 'slider')->get(), true);
            $count = count($places);
            for ($i = 0; $i < $count; $i++) {
                $places[$i]['slider'] = json_decode($places[$i]['slider'], true);
                if (isset($places[$i]['slider'])) {
                    for ($x = 0; $x < count($places[$i]['slider']); $x++) {
                        if (isset($places[$i]['slider'][$x]['url'])) {
                            $product_img_url = asset("images/places/sliders/" . $places[$i]['slider'][$x]['url']);
                            $places[$i]['slider'][$x]['url'] = $product_img_url;
                        }
                    }
                }
            }
            return view('places.index', compact('places'));
        } catch (\Exception $e) {
        }
    }
    public function create()
    {
        try {
            return view('places.create');
        } catch (\Exception $e) {

        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'description' => 'sometimes',
            ]);
            $slider = $this->UploadNewSlider($request, 'slider', public_path('images/places/sliders'));
            $data['slider'] = json_encode($slider, true);
            Place::create($data);
            return redirect()->route('places.index')->with(['success' => 'Created successfully']);
        } catch (\Exception $e) {

        }
    }
    public function edit($id)
    {
        try {
            $place = json_decode(Place::findOrFail($id), true);
            $place['slider'] = json_decode($place['slider'], true);
            if (isset($place['slider'])) {
                for ($i = 0; $i < count($place['slider']); $i++) {
                    if (isset($place['slider'][$i]['url'])) {
                        $place_img_url = asset("images/places/sliders/" . $place['slider'][$i]['url']);
                        $place['slider'][$i]['url'] = $place_img_url;
                    }
                }
            }
            if (!$place)
                return redirect()->route('places.index')->with(['error' => 'this place id not found']);
            return view('places.edit', compact('place'));
        } catch (\Exception $e) {

        }
    }
    public function update($id, Request $request)
    {
        try {
            $imageNameArr = [];
            $newSlider = [];
            $place = json_decode(Place::findOrFail($id), true);
            if (!$place) {
                return redirect()->route('places.index')->with(['error' => 'this product id not found']);
            }

            $data = $request->validate([
                'name' => 'required|string',
                'description' => 'sometimes',
            ]);
            $place['slider'] = json_decode($place['slider'], true);
            foreach ($place['slider'] as $key => $sliderItem) {
                if ($request->get("check_exist_$key") != null) {
                    $imageNameArr[] = [
                        "url" => $this->updateSliderExisting($request, 'slider_' . $key, $place['slider'][$key]['url'], public_path('images/places/sliders/')),
                        "title" => $request->get("old_title_$key"),
                        "alt" => $request->get("old_alt_$key"),
                    ];
                }
            }
            $newImages = $this->uploadNewSlider($request, 'new_slider', public_path('images/places/sliders'));
            if (count($newImages) >= 0) {
                $newSlider = array_merge($imageNameArr, $newImages);
            }
            $updateData = [
                'name' => $data['name'],
                'description' => $data['description'],
                'slider' => json_encode($newSlider, true),
                'updated_at' => now(),
            ];
            Place::where('id', $id)->update($updateData);
            return redirect()->route('places.index')->with(['success' => 'Updated successfully']);
        } catch (\Exception $e) {

        }
    }
    public function delete($id)
    {
        try {
            $place = Place::findOrFail($id);

            if (!$place) {
                return redirect()->route('places.index')->with(['error' => 'this product id not found']);
            }
            $place->slider = json_decode($place->slider, true);
            if (isset($place->slider)) {
                for ($i = 0; $i < count($place->slider); $i++) {
                    if (isset($place->slider[$i]['url'])) {
                        unlink(public_path("images/places/sliders/" . $place->slider[$i]['url']));
                    }
                }
            }
            $place->delete();
            return redirect()->route('places.index')->with(['success' => 'Deleted Successfully']);
        } catch (\Exception $ex) {
            return redirect()->route('places.index')->with(['error' => 'Deleted Failed']);
        }
    }

}
