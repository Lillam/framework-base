<?php

// dd(\Vyui\Support\Helpers\_Regex::acronise('Complementary metal-oxide semiconductor'));

//$words = [
//    'Portable network graphic',
//    'Plastic Surgery',
//    'Content Management System',
//    '',
//    null
//];
//
//foreach ($words as $word) {
//    var_dump(\Vyui\Support\Helpers\_Regex::acronise($word));
//}
//
//die();

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
//dd($dictionary->findWordsFromAnagram());
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


//$token = (new \Vyui\Auth\Token)->encode([
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

//$parsedToken = (new \Vyui\Contracts\Auth\Token)->decode($token);
//
//dd($token, $parsedToken);

//
//class Bob
//{
//    protected bool $yelled = false;
//    protected bool $questioned = false;
//    protected bool $onlyNumbers = false;
//    protected bool $saidNothing = false;
//
//    protected array $responses = [
//        'response.questioned'        => 'Sure.',
//        'response.questioned.yelled' => "Calm down, I know what I'm doing!",
//        'response.yelled'            => 'Whoa, chill out!',
//        'response.silenced'          => 'Fine. Be that way!',
//        'response.default'           => 'Whatever.'
//    ];
//
//    public function respondTo(string $statement): string
//    {
//        $this->parseStatement($statement);
//
//        if ($this->questioned && $this->yelled) {
//            return $this->getResponse('response.questioned.yelled');
//        }
//
//        if ($this->questioned) {
//            return $this->getResponse('response.questioned');
//        }
//
//        if ($this->yelled) {
//            return $this->getResponse('response.yelled');
//        }
//
//        if ($this->saidNothing) {
//            return $this->getResponse('response.silenced');
//        }
//
//        return $this->getResponse('response.default');
//    }
//
//    private function parseStatement(string $statement): void
//    {
//        $spaceless = preg_replace('/[^a-zA-Z0-9\?]/', '', $statement);
//
//        $this->questioned = substr($spaceless, -1) === '?';
//        $this->yelled = ctype_upper(preg_replace('/[^A-Z]/', '', $spaceless));
//        $this->onlyNumbers = empty(preg_replace('/[0-9]/', '', $spaceless));
//        $this->saidNothing = empty($spaceless);
//    }
//
//    private function getResponse(string $key): string
//    {
//        return $this->responses[$key];
//    }
//}
//
//$bob = new Bob();
//
//dd($bob->respondTo('WHAT THE HELL WERE YOU THINKING?'), $bob);

$chosenLetter = 'p';

$alphabet = range('a', chr(ord($chosenLetter) - 1));
$diamondAlphabet = [
    ...$alphabet,
    $chosenLetter,
    ...array_reverse($alphabet)
];

//array_map(function ($letter) use ($chosenLetter) {
//    $spaces = str_repeat(' ', ord($chosenLetter) - ord($letter));
//    $x = ((ord($letter) - ord("a")) * 2) - 1;
//    $innerspace = $x > 0 ? str_repeat(" ", $x) : '';
//
//    if ($letter === 'a' || $letter === 'A') {
//        return $spaces . $letter . $spaces;
//    }
//
//    return "{$spaces}{$letter}{$innerspace}{$letter}{$spaces}";
//}, $diamondAlphabet);

//dd(
//    array_map(function ($letter) use ($chosenLetter) {
//        $spaces = str_repeat(' ', ord($chosenLetter) - ord($letter));
//        $x = ((ord($letter) - ord("a")) * 2) - 1;
//        $innerspace = $x > 0 ? str_repeat(" ", $x) : '';
//
//        if ($letter === 'a' || $letter === 'A') {
//            return $spaces . $letter . $spaces;
//        }
//
//        return "{$spaces}{$letter}{$innerspace}{$letter}{$spaces}";
//    }, $diamondAlphabet)
//);

//dd($array);

//dd($alphabet);

/**
 * Take a character in the alphabet and begin making a diamond from the
 * letter given.
 *
 * @param string $letter
 * @return array
 *
 * @notes
 * oSpace = outer space
 * iSpace = inner space
 */
//function diamond(string $letter): array
//{
//    // if the letter has been passed as "A" then we're going to simply
//    // return the character as a single standalone array;
//    if (($l = strtoupper($letter)) === 'A') {
//        return [$l];
//    }
//    $alphabet = range('A', chr(ord($l) - 1));
//    return array_map(
//        function ($c) use ($l) {
//            $oSpace = str_repeat(' ', ord($l) - ord($c));
//            // A is always going to be the tip of either side of the
//            // diamond, so we're just going to return early without the
//            // need to calculate some inner space.
//            if ($c === 'A') {
//                return "{$oSpace}{$c}{$oSpace}";
//            }
//            // this space between characters translates to 2x-1 (x being
//            // the distance between the current letter and A)
//            // A to B = 1 (1 * 2)) - 1) = 1... the space between B and A = 1.
//            // A to C = 2 (2 * 2)) - 1) = 3... the space between C and A = 3.
//            // A to D = 3 (3 * 2)) - 1) = 5... the space between D and A = 5.
//            // ...etc
//            $iSpace = str_repeat(' ', ((ord($c) - ord("A")) * 2) - 1);
//            return "{$oSpace}{$c}{$iSpace}{$c}{$oSpace}";
//        },
//        // kind of grossly create the necessary array in a simple way
//        [ ...$alphabet, $l, ...array_reverse($alphabet) ]
//    );
//}

// dd(diamond('m'));


//class Robot
//{
//    /**
//     * @var string[]
//     */
//    protected array $knownNames = [];
//
//    /**
//     * The name of the robot
//     *
//     * @var string|null
//     */
//    protected string|null $name = null;
//
//    /**
//     * Get the robot's name, if a name has been specified then we
//     * can just return that, otherwise we're going to recursively
//     * attempt to generate the robot a name.
//     *
//     * @return string
//     * @throws Exception
//     */
//    public function getName(): string
//    {
//        if (! empty($this->name)) {
//            return $this->name;
//        }
//
//        return $this->generateRandomly();
//    }
//
//    /**
//     * Generate a random name based on (2) Uppercase characters
//     * A-Z (65-A) and (90-Z) This will be entirely random, however;
//     * if this collides with a name that was previously made, we're
//     * going to simply call this method again.
//     *
//     * This method is particularly... brutal, based on the randomness
//     * this has the random potential to run endlessly (the odds of that
//     * be low but the odds are there non-the-less).
//     *
//     * @return string
//     * @throws Exception
//     */
//    private function generateRandomly(): string
//    {
//        $name = chr(random_int(65, 90)) .
//                chr(random_int(65, 90)) .
//                random_int(0, 9) .
//                random_int(0, 9) .
//                random_int(0, 9);
//
//        if (in_array($name, $this->knownNames)) {
//            return $this->generateRandomly();
//        }
//
//        $this->knownNames[] = $name;
//
//        return $this->name = $name;
//    }
//
//    /**
//     * Reset the robot back to factory settings. (nameless)
//     *
//     * @return void
//     */
//    public function reset(): void
//    {
//        $this->name = null;
//    }
//
//    public function countDupes(): int
//    {
//        return count($this->knownNames) - count(array_unique($this->knownNames));
//    }
//}
//
//$robot = new Robot;
//
//for ($i = 0; $i < 1001; $i ++) {
//    var_dump($robot->getName());
//    $robot->reset();
//}
//
//dd($robot->countDupes());
//
//dd('here');

//function raindrops(int $number): string
//{
//    if ($number % 3 !== 0 && $number % 5 !== 0 && $number % 7 !== 0) {
//        return (string) $number;
//    }
//
//    return ($number % 3 === 0 ? 'Pling' : '') .
//           ($number % 5 === 0 ? 'Plang' : '') .
//           ($number % 7 === 0 ? 'Plong' : '');
//}

// dd(raindrops(35));

///**
// * @param string $word    -> the word in which we're going to look for
//                             anagrams from.
// * @param array $anagrams -> the words to detect whether they're anagrams
//                             of the word.
// * @return array          -> the matches.
// */
//function detectAnagrams(string $word, array $anagrams): array
//{
//    return array_values(array_filter(
//        $anagrams,
//        function ($anagram) use ($word) {
//            return $anagram !== mb_strtolower($word) &&
//                   getWordCost($word) === getWordCost($anagram);
//        }
//    ));
//}
//
///**
// * Get the char code of each character; so that when performing a comparison
// * we can check if the characters of each word amounts to the same.
// *
// * @param $word
// * @return int
// */
//function getWordCost($word): int
//{
//    return array_sum(array_map(fn ($char) => ord($char), str_split($word)));
//}
//
//function wordCount(string $phrase): array
//{
//    return array_count_values(str_word_count(strtolower($phrase), 1, '1..9'));
//}
//
//dd(wordCount('This is Something that Something and that is something is good but good is not really good when really you are something else 1 1 2 3 4 5 6 9!!!!!!@£@£!$!@'));


$string = "# Header!\n* __Bold Item__\n* _Italic Item_\nThis should just be a paragraph\n* this is a test\n* this is another test\ntesting...\n* and another list";

//preg_replace_callback_array([
//    '/(\*(.*)\n)|(\*(.*))/' => function ($matches ) {
//        dd($matches);
//    }
//], $string);

$string = "#header\n* Item 1\n* Item 2\nhello";
//
//dd(htmlentities(preg_replace_callback_array([
//    "/(^(\*+ ?.*+\n?){0,})/m"             => fn ($match) => ! empty($match[0]) ? "<ul>$match[0]</ul>" : '',
//    "/\*(.*)/"                         => fn ($match) => "<li><p>$match[1]</p></li>",
//], $string)));

//dd($string, $string = preg_replace_callback_array([
//    "/######+ ?((.*)|(.*\n))/"           => fn ($match) => ("<h6>$match[1]</h6>"),
//    "/#####+ ?((.*)|(.*\n))/"            => fn ($match) => ("<h5>$match[1]</h5>"),
//    "/####+ ?((.*)|(.*\n))/"             => fn ($match) => ("<h4>$match[1]</h4>"),
//    "/###+ ?((.*)|(.*\n))/"              => fn ($match) => ("<h3>$match[1]</h3>"),
//    "/##+ ?((.*)|(.*\n))/"               => fn ($match) => ("<h2>$match[1]</h2>"),
//    "/#+ ?((.*)|(.*\n))/"                => fn ($match) => ("<h1>$match[1]</h1>"),
//    "/(^[*].*\n){0,}/m"                  => function ($match) {
//        if ($match[0] === "") {
//            return "";
//        }
//
//        return '<ul>' .
//               preg_replace_callback('/\*? +(.*)/', fn ($iMatch) => "<li>$iMatch[1]</li>", $match[0]) .
//              '</ul>';
//    },
//    '/(^[a-zA-Z_].*)/'                 => fn ($match) => ("<p>$match[1]</p>"),
//    "/__(.*)__/"                       => fn ($match) => ("<em>$match[1]</em>"),
//    "/_(.*)_/"                         => fn ($match) => ("<i>$match[1]</i>"),
//    "/\n/"                             => fn () => "",
//], $string));

//class BeerSong
//{
//    protected string $output = '';
//
//    public function verse(int $number): string
//    {
//        $next = $number - 1;
//
//        return $this->onTheWall($number) . "\n" .
//               $this->offTheWall($number, $next, $next > 1 ? 's' : '');
//    }
//
//    public function verses(int $start, int $finish): string
//    {
//        foreach ($verses = range($start, $finish) as $key => $verse) {
//            $this->output .= $this->verse($verse);
//            if ($key + 1 < count($verses)) {
//                $this->output .= "\n";
//            }
//        }
//
//        return $this->output;
//    }
//
//    public function lyrics(): string
//    {
//        return $this->verses(99, 0);
//    }
//
//    private function onTheWall(int $n): string
//    {
//        return match ($n) {
//            0 => 'No more bottles of beer on the wall, no more bottles of beer.',
//            1 => '1 bottle of beer on the wall, 1 bottle of beer.',
//            default => "$n bottles of beer on the wall, $n bottles of beer."
//        };
//    }
//
//    private function offTheWall(int $n, int $r, string $s = ''): string
//    {
//        return match ($n) {
//            0 => "Go to the store and buy some more, 99 bottles of beer on the wall.",
//            1 => "Take it down and pass it around, no more bottles of beer on the wall.\n",
//            default => "Take one down and pass it around, $r bottle$s of beer on the wall.\n"
//        };
//    }
//}
//
//dd((new BeerSong)->lyrics());

//class Robot
//{
//    /**
//     * @var int[]
//     */
//    protected array $position;
//
//    /**
//     * @var string "north" | "east" | "south" | "west"
//     */
//    protected string $direction;
//
//    /**
//     * @var int
//     */
//    protected int $numericalDirection;
//
//    public const X = 0;
//    public const Y = 0;
//
//    public const DIRECTION_NORTH = 'north';
//    public const DIRECTION_EAST = 'east';
//    public const DIRECTION_SOUTH = 'south';
//    public const DIRECTION_WEST = 'west';
//
//    /**
//     * @var string[]
//     */
//    protected array $directions = [
//        Robot::DIRECTION_NORTH,
//        Robot::DIRECTION_EAST,
//        Robot::DIRECTION_SOUTH,
//        Robot::DIRECTION_WEST
//    ];
//
//    public function __construct(array $position, string $direction)
//    {
//        $this->position  = $position;
//        $this->direction = $direction;
//        $this->numericalDirection = array_search($direction, $this->directions);
//    }
//
//    public function turnRight(): static
//    {
//        $this->numericalDirection = ($this->numericalDirection + 1) % 4;
//
////        if ($this->numericalDirection > 3) {
////            $this->numericalDirection = 0;
////        }
//
//        $this->direction = $this->directions[$this->numericalDirection];
//
//        return $this;
//    }
//
//    public function turnLeft(): static
//    {
//        $this->numericalDirection = ($this->numericalDirection - 1) % 4;
//
//        $this->direction = $this->directions[$this->numericalDirection];
//
//        return $this;
//    }
//
//    /**
//     * Advance the robot's position
//     *
//     * @return $this
//     */
//    public function advance(): static
//    {
//        $macro = [
//            fn () => $this->position[1] += 1,
//            fn () => $this->position[0] += 1,
//            fn () => $this->position[1] -= 1,
//            fn () => $this->position[0] -= 1
//        ];
//
//        $macro[$this->numericalDirection]();
//
//        return $this;
//    }
//
//    public function instructions(string $instructions): static
//    {
//        if (! empty(preg_replace('/[ALR]/', '', $instructions))) {
//            throw new InvalidArgumentException;
//        }
//
//        $macro = [
//            'A' => fn () => $this->advance(),
//            'L' => fn () => $this->turnLeft(),
//            'R' => fn () => $this->turnRight()
//        ];
//
//        foreach (str_split($instructions) as $instruction) {
//            $macro[$instruction]();
//        }
//
//        return $this;
//    }
//}

//dd((new Robot([0,0], 'north'))->instructions('LAL'));


//class InterestCalculator {
//    /**
//     * @var float
//     */
//    protected float $funds = 0;
//
//    /**
//     * @var float
//     */
//    protected float $interestRate = 0.05;
//
//    /**
//     * @var float
//     */
//    protected float $monthlyDeposit = 1000;
//
//    /**
//     * @var int
//     */
//    protected int $monthsSaving = 180;
//
//    /**
//     * @var array
//     */
//    protected array $growth = [];
//
//    /**
//     * @param float $startingFunds
//     */
//    public function __construct(float $startingFunds)
//    {
//        $this->funds = $startingFunds;
//    }
//
//    /**
//     * @return $this
//     */
//    public function grow(): static
//    {
//        $this->funds = $this->funds +
//                       $this->monthlyDeposit;
//
//        return $this;
//    }
//
//    /**
//     * @return $this
//     */
//    public function addInterest(): static
//    {
//        $this->funds = $this->funds + floor(($this->funds * $this->interestRate) / 12);
//
//        return $this;
//    }
//
//    public function predict()
//    {
//        for ($i = 1; $i <= $this->monthsSaving; ++$i) {
//            $current = $this->funds;
//            $this->addInterest();
//            $added = "£" . $this->funds - $current;
//            $this->grow();
//            $totalAdded = $this->funds - $current;
//            $this->growth[$i] = "Month $i: £$this->funds +$added - total added: £$totalAdded";
//        }
//
//        dd($this->growth);
//    }
//}

//$interest = new InterestCalculator(17000);
//
//$interest->predict();


//function isIsogram(string $string): bool {
//    $string = mb_str_split(mb_strtolower(str_replace([' ', '-'], '', $string)));
//
//    for ($i = 0; $i < ($sl = count($string)); $i++) {
//        // begin iterating backwards to see if this particular string
//        // occurs earlier on in the string.
//        for ($r = ($i - 1); $r >= 0; $r--) {
//            if ($string[$i] === $string[$r]) {
//                return false;
//            }
//        }
//
//        // begin iterating forward to see if this particular string
//        // occurs later on in the strings.
//        for ($n = ($i + 1); $n < $sl; $n++) {
//            if ($string[$i] === $string[$n]) {
//                return false;
//            }
//        }
//    }
//
//    return true;
//}
//
//dd(isIsogram('Emily Jung Schwartzkopf'));

class Interpreter
{
    public function __construct(
        protected string $input
    ) {}

    public function validate(): self
    {
        if (! str_starts_with($this->input, 'What is') || str_contains($this->input, 'cubed')) {
            throw new \InvalidArgumentException(
                "Input is either missing the question, or too advanced with cubed."
            );
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function process(): self
    {
        $this->input = preg_replace_callback_array([
            '/(What is |\?)/'                 => fn () => '',
            '/divided by/'                    => fn () => '/',
            '/multiplied by/'                 => fn () => '*',
            '/plus/'                          => fn () => '+',
            '/minus/'                         => fn () => '-',
            '/(\d+|-\d+).*(\d+|-\d+)/'        => fn ($matches) => "($matches[0])",
            '/(\d+|-\d+) (\-|\+) (\d+|-\d+)/' => fn ($matches) => "($matches[0])"
        ], $this->input);

        return $this;
    }

    /**
     * This particular function is going to eval the string that we've parsed to be a mathematical
     * equation; however this leaves the system open to vulnerabilities as the input could be
     * valid syntax php such as passing in phpinfo()...
     *
     * This is a security flaw, acknowledged but not fixed. (out of scope).
     *
     * @return float
     */
    public function answer(): float
    {
        return (float) eval("return $this->input;");
    }
}

function calculate(string $input): float
{
    return (new Interpreter($input))->validate()->process()->answer();
}

// dd(calculate('What is 53 plus 2?'));