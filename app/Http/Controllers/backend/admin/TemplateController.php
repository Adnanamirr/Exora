<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    //
    public function AllTemplates()
    {
        $templates = Template::latest()->get();
        return view('admin.backend.template.all-template' , compact('templates'));

    }
    public function AddTemplate(){
        return view('admin.backend.template.add-template');
    }

    public function StoreTemplate(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'prompt' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $template = Template::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'icon' => $request->icon,
            'prompt' => $request->prompt,
            'is_active' => $request->is_active ?? true,
            'created_by' => auth()->id()
        ]);


        $notification = array(
            'message' => 'Template Created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.templates')->with($notification);
    }

}
