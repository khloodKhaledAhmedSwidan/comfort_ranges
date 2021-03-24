<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Sentence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SentenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sentences = Sentence::get();
        return view('admin.sentences.index', compact('sentences'));
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
        return view('admin.sentences.create');

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
            'sentence' => 'required|max:255',
            'sentence_ar' => 'required|max:255',

        ]);
        $sentence = Sentence::create([
            'sentence' => $request->sentence,
            'sentence_ar' => $request->sentence_ar,


        ]);
        flash(app()->getLocale() == 'en' ? 'added successfully' : 'تم الاضافه بنجاح')->success();
        return redirect('admin/sentences');
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
        $sentence = Sentence::find($id);
        return view('admin.sentences.edit', compact('sentence'));
        //
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
        //
        $this->validate($request, [
            'sentence' => 'max:255',
            'sentence_ar' => 'max:255',

        ]);

        $sentence = Sentence::findOrFail($id);
        $sentence->update($request->all());

        flash(app()->getLocale() == 'en' ? 'updated successfully' : 'تم التعديل بنجاح')->success();
        return redirect('admin/sentences');
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
        $sentence = Sentence::find($id);
        if ($sentence->rates()->count() > 0) {
            flash(app()->getLocale() == 'en' ? 'This sentence cannot be cleared, used' : 'لا يمكن مسح هذه الجملة , مستخدمه ')->error();
            return redirect('admin/sentences');
        } else {
            $sentence->delete();
            flash(app()->getLocale() == 'en' ? 'deleted successfully' : 'تم الحذف بنجاح')->success();
            return redirect('admin/sentences');
        }
    }
}
