# Thai-laravel
A helper package for deploying laravel apps in Thailand

## Installation
Install through Composer (currently in Beta, remove dev-master once we move to a stable release)

```composer require "awcode/thai-laravel" "dev-master"```

Or manually adding

```json
{
    "require": {
        "awcode/thai-laravel": "dev-master"
    }
}
```

Once installed run these commands to publish the config file.

``` 
php artisan vendor:publish --provider="Awcode\ThaiLaravel\ThaiLaravelServiceProvider"
```

Run below to publish the Address database migrations and seeds.

```
php artisan migrate
php artisan thailaravel:install
```

## Thai Addresses
Seeders and helper classes to populate all Thai Provinces, Postcodes and Districts

Connector to the Where.in.th commercial database for locating individual addresses

## Thai Validation
Validate a phone number is correct format for Thailand

Validate a [Thai ID card](https://thailandformats.com/idcards) is in correct format

## Thai Format Helpers
Convert [Thai Years](https://thailandformats.com/dates) to Gregorian Calendar

Convert [Rai/Wah](https://thailandformats.com/areas) (Land unit measurements) to other common formats

## Thai Fonts
Font packages to help use Thai Text

Helpers to make Thai fonts work in other packages such as DomPDF


### Support and Contributing
This has been built by the team at [AWcode](https://awcode.com), for both our internal needs and to support the Thai developer community.
Please run your own tests to ensure that this fits your needs, no warranty or guarantee is provided.

If you have any questions, feedback or issues please raise an Issue on this repository.

Contributors are welcome to submit pull requests for review.
