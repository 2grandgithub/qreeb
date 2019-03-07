<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send(Request $request)
    {
        $this->validate($request,
            [
                'email' => 'required|email',
                'subject' => 'required',
                'text' => 'required'
            ],
            [
                'email.required' => 'Sorry,email is required',
                'email.email' => 'Sorry,email is invalid',
                'subject.required' => 'Sorry,the email subject is required',
                'text.required' => 'Sorry,the email content is required'
            ]
        );

        $dataEmail = [
            'subject' => $request->subject,
            'email' => $request->email,
            'content' => $request->text
        ];

        Mail::send('admin.emails.email',$dataEmail, function ($message) use ($dataEmail) {
            $message->from('support@qareeb.com','Support@Qareeb')
                ->to($dataEmail['email'])
                ->subject('Qareeb | Customer Support');
        });

        return back()->with('success', 'Email sent successfully !');
    }
}
