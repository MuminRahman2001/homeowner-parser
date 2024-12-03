# Homeowner Name Parser

## Description

This application parses homeowner names from a CSV file and splits them into individual records.

## Installation

1. Clone the repository.
2. Run `composer install` to install dependencies.
3. Run `php artisan` to verify Laravel commands are available.

## Usage

1. Place your CSV file in the `storage` directory.
2. Run the command:
   ```bash
   php artisan parse:homeowners storage/homeowners.csv

## Testing

1. Run the command:
   ```bash
   php artisan test
