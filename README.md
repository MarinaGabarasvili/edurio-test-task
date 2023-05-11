# Survey test task

Set up
------------

To get started, make sure you have [Docker installed](https://docs.docker.com) on your system, and then clone this repository.

Next, navigate in your terminal to the directory you cloned this, and spin up the containers for the web server by running `docker-compose build`.

To run the service at the background run `docker-compose build`.

Add .env file with all needed variables.

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
