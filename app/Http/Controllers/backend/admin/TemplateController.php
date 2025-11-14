<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateInputFields;
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
            'category' => 'required|string|in:ads,articles,blog_post,ecommerce,website,social_media,marketing,email',
            'icon' => 'nullable|string|max:255',
            'prompt' => 'required|string',
            'is_active' => 'boolean',
            'input_fields' => 'required|array|size:1',
            'input_fields.*.title' => 'required|string|max:255',
            'input_fields.*.description' => 'required|string',
            'input_fields.*.type' => 'required|string|in:text,textarea',
        ]);
        // Create new template from validated data
        $template = new Template();
        $template->title = $validated['title'];
        $template->description = $validated['description'];
        $template->category = $validated['category'];
        $template->icon = $validated['icon'];
        $template->prompt = $validated['prompt'];
        $template->is_active = $validated['is_active'] ;
        $template->created_by = auth()->id();
        $template->save();


        $input_fields = $validated['input_fields'][0];
        TemplateInputFields::create([
            'template_id' => $template->id,
            'title' => $input_fields['title'],
            'description' => $input_fields['description'],
            'type' => $input_fields['type'],
        ]);

        $notification = array(
            'message' => 'Template Created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.templates')->with($notification);
    }

    public function EditTemplate($id)
    {
        $template = Template::with('inputFields')->findOrFail($id);
        return view('admin.backend.template.edit-template' , compact('template'));
    }

    public function UpdateTemplate(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:ads,articles,blog_post,ecommerce,website,social_media,marketing,email',
            'icon' => 'nullable|string|max:255',
            'prompt' => 'required|string',
            'is_active' => 'boolean',
            'input_fields' => 'required|array|size:1',
            'input_fields.*.title' => 'required|string|max:255',
            'input_fields.*.description' => 'required|string',
            'input_fields.*.type' => 'required|string|in:text,textarea',
        ]);

        Template::findOrFail($id)->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'icon' => $validated['icon'],
            'prompt' => $validated['prompt'],
            'is_active' => $validated['is_active'],
        ]);

        $input_fields = $validated['input_fields'][0];
        TemplateInputFields::where('template_id', $id)->update($input_fields);

        $notification = array(
            'message' => 'Template Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.templates')->with($notification);
    }


    public function TemplateDetails($id)
    {
        $template = Template::with('inputFields')->findOrFail($id);
        $user = auth()->user();
        return view('admin.backend.template.template-details' , compact('template' , 'user'));

    }

}
