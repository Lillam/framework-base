# Vyui Framework
A custom built educational oriented framework MVC (Model View Controller) inspired heavily by Laravel.

## Features: 

### Console Application (Conjure)

Conjure (./conjure) is an executable PHP file that is heavily inspired from 
Laravel's artisan. This is the console side to the application and will be your 
guide, allowing you to conjure an action. The supported actions of which are: 
- `./conjure test` (test your application)
- `./conjure make` (make a particular entity)
- `./conjure version` (tell you the version of the console)
- `./conjure help` (which will output what you can do with the console)
- `./conjure format` (which will format your application)
- `./conjure migrate` (which will run the migration files)

### Dependency Injection (DI)
This application supports dependency injection off the bat, working closely similar to 
Laravel's application you can construct anything from the container, anything running through
the container will be recursively mapped as needed.

### Test Suite 
The application comes with a simplistic light-weight built in testing solution that doesn't require any
external packages to be installed and can simply run the command `./conjure test` and will begin running 
through all the test files. Test files must end with a .test.php in order for it to be executed 
otherwise all other files will be ignored.

### Templating 
The application has it's own custom built templating engine with extensibility in mind, you can include
your own custom templating engine 
or install one of which is well established and weill work without any extra 
work needed.

- Facade Pattern Ready
- Support Implementations for an object oriented way of dealing with:
  - arrays 
  - objects
    - reflectable helper which helps with dynamic programming.
  - floats
  - integers
  - strings
- Config Parsing Service (application accessible)
- Env Parsing Service (application accessible)
- Test Suite capable of primitive assertions
    - assert integer
    - assert string
    - assert less than
    - assert greater than
    - assert equals to
    - assert loose equals to
    - assert array
    - assert count
    - assert array has key
    - assert array does not have key
    - assert bool
    - assert true
    - assert false
    - assert null
    - assert not null
    - assert float
    - assert instance of
    - ... and more to come