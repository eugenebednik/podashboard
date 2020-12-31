@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">{{__('Installation Instructions')}}</div>

                    <div class="card-body">
                        <h3>Installation Instructions</h3>
                        <p>Please follow these instructions to add the bot and activate the dashboard for your server:</p>
                        <ul>
                            <li>Add the Discord bot to your discord server by clicking this link: <a href="https://discord.com/oauth2/authorize?client_id=770374612084195328&scope=bot&permissions=537357376" target="_blank">add the bot to your Discord server</a>.</li>
                            <li>On your Discord server, go to the channel where you would like the Protocol Officer bot to operate and run the following command: <pre>!pobot setup</pre></li>
                            <li>The bot will create your server within the Dashboard app and provide you with a server link that looks like this: <pre>https://your-server.protocolofficer.net</pre></li>
                            <li>Email <code>Daenelys</code> in game with the link the bot provided you with so that I can activate your Dashboard account.</li>
                            <li>Once I have activated your account, use the URL provided to you by the Discord bot to login and set up your Dashboard.</li>
                            <li>Having problems installing the bot? Email <code>Daenelys</code> in game for assistance.</li>
                        </ul>
                        <h3>How to Request Titles</h3>
                        <p>Once the bot and the Dashboard have been activated, your players can request titles from your Protocol Officers.</p>
                        <p><b>A Protocol Officer must be signed on (use the "Sign On" button on the Dashboard) before the bot will allow a player to request a title.</b></p>
                        <ul>
                            <li>In your server's Discord, go to the channel where the bot is operating.</li>
                            <li>Type in the following commands to request titles: <code>!po +r</code> (Research), <code>!po +b</code> (Building), <code>!po +t</code> (Training) or <code>!po +lc</code> (Lord Commander).</li>
                            <li>Type <code>!po q</code> to see the current buff queue.</li>
                        </ul>
                        <p>This service is provided <b>free of charge</b>. However, I do have to pay for the server the app is hosted on, so if you would like to support me, please donate! Thank you.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
