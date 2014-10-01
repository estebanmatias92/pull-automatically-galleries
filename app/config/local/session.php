<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Default Session Driver
	|--------------------------------------------------------------------------
	|
	| This option controls the default session "driver" that will be used on
	| requests. By default, we will use the lightweight native driver but
	| you may specify any of the other wonderful drivers provided here.
	|
	| Supported: "file", "cookie", "database", "apc",
	|            "memcached", "redis", "array"
	|
	*/

	'driver' => 'file',

	/*
	|--------------------------------------------------------------------------
	| Session Lifetime
	|--------------------------------------------------------------------------
	|
	| Here you may specify the number of minutes that you wish the session
	| to be allowed to remain idle before it expires. If you want them
	| to immediately expire on the browser closing, set that option.
	|
	*/

	'lifetime' => 120,

	'expire_on_close' => false,

	/*
	|--------------------------------------------------------------------------
	| Session File Location
	|--------------------------------------------------------------------------
	|
	| When using the native session driver, we need a location where session
	| files may be stored. A default has been set for you but a different
	| location may be specified. This is only needed for file sessions.
	|
	*/

	'files' => storage_path().'/sessions',

];