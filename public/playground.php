<?php

/**
 * const actual = priceWithMonthlyDiscount(16, 130, 0.15);
const expected = 14528;
expect(actual).toBeCloseTo(expected, DIFFERENCE_PRECISION_IN_DIGITS);
 */

//$dayRate = 16;
//$dayHours = 8;
//$days = 110;
//
//$discountedDays = 20;
//$discount = 1 - 0.15;
//
//dd(
//    (
//        $days * ($dayRate * $dayHours)
//    ) + (
//        ($discountedDays * ($dayRate * $dayHours)) * $discount
//    )
//);

// dd(\Vyui\Support\Facades\DB::connection('mysql'), \Vyui\Support\Facades\DB::getConnections('mysql'));

// dd(\Vyui\Support\Facades\DB::getConnections());

// dd(\Vyui\Support\Helpers\_String::fromString(1)->repeat(4)->toString());

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