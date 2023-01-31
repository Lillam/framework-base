### Vyui Framework
A custom built educational oriented framework MVC (Model View Controller) inspired heavily by Laravel.

#### Features: 
- Conjure (Console application) 
- DI (Dependency Injection)
- Facade Pattern Ready
- Support Implementations for an object oriented way of dealing with:
  - arrays 
  - objects
    - reflectable helper which helps with dynamic programming.
  - floats
  - integers
  - strings
- Primitive Templating Engine with support for:
  - own made templating languages
  - vyui templating
  - blade templating
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

### Conjure (Artisan Ripoff)
- php conjure test (will run a custom|simplistic created test suite)
- php conjure make:{option} which has stubs for as well as ability to edit and create your own stubs. for your implementation of the following:
  - request
  - model
  - command
  - controller
  - service