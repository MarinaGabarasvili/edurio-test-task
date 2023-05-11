# Survey test task

Set up
------------

To get started, make sure you have [Docker installed](https://docs.docker.com) on your system, and then clone this repository.

Next, navigate in your terminal to the directory you cloned this, add .env file with all needed variables(example is in email), and spin up the containers for the web server.
As project build with Laravel Sail, run this command:

`docker run --rm \
-u "$(id -u):$(id -g)" \
-v "$(pwd):/var/www/html" \
-w /var/www/html \
laravelsail/php82-composer:latest \
composer install --ignore-platform-reqs`.

Then run: `docker-compose build`.

To run the service at the background run `docker-compose up -d`.

To run migrations run command `./vendor/bin/sail artisan migrate`.

To seed tables run command `./vendor/bin/sail artisan db:seed`.

In order to fill the survey and save the data you need to provide personal access token (sanctum authorization). To generate token register new user and return message will contain token you need to add as  as Authorization Bearer Token in Postman.  
Data example:
```json
{
"answers":[
            {
                "user_id": 1001,
                "question_id": 20,
                "answer_option_id": 91,
                "text": null
            },
          ...]
}
```
Postman Collection file with available requests is attached to email.

To run test run command `./vendor/bin/sail artisan test`.

However, instead of repeatedly typing `vendor/bin/sail` to execute Sail commands, you may wish to configure a shell alias that allows you to execute Sail's commands more easily:

`alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'`
