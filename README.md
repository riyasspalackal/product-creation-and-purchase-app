# PRODUCT CREATION AND PURCHASE APP 

Laravel Jetstream VueJS 3 & InertiaJS with SSR Support

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

Admin Login
<img width="1788" alt="image" src="https://user-images.githubusercontent.com/15151953/198587746-f1558065-7fcc-4d1f-9844-ae068a24f6fc.png">

<img width="1787" alt="image" src="https://user-images.githubusercontent.com/15151953/198588330-d033be02-557b-4adb-b3de-f47d38dd43b1.png">

<img width="1791" alt="image" src="https://user-images.githubusercontent.com/15151953/198588431-8681015e-a7b1-48d4-aacc-2870bcd7c314.png">
Transaction history 
<img width="1792" alt="image" src="https://user-images.githubusercontent.com/15151953/198588931-267090af-0baf-4219-b203-5395c0b93c40.png">


User Dashboard
<img width="1784" alt="image" src="https://user-images.githubusercontent.com/15151953/198588593-448f24da-72fc-4e50-92fb-657446e53827.png">

<img width="1789" alt="image" src="https://user-images.githubusercontent.com/15151953/198588745-6f927819-f7c8-494e-8be3-412a13db8a34.png">




