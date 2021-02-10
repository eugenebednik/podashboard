@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-white-50">Introduction</h3>
                        <p>The Protocol Officer Dashboard is a <b>100% legal and free</b> service that facilitates the work of Protocol Officers in the <a href="https://got.gtarcade.com" target="_blank">Game of Thrones: Winter is Coming</a> browser game, developed by Yoozoo Games.</p>
                        <p>The service consists of a web application and a Discord bot designed to allow your Kingdom's players and Protocol Officers to work together and effectively request and assign King Landing titles. The service is legal because it still requires a live human being to operate the Protocol Officer account.</p>
                        <p>The service comes complete with Discord login integration and in-depth reporting of the performance of your your Protocol Officers, with metrics such as their total served requests, average time spent in service per session and total time spent giving out buffs.</p>
                        <h3 class="text-white-50">Installation Instructions</h3>
                        <p>Please follow these instructions to add the bot and activate the dashboard for your server:</p>
                        <ul>
                            <li><a class="btn btn-info" href="https://discord.com/oauth2/authorize?client_id=770374612084195328&scope=bot&permissions=537357376" target="_blank">Add The Bot To Your Server</a></li>
                            <li>On your Discord server, go to the channel where you would like the Protocol Officer bot to operate and run the following command: <pre class="text-muted">!pobot setup</pre></li>
                            <li>The bot will create your server within the Dashboard app and provide you with a server link that looks like this: <pre class="text-muted">https://your-server.protocolofficer.net</pre></li>
                            <li>Email <code>D a n y</code> in game with the link the bot provided you with so that I can activate your Dashboard account. Alternatively, you can join my support discord (link below) and request activation there.</li>
                            <li>While you wait for activation, set up a separate role on your Discord server for users that will be ale to login to the Dashboard, such as <code>Protocol Officer</code> or <code>PO</code>.</li>
                            <li>Once I have activated your account, use the URL provided to you by the Discord bot to login.</li>
                            <li>Once logged in, click on your name dropdown on the top right of the page and select <b>"Role Management"</b>. Next, allow the role you created in previous steps to login to the server by clicking the red button next to it. When the button turns green, all users having that role in your Discord server will be able to login and use the system.</li>
                            <li>Assign the role mentioned in the previous steps to all your Protocol Officers in your Discord server.</li>
                            <li>Having problems installing the bot? Join my Discord server for help. Scroll down for the link to my Discord server.</li>
                        </ul>
                        <h3 class="text-white-50">How to Request Titles</h3>
                        <p>Once the bot and the Dashboard have been activated, your players can request titles from your Protocol Officers.</p>
                        <p><b>A Protocol Officer must be signed on (use the "Sign On" button on the Dashboard) before the bot will allow a player to request a title.</b></p>
                        <ul>
                            <li>In your server's Discord, go to the channel where the bot is operating.</li>
                            <li>Type in the following commands to request titles: <code>!po +r</code> (Research), <code>!po +b</code> (Building), <code>!po +t</code> (Training) or <code>!po +lc</code> (Lord Commander).</li>
                            <li>Type <code>!po done</code> when you are done using the title to remove your request from the queue. Alternatively, requests are automatically removed from the queue after 2 minutes have passed.</li>
                            <li>Type <code>!po q</code> to see the current buff queue.</li>
                        </ul>
                        <h3 class="text-white-50">Support & Feedback</h3>
                        <p>I welcome all sorts of feedback, bug reports, as well as am eager to chat with you and provide support using the system when I can. Join my Discord server to stay in touch!</p>
                        <p><a class="btn btn-outline-info" target="_blank" href="https://discord.gg/zr7wrQZeNX">Join The Dashboard Discord Server</a></p>
                        <h3 class="text-white-50">Disclaimer</h3>
                        <p>As mentioned before, this service is provided <b>free of charge</b>. However, I do have to pay for the server the app is hosted on, so if you would like to support me, please donate! Thank you.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
