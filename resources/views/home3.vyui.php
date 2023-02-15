#[extends: layouts/master]
#[section: body]
    <h1>This is something...</h1>
    #[if: 1 === 1]
        we do something here...
    #[/if]
    #[for: $i = 0; $i < 10; $i++]
        skiddaddling <br />
    #[/for]
    #[echo: $some_data->test] <br />
    #[echo: $some_data->testing]
#[/section]