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
}
