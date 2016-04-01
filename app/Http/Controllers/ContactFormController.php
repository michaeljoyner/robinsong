<?php

namespace App\Http\Controllers;

use App\Mailing\AdminMailer;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ContactFormController extends Controller
{
    public function showPage()
    {
        return view('front.pages.contact');
    }

    public function sendMessage(Request $request, AdminMailer $mailer)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'enquiry' => 'required'
        ]);

        $mailer->sendSiteMessage($request->only(['name', 'email', 'enquiry']));

        return response('ok');
    }
}
