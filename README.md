# PRODUCT CREATION AND PURCHASE APP

#### Following are the Models

-   Order
-   OrderTransaction
-   Product
-   User

#### Usage

Clone the project via git clone or download the zip file.

##### .env

Copy contents of .env.example file to .env file. Create a database and connect your database in .env file.

##### Composer Install

cd into the project directory via terminal and run the following command to install composer packages.

###### `composer install`

##### Run Migration and Seed

then run the following command to create migrations and create dummy content in databbase.

###### `php artisan migrate:fresh --seed`

##### Run your Laravel server:

Since Laravel recently runs on Vite, we need to run 2 terminal commands.

###### `npm run dev`

And in another terminal window we need to run the below:

###### `php artisan serve`

#### Two user created

-   admin user
    -   username : admin
    -   password : admin123
-   user
    -   username : user
    -   password : user123

### API EndPoints

##### User login

-   User POST `http://localhost:8000/login`

##### Post

-   POST Create product `http://127.0.0.1:8000/create-products`
-   GET All Products ` http://127.0.0.1:8000/get-products`
-   DELETE Product By id `http://127.0.0.1:8000/delete-products/id`
-   POST Create Order `http://127.0.0.1:8000/create-order`
-   GET User created Order ` http://127.0.0.1:8000/get-user-orders`
-   POST Payment `http://127.0.0.1:8000/pay`
-   GET All orders `http://127.0.0.1:8000/get-all-orders`
-   GET Transaction history `http://127.0.0.1:8000/transaction-history/orderId`
