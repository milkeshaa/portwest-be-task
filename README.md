## Back End Developer Test

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
