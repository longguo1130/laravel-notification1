Steps to create Real-time notification on click on bell icon::

Special note:- Please install 'npm' first by command npm install and then run these command for installing pusher and laravel-echo

composer require pusher/pusher-php-server "~4.0"

npm install --save laravel-echo pusher-js


<-----first of all we have to done these steps------------>

1. go to pusher.com
2. create new account
3. create new app
4. fill project name, cluster as ap2, forntend as vanilla js, and backend in laravel
5. pusher will provode some credentials like APP_ID, secret, cluster etc
6. add these details to the env file in pre-define pusher variables
6. In env file change BROADCAST_DRIVER=log to BROADCAST_DRIVER=pusher
    

<-----first of all we have to done these steps------------>


1. Create notification table command :-

php artisan notifications:table

php artisan migrate

2. Create new notification file command :-

php artisan make:notification DatabaseNotification

3. Delete Mail function from DatabaseNotification file

4. Change "toArray" function name to "toDatabase" function 

5. Define private variable name anything (for ex- $demo)

6. modify construct function like this :

	public function __construct($letter)
	{
		$this->subscription = $letter;
	}

$letter is the variable pass when the notification is send.

7. Modify the toDatabase function like this : 

	public function toDatabase($notifiable)
	{
		return [
		    'letter' => $this->subscription,
		];
    	}

letter in array should be anything.

8. Change via function 

	public function via($notifiable)
	{
		return ['database','broadcast'];
	}

9. Add a new function toBroadcast 

	public function toBroadcast($notifiable)
	{
		return new BroadcastMessage([
    				'letter' => $this->subscription,
    				'count' => $notifiable->unreadNotifications->count(),
		]);
}

9. Create a route like this 

Route::get('/notify',function(){
    $users = User::all(); 
    $letter = ['title' => 'Notification','body' => 'this is demo for sending notification '];
    Notification::send($users, new DatabaseNotification($letter));
    return 'notification send';
});

here two statements must be added 
a.) use Notification facade
b.) use DatabaseNotification file path

10. Redirect to this route and the notifaction will be sent to all users

<------------now the notifications will show in pusher debug console ----------->

<---------- to get the data in frontend ---------------------->

11. Run command 'npm run watch'

12. go to the resources/js/bootstarp.js and uncomment the last function and modify like this

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    authEndpoint: 'http://192.168.1.188/QalbApplication/public/broadcasting/auth', //proper auth path
    broadcaster: 'pusher',		
    key: '97141b53f095c5c992ff',	//the app key provided by pusher
    cluster: 'ap2',		//the cluster you choose  
    encrypted: true,
    // transports: 'websocket'	//must
});

and add new variable name token like this:- 

let token = document.head.querySelector('meta[name="csrf-token"]').content; 

13. go to app.js in same directory and delete all code except " require('./bootstrap'); " and add this code 

let userId = document.head.querySelector("meta[name='user-id']").content;

Echo.private('App.User.' + userId).notification((notifiable) => {
	$('.badge').text(notifiable.count);
	$('#notificationlist').prepend(notifiable.letter.body);
    });

14. add meta tags in layouts/app.blade.php

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <meta name="user-id" content="{{ (Auth::check()) ? Auth::user()->id : '' }}">

15. Please also add these 2 tags

	<script src="{{ asset('js/app.js') }}" defer></script>
       <link href="{{ asset('css/app.css') }}" rel="stylesheet">

16. If you have other external js and css please remove require variables from bootstarp.js

17. Above this all work fine if we use our path as ip. If you want to use that other path please add this property in new laravel-echo instance

authEndpoint: 'http://192.168.1.188/QalbApplication/public/broadcasting/auth', //proper auth path 


After all, register two new accounts open in differnt browser to check the notification hit the /notify url on web browser 

All Done!!



PS:- Please read all points thoroughly first and then edit the code by your own understanding. Sometimes it take time so don't panic. 

   
