<?php

use Vyui\Contracts\Http\Kernel;
use Vyui\Foundation\Application;
use Vyui\Foundation\Http\Request;

define("FRAMEWORK_START", microtime(true));

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

/** @var Application $application */
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

/*s
|-----------------------------------------------------------------------------------------------------------------------
| Prepare and Ship the Request into a Response.
|-----------------------------------------------------------------------------------------------------------------------
| Capture the request that the user has made and then ship this request off to the kernel so that the response can be
| prepared and returned to the handling the process of building out a response
| to show.
|
*/
//
//$array = [
//    'test' => [
//        'item' => [
//            'pog' => 'champ'
//        ]
//    ],
//    'foo' => 'bar',
//    'user' => [
//        'username' => 'Lillam',
//        'email' => [
//            'address' => 'liam.taylor@outlook.com'
//        ],
//        'password' => [
//            'hash' => [
//                'key' => 'tester',
//                'salt' => 'sdsdfsdfsdfdsf'
//            ],
//            'sdfsdfsdfdsfsdfdsf'
//        ]
//    ]
//];
//
///** @var \Vyui\Services\Filesystem\Filesystem $filesystem */
//$filesystem = app(\Vyui\Contracts\Filesystem\Filesystem::class);
//
//$dictionary = (new \Vyui\Dictionary\Dictionary($filesystem))->load();
//
//$dictionary->setAnagram('ceygnur')
//           ->setAnagramMin(4)
//           ->setAnagramMax(7);
//
//$dictionary->addWords([
//
//]);
//
//dd($dictionary->findWordsFromAnagram(), $dictionary->getWordsAdded());
//
//$dictionary->commitWordsAddedToStorage();
//$dictionary->convertDictionaryFilesToPHPFiles();
//
//dd($dictionary->getWordsAdded(), $dictionary);
//
//$arrayable = (new \Vyui\Support\Helpers\Arrayable)
//    ->set('user', (object) [
//        'test' => 15,
//        'email' => 'liam.taylor@outlook.com',
//        'age' => 29,
//        'birthday' => '17/06/1993'
//    ])
//    ->set('array', $array);
//
//dd($arrayable->flatten(true)->toArray());
//
//dd($arrayable->get('user')->test);
//
//dd((new \Vyui\Support\Helpers\Arrayable($array))->flatten(true)->only(['user.email.address']));
//$string = (new \Vyui\Support\Helpers\Stringable('test'))->remove('est');
//dd((string) $string);
//
//$str = \Vyui\Support\Helpers\_String::fromString('Testing @ Tests $ $');
//
//dd($str->slug([
//    '@' => 'at',
//    '$' => 'dollar'
//])->toString());

/** @var \Vyui\Services\Logger\Logger $logger */
//$logger = $application->make(\Vyui\Contracts\Logger\Logger::class);
//
//dd((new \Vyui\Debugger\Debugger)
//    ->run(function () use ($logger) {
//        for ($i = 0; $i < 100; $i ++) {
//            $logger->log("iteration $i - testing");
//        }
//    })
//    ->run(function () use ($logger) {
//        for ($i = 0; $i < 100; $i ++) {
//            $logger->directLog("iteration $i - testing");
//        }
//    })
//    ->compare()
//);

//echo json_encode([
//    'content' => (string) (new \Vyui\Support\Helpers\Stringable('test'))
//        ->concat('testing', 'testing again', 'and another')
//]);
//
//exit;
//
//dd(
//    (new \Vyui\Debugger\Debugger())
//        ->run(function () {
//            echo 'here';
//        })
//        ->run(function () {
//            foreach ([1,2,3] as $number) {
//                echo $number;
//            }
//        })
//        ->getTests()
//);


//$token = (new \Vyui\Contracts\Auth\JWT)->encode([
//    'id' => 1,
//    'name' => 'liam taylor',
//    'exp' => time() + 20,
//]);

//dD(base64_encode(json_encode([
//    "id" => 1,
//    "name" => "liam taylor"
//])));
// eyJpZCI6MSwibmFtZSI6ImxpYW0gdGF5bG9yIn0=

// dd(json_decode(base64_decode('eyJpZCI6MSwibmFtZSI6ImxpYW0gdGF5bG9yIn0'), true));

//$parsedToken = (new \Vyui\Contracts\Auth\JWT)->decode($token);
//
//dd($token, $parsedToken);

$response = $kernel->handle($request = Request::capture());

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