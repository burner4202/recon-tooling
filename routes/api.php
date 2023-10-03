<?php

Route::get('/_alive', [
	'as' => 'api.index',
	'uses' => 'AliveController@alive',
]);