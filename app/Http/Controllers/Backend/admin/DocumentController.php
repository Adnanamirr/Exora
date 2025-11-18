<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneratedContent;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function AdminDocument(){
        // $id = Auth::user()->id;
        $document = GeneratedContent::orderBy('id','desc')->get();
        return view('admin.backend.document.all_document',compact('document'));
    }

    public function EditAdminDocument($id){
        $document = GeneratedContent::findOrFail($id);
        return view('admin.backend.document.edit_document',compact('document'));
    }
    public function AdminUpdateDocument(Request $request,$id){
        $document = GeneratedContent::findOrFail($id);
        $validatedData = $request->validate([
            'output' => 'required|string'
        ]);
        $document->update([
            'output' => $validatedData['output']
        ]);
        $notification = array(
            'message' => 'Document Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.document')->with($notification);
    }

    public function DeleteAdminDocument($id){

        GeneratedContent::find($id)->delete();
        $notification = array(
            'message' => 'Document Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
