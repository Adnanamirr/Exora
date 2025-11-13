<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\plan;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    public function AllPlans(){
        $plan = Plan::latest()->get();
        return view('admin.backend.plan.all_plans', compact('plan'));
    }

    public function AddPlan(){
        return view('admin.backend.plan.add_plan');
    }

    public function StorePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'monthly_word_limit' => 'required|integer',
            'price' => 'nullable|numeric',
            'templates' => 'nullable|string'
        ]);

        Plan::create([
            'name' => $request->name,
            'monthly_word_limit' => $request->monthly_word_limit,
            'price' => $request->price,
            'templates' => $request->templates,
        ]);

        $notification = array(
            'message' => 'Plan Created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.plans')->with($notification);
    }

    public function EditPlan($id)
    {
        $plan = Plan::findOrFail($id);
        return view('admin.backend.plan.edit_plan', compact('plan'));
    }


    public function UpdatePlan(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'monthly_word_limit' => 'required|integer',
            'price' => 'nullable|numeric',
            'templates' => 'nullable|string'
        ]);

        Plan::findOrFail($id)->update([
            'name' => $request->name,
            'monthly_word_limit' => $request->monthly_word_limit,
            'price' => $request->price,
            'templates' => $request->templates,
        ]);

        $notification = array(
            'message' => 'Plan Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.plans')->with($notification);
    }


    public function Destroy($id)
    {
        Plan::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Plan Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
