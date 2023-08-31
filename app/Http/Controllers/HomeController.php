<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Kutia\Larafirebase\Facades\Larafirebase;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('notif');
    }

    public function updateToken(Request $request)
    {
        try {
            $request->user()->update(['fcm_token_web' => $request->token]);
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required'
        ]);

        try {
            $fcmTokens = User::whereNotNull('fcm_token_web')->pluck('fcm_token_web')->toArray();

            //Notification::send(null,new SendPushNotification($request->title,$request->message,$fcmTokens));

            /* or */

            //auth()->user()->notify(new SendPushNotification($title,$message,$fcmTokens));

            /* or */

            Larafirebase::withTitle($request->title)
                ->withBody($request->message)
                ->withPriority('high')
                ->withImage('https://firebase.google.com/images/social.png')
                ->withIcon('https://seeklogo.com/images/F/firebase-logo-402F407EE0-seeklogo.com.png')
                ->withClickAction('https://www.ekobudi.my.id')
                ->sendMessage($fcmTokens);

            return redirect()->back()->with('success', 'Notification Sent Successfully!!');
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Something goes wrong while sending notification.');
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
    }

    public function sendWebNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = 'AAAAMEtX1b4:APA91bHavagZch83wT017JtowkZ0sb-2AGmaz8MvlT4UkQ3sf2YsLYXyvbNNzsV6ComVLh9rlYjGNDyWoL8wFoDv5wYd2LDqYd2fgUobhWiLw6zeQ9nDxkiEbWnpSyfK9fQzH0gJOYN1';

        // Fetch all FCM tokens from users with a valid device_key
        $fcmTokens = User::whereNotNull('fcm_token_web')->pluck('fcm_token_web')->all();

        $notification = [
            "title" => 'asa',
            "body" => 'body' // Replace with your actual icon URL
        ];

        $data = [
            "registration_ids" => $fcmTokens,
            "notification" => $notification,
            "data" => $notification, // This is necessary to display notifications on backgrounded/terminated apps in some cases.
        ];

        $headers = [
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ];

        $response = Http::withHeaders($headers)->post($url, $data);
        // dd($response);

        if ($response->successful()) {
            return response()->json(['message' => 'Notification sent successfully.']);
        } else {
            return response()->json(['message' => 'Failed to send notification.'], 500);
        }
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
    }
}
