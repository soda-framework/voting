# Soda Voting
A sweet voting platform for Soda CMS

###Installation
1) Firstly follow the instructions to install Soda CMS at:
https://github.com/soda-framework/cms

2) Require this in your `composer.json` file
```
    composer require soda-framework/voting
```

3) Integrate Soda Voting into laravel by adding `Soda\Voting\Providers\VotingServiceProvider::class`
in the providers array in `/config/app.php`
```
    'providers' => [
        Soda\Providers\SodaServiceProvider::class,
        Soda\Voting\Providers\SodaServiceProvider::class,
    ]
```

4) Run the database migrations `php artisan migrate` to generate the necessary tables

##Configuration
//TODO

##Voting endpoints
There a number of api end points available to help with the voting process,
found at `routes/api.php`.

##Creating reports
//TODO