<?php

define("FRAMEWORK_START", microtime(true));

use Vyui\Contracts\Http\Kernel;
use Vyui\Foundation\Http\Request;

/*
|-----------------------------------------------------------------------------------------------------------------------
| Fire up the Autoloader.
|-----------------------------------------------------------------------------------------------------------------------
| Register the autoloader that composer provides, conveniently which automatically generates a class loader for this
| Application. All we need to do from here on out is just simply require the autoloader, and never need to manually
| register classes.
|
*/

require '../vendor/autoload.php';

/*
|-----------------------------------------------------------------------------------------------------------------------
| Fire up the Application.
|-----------------------------------------------------------------------------------------------------------------------
| Bootstrap and return the application to the main script, this will render everything that the application needs in
| order to fully function and operate as an application.
|
*/

/** @var \Vyui\Foundation\Application $application */
$application = require_once '../bootstrap/application.php';

/*
|-----------------------------------------------------------------------------------------------------------------------
| Make the Http Kernel.
|-----------------------------------------------------------------------------------------------------------------------
| Allow the application to fire up a kernel for handling all incoming requests and then binding the application to the
| router and allowing the two to come together and form a connection among the app, the router and the request.
|
*/

/** @var \App\Http\Kernel $kernel */
$kernel = $application->make(Kernel::class);

/*
|-----------------------------------------------------------------------------------------------------------------------
| Prepare and Ship the Request into a Response.
|-----------------------------------------------------------------------------------------------------------------------
| Capture the request that the user has made and then ship this request off to the kernel so that the response can be
| prepared and returned to the handling the process of building out a response
| to show.
|
*/

$response = $kernel->handle($request = Request::capture());

//$connection = $application->make(\Vyui\Services\Database\ConnectionManagerInterface::class);
//dd($connection->connection('mysql')->query()->where(function ($query) {
//
//}));

//dd(\App\Models\News::query()->insert([
//	'test' => 'testing',
//	'another_test' => 10
//]));

// \Vyui\Support\Facades\Storage::disk('local');
//
//dd(
//	\App\Models\News::query()
//		->join('news_category', 'news.category_id', '=', 'news_category.id')
//		->where('news_category.id', '=', 2)
//		->all()
//);
//
//dd(
//	\App\Models\NewsCategory::query()
//		->select('id', 'url')
//		->where('id', '>=', 1)
//		->where(function ($query) {
//			$query->where('url', '=', 'website-news')
//				  ->orWhere('url', '=', 'server-news');
//		})
//		->orWhere(function ($query) {
//			$query->where('id', '=', 1)
//				   ->orWhere('id', '=', 2);
//		})
//		->all(),
//	\App\Models\News::query()
//		->where('category_id', '=', 2)
//		->limit(1)
//		->all()
//);

//
//$start = microtime(true);
//

// /** @var \Vyui\Dictionary\Dictionary $dictionary */
// $dictionary = $application->make(\Vyui\Dictionary\Dictionary::class);

// dd($dictionary->convertDictionaryFilesToPHPFiles());

//$dictionary->setAnagram(request('word') ?? 'cardiac')
//	->findWordsFromAnagram(
//		(int) request('min',3),
//		(int) request('max', 7)
//	);

// dd($dictionary);

//
//$string = <<<EOL
//# Heading
//hello there, this is just something that should turn into a p tag?
//- something
//- something
// - something
//  - something
//## Sub Heading
//this is also something that should convert into a p tag?
//### Sub Heading
//#### Sub Heading
//##### Sub Heading
//###### Sub Heading
//EOL;
//
//dd(
//	\Vyui\Support\Markdown\Markdown::parse($string),
//	\Vyui\Support\Markdown\Markdown::convert($string)
//);
//
//dd(
//    $dictionary,
//    $time = microtime(true) - $start,
//    $application->buildTime() < LARAVEL_BUILD_TIME ? 'faster' : 'slower',
//    $application->buildTime()
//);

/*
|-----------------------------------------------------------------------------------------------------------------------
| Send the response.
|-----------------------------------------------------------------------------------------------------------------------
| after the previous step of acquiring the request, we're going to want to send the request and allow the user to see
| the fruits of what had been prepared. This is the final step that the user will see; upon this happening nothing else
| will be visible to the user, any user content code will want to be above this portion of code, as opposed to below it.
|
*/

$response->send();

/*
|-----------------------------------------------------------------------------------------------------------------------
| Close the Doors.
|-----------------------------------------------------------------------------------------------------------------------
| Terminate everything to do with the application and perform some clean up. here we're going to be terminating the
| response as well as request, and anything that needs to happen during the teardown will happen here also.
|
*/

// todo - setup some terminating  callbacks that the application will need in order to begin cleaning up everything that
//        the application may or may not need to do.