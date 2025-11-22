<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function ContactForm(Request $request)
    {

        $validatedMessage = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);
        $message = new Support();
        $message->name = $validatedMessage['name'];
        $message->email = $validatedMessage['email'];
        $message->subject = $validatedMessage['subject'];
        $message->message = $validatedMessage['message'];
        $message->save();

        $notification = array(
            'message' => 'Your mail has been sent successfully!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }
}
