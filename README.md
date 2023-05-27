## Data collection tool ##
*Follow the steps to get the application up and running.*

### Prerequisites ###
* x64 PC/Mac
* Mysql
* php 7.2.X
* composer 2.X.X
* Node.js 14.X.X

Now you can proceed to project installation.
```bash
# Clone the project
> git clone git@github.com:lcdamy/data-collection-tool-api.git
# move to the data-collection-tool-api folder install all packages
> cd data-collection-tool-api
> composer install
> npm install
# create a database in mysql 
# copy .env.example to .env
> cp .env.example .env
# modify the DB environment accordingly
# generate App key 
> php artisan key:generate
# create all necessary tables by running this command
> php artisan migrate:fresh
# if all is okay, Now you can run your server.
# Run the server
> php artisan serve
```   
### For developpers ###
[Visit for Api documentation](http://127.0.0.1:8000/docs/)
### Owner ###
Wise Advice Ltd

### Main Contributors ##
* Developers: Pierre Damien, Willy

