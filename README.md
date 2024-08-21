## Back End Developer Test

To start testing you'll need to perform these simple steps:
* Clone the repo into your own account
* Composer install 
* Run `php artisan store:link` so that you have access to the storage folder
* Run `cp .env.example .env`
* Run `php artisan key:generate`
* Install sqlite3 library and create database.sqlite file in the `/database` folder
* After confirming that you can successfully connect to DB run `php artisan migrate:fresh`
* To make it possible for the system to handle the endpoint calls you need to start the server: `php artisan serve`
* To be able to test Dummy API results save into your DB you need to start the queue: `php artisan queue:work`
* Both commands above should be kept up and running before the next step
* Once the queue started run the command to initiate DB data setup: `php artisan db:seed`
* The process will be handled in the background and I left few info messages here, please check them
* Once the process is finished you should see 'Data import was completed, please check the DB and perform further checks.' message in your laravel.log file
* To test the endpoint you need to send the GET request to `http://127.0.0.1:8000/skus` you can provide these query parameters:
  - page - to change the page
  - per_page - to change the items limit presented on the page
  - filter - to filter by SKU name
  - order - asc/desc to sort by SKU name
* To test "hidden" fields like box_qty & other you need to perform few preparation steps:
  - send POST request to `http://127.0.0.1:8000/login` without any request data (data is hardcoded just for simplicity of testing)
  - send GET request mentioned above again and check the resulst
  - send POST request to `http://127.0.0.1:8000/logout` without any request data to kill the session

-----
Original README:

This test is designed to test your overall code and architectural skills.

There is a dummy API already configured which you can access at `/product-data` 

You should not have to change this endpoint or its controller.

You should demonstrate how you would consume the data from the API, store it and disseminate that data.

Although not strictly represented in the data, you can assume that there will be many variants per SKU.

## Todo
1. Design an adequate table structure for the data supplied.
2. Write code which will store the data from the API into your table structure.
3. Create an endpoint that will display 20 SKUs including their child variants.
4. You should only return an item if the item is available to buy.
5. Only return Box Quantity, Width, Height and Length if the user is logged in.

This is not an exhaustive list of requirements. This is your opportunity to code to the best of your ability.

## Installation
* Clone the repo into your own account
* Composer install 
* Run `php artisan store:link` so that you have access to the storage folder.
* Perform the tasks as required.
* Push the work to your github account
* Provide a link to the repo to PW
