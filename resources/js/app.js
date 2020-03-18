/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

let userId = document.head.querySelector("meta[name='user-id']").content;

Echo.private('App.User.' + userId).notification((notifiable) => {
    	$('.badge').text(notifiable.count);
	    $('#notificationlist').prepend(`
	<a class="dropdown-item" href="#">`+notifiable.data.body+`</a>
`);
    });
