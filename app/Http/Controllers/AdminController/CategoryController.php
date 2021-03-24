<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::orderBy('arranging', 'asc')->get();
        return  view('admin.categories.index',compact('categories'));

    }
    public function is_active(Request $request, $id)
    {

        if ($request->ajax()) {
            $category = Category::findOrfail($id);
            if ($category->active == 1) {
                $category->active = 0;
                $category->save();

            } else {
                $category->active = 1;
                $category->save();
            }

            return 'true';
        }


    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return  view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //


        $this->validate($request, [
            'name'                 => 'required|max:255',
            'name_ar'                  => 'required|max:255',
            'arranging' => 'required|unique:categories|numeric',
            'image' => 'required|mimes:jpeg,jpg,png|max:3000|image',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $category = Category::create([
            'name'          => $request->name,
            'name_ar'           => $request->name_ar,
            'arranging'           => $request->arranging,
            'image'         => UploadImage($request->image,'category','uploads/categories'),
            'branch_id'    =>$request->branch_id,

        ]);
        flash(app()->getLocale() == 'en'?'created successfully':'تم إنشاء الخدمة بنجاح')->success();
        return redirect('admin/categories');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $category = Category::find($id);
        return view('admin.categories.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $category =Category::find($id);
        $this->validate($request, [
            'arranging' => 'unique:categories,arranging,'.$id,

        ]);

        $category->update($request->except('image'));
        if($request->arranging){
            $category->arranging = $request->arranging;
            $category->save();
        }
        $oldImage = $category->image;
    $category->image =    $request->image != null ?UploadImageEdit($request->image,'category','uploads/categories',$oldImage):$category->image;
        $category->save();
        flash(app()->getLocale() == 'en'?'updated successfully':'تم التعديل بنجاح')->success();
        return redirect('admin/categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $category = Category::find($id);
        if($category->users()->count() >0 || $category->orders()->count() >0){
            flash(app()->getLocale()=='en'?'This service cannot be deleted, it contains employees':' لا يمكن حذف هذه الخدمة ,تحتوي علي موظفين','success');
            return redirect('admin/categories');
        }else{
            @unlink(public_path('/'.'uploads/categories'.'/'.$category->image));
            $category->delete();
            flash(app()->getLocale() == 'en'?'deleted successfully':'تم الحذف بنجاح','success');
            return redirect('admin/categories');

        }
    }
}
