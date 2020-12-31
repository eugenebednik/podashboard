@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">{{__('Welcome')}}</div>

                    <div class="card-body">
                        <h3>Installation Instructions</h3>
                        <p>Please follow these instructions to add the bot and activate the dashboard for your server:</p>
                        <ul>
                            <li>Add the Discord bot to your discord server by clicking this link: <a href="https://discord.com/oauth2/authorize?client_id=770374612084195328&scope=bot&permissions=537357376">Add the bot to your Discord server</a>.</li>
                            <li>On your Discord server, go to the channel where you would like the Protocol Officer bot to operate and run the following command: <pre>!pobot setup</pre></li>
                            <li>The bot will create your server within the Dashboard app and provide you with a server link that looks like this: <pre>https://your-server.protocolofficer.net</pre></li>
                            <li>Email <code>Daenelys</code> in game with the link the bot provided you with so that I can activate your Dashboard account.</li>
                            <li>Once I have activated your account, use the URL provided to you by the Discord bot to login and set up your Dashboard.</li>
                            <li>Having problems installing the bot? Email <code>Daenelys</code> in game for assistance.</li>
                        </ul>
                        <p>This service is provided <b>free of charge</b>. However, I do have to pay for the server the app is hosted on, so if you would like to support me, please donate! Thank you.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
