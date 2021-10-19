# Simple Wallet

## How to install

This application use sqlite, so you must create database first.

1. Recomendation PHP version 7.4 above.
2. In the application root directory, in terminal run `touch database/database.sqlite`
3. Install the depencies `composer install`
4. Copy the configuration file `cp .env.example .env`
5. Generate application key `php artisan key:generate`
6. Generate JWT key `php artisan jwt:secret`, choose yes if some confirmation question asked
7. Run the migration to create table structure, `php artisan migrate --seed`
8. Run the server `php artisan serve` and open in the Postman `localhost:8000`

Note: to list available routes, in the new terminal type `php artisan route:list`.

Before you test the endpoint, you must create the customer first. 
Luckyly, I have created for you in the 7th step above.
If you want to see list of customers and grab the id, 
open the database.sqlite in your favourite sqlite client.

## Queue

After each transaction, there is a after create hook to 
calculate the balance of wallet.

This hook use queue, you can setup queue driver as you like in .env
with key `QUEUE_DRIVER`. 
Available driver are "sync", "database", "beanstalkd", "sqs", "redis".

For simplicity, current driver is sync. 
If you use another driver, make sure run `php artisan queue:work`.

## Important note

For patch request `PATCH /api/v1/wallet` cannot use *--form* 
instead using *--data-urlencode* and add the content type header.

Example:

```
curl --location --request PATCH 'localhost:8000/api/wallet' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9pbml0IiwiaWF0IjoxNjM0NjE2NjQ0LCJleHAiOjE2MzQ2MjAyNDQsIm5iZiI6MTYzNDYxNjY0NCwianRpIjoiSkQyT3VrS3dJcjRlZmo3MCIsInN1YiI6ImI1OWM4ZWM0LWY5YjUtNGY1YS1iZjc3LTNkNzRkMDNiM2I5OSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.Qr4EeFhrSk3tfmJ9Nr-cvv6WeEN0iKv-ZR0KQkDJ0mE' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'is_disabled=true'
```