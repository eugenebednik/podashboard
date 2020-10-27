# podashboard

A dashboard and API for Protocol Officers for GoT: WiC game and the Discord bot built on Laravel 8 and Vue.js.

### Disclaimer
This Dashboard and its associated [Discord bot](https://github.com/eugenebednik/po-bot) are perfectly legal as no game files or special macros are created. This Dashboard and its associated bot are created as a way to facilitate Protocol Officer duties for players, but the titles must be assigned __manually__ in game.


### Requirements
* A web host running preferably Nginx
* PHP 7.3 or higher 
* MySQL 5.8 or higher
* Node.js 14 or higher
* Composer (latest)

### Installation
1. Clone the repository onto your server.
2. Set up the `public` directory within the repository to be the web server root for your application domain/sub-domain.
3. From within the cloned directory, run (in that order):
```bash
composer install
php artisan key:generate
php artisan migrate --seed
```
4. Set up the (https://github.com/eugenebednik/po-bot)[Discord Bot].
5. Modify your `.env` with values (see `.env.example` for an example).
