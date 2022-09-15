# Submission for qualifier task #2 on Web Technologies Finnish EuroSkills team

## Author

I am Heikki Miinalainen, author of this Laravel and frontend code.

Note that the quality of this code is intentionally very bad: it is done in competition programming in **very** limited timeframe, thus using every method possible to save time by sacrificing even basic coding quality for speed. Nobody in their right minds should ever write their full controller logic in `routes/api.php`, or seed their DB in migrations.

## Operation instructions

You can have two instructions, either the XAMPP route, or WSL2/Docker route. This guide is written for the score marking evaluation being done in a Windows system, and example `.env` file is more suited for initial XAMPP install.

**Please use only one or the other way at the same time.**

### Install using XAMPP

1. Clone this repository in folder of your choice.
2. Make sure you have MySQL credentials at hand for this app. You should have empty database and user with all privileges for that database.
3. Make sure you have [https://getcomposer.org/download/](Composer) and [https://www.apachefriends.org/](XAMPP) installed.
4. Point your XAMPP Apache DocumentRoot to the `public/` folder of your local repository copy.
5. Copy `.env.example` file to `.env`.
6. In `.env` file, update the environment variables starting with `DB_` to match right settings (username, password, database).
7. Open a `cmd` shell or PowerShell.
8. Run these commands (every one must succeed before continuing):
    * `composer install`
    * `php artisan key:generate`
    * `php artisan migrate:fresh`
9. In XAMPP control panel, start Apache and MySQL services. Those should start up normally.

### Install using WSL2 and Docker

1. Please clone this repository into WSL2 -based system (or other compatible Linux system) containing `composer` and `docker`. This system uses [https://laravel.com/docs/9.x/sail](Laravel Sail) under [https://www.docker.com/](Docker) for its operation.
5. Copy `.env.example` file to `.env`.
2. Open a terminal and `cd` into this directory.
3. Run `composer install`
4. Run `vendor/bin/sail up -d`
5. Run `vendor/bin/sail shell`
6. In newly-opened shell, run these commands:
    * `php artisan key:generate`
    * `php artisan migrate:fresh`
7. After evaluation, you can shut down the system running `vendor/bin/sail down`

If you happen to get error about binding to port 5173, you can safely remove the line for setting `VITE_PORT` in `docker-compose.yml`. This may occur in Windows filesystem, has not occurred for me when cloned straight into WSL2 filesystem. Restarting the Windows computer might also help with port errors.

You may have to tinker with DB environment variables with this option, too, as Sail seems not to touch the example environment. Tests tell that host is better as "mysql", username as **not** "root", and password as set, not empty.

## Evaluation

As specified in the task, backend API can be found at `/module-b/phase1/api/v1`. 
[http://localhost/module-b/phase1/api/v1/concerts](http://localhost/module-b/phase1/api/v1/concerts) is an example url of backend submission. 
Frontend submission can be found at [http://localhost/module-b/phase2/](http://localhost/module-b/phase2/).
There is sample seed data in database for initial configuration. You can empty the tables for further evaluation if needed, but must then supply your own data.