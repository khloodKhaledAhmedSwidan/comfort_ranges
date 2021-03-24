<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::all();
        return view('admin.sliders.index', compact('sliders'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'link' => 'sometimes|max:225',
            'category_id' => 'sometimes',
            "image" => 'required|mimes:jpeg,bmp,png,jpg,gif,ico,psd,webp,tif,tiff|max:5000',
        ]);
        if ($request->category_id == null && $request->link == null)
        {
            flash(app()->getLocale() == 'en'?'you must enter link or service':'يجب ادخال لينك او خدمة ')->info();
            return  back();
        }
        elseif ($request->category_id != null && $request->link != null )
        {
            flash(app()->getLocale() == 'en'?'you can not enter link and service in the sametime':'لا يمكن ادخال اللينك والخدمه في ان واحد')->info();
            return  back();
        }
        $slider = Slider::create([
            'image' => UploadImage($request->image, 'slider', 'uploads/sliders'),
            'link' => $request->link,
            'category_id' => $request->category_id,

        ]);
        flash(app()->getLocale() == 'en' ? 'created successfully' : 'تم الاضافه بنجاح')->success();
        return redirect('admin/sliders');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $slider = Slider::find($id);
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
            $this->validate($request , [
                'link' => 'sometimes|max:225',
                'category_id' => 'sometimes',
                "image" => 'sometimes|mimes:jpeg,bmp,png,jpg,gif,ico,psd,webp,tif,tiff|max:5000',
                ]);
        if ($request->category_id == null && $request->link == null)
        {
            flash(app()->getLocale() == 'en'?'you must enter link or service':'يجب ادخال لينك او خدمة ')->info();
            return  back();
        }elseif ($request->category_id != null && $request->link != null)
        {
            flash(app()->getLocale() == 'en'?'you can not enter link and service in the sametime':'لا يمكن ادخال اللينك والخدمه في ان واحد')->info();
            return  back();
        }
        $slider = Slider::find($id);
        $oldImage = $slider->image;
        $slider->image = $request->image != null ? UploadImageEdit($request->image, 'slider', 'uploads/sliders', $oldImage) : $slider->image;
        $slider->link = $request->link;
        $slider->category_id = $request->category_id;
        $slider->save();
        flash(app()->getLocale() == 'en' ? 'updated successfully' : 'تم التعديل بنجاح')->success();
        return redirect('admin/sliders');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $slider = Slider::find($id);

        @unlink(public_path('/' . 'uploads/sliders' . '/' . $slider->image));
        $slider->delete();
        flash(app()->getLocale() == 'en' ? 'deleted successfully' : 'تم الحذف بنجاح', 'success');
        return redirect('admin/sliders');

    }
}
