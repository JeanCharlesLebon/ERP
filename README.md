# ERP

PHP 7.4 - Nginx - Mysql - MVC

### Install depencencies

`docker-compose run composer install`

### Strat the project

`docker-compose up -d --build`

### To create Database

- access phpmyadmin at http://127.0.0.1:8080
- user : root
- password : root
- import `erp.sql`

### Site is accessible at http://127.0.0.1

#### Routes are :

- 127.0.0.1/authentication/register
- 127.0.0.1/authentication/login
- 127.0.0.1/authentication/logout
- 127.0.0.1/company
- 127.0.0.1/client
- 127.0.0.1/employee
- 127.0.0.1/product
- 127.0.0.1/provider
- 127.0.0.1/transaction
- 127.0.0.1/user

#### Each non authentication related route possesses sub-routes :

- /edit/{id}
- /add
- /delete/{id}

#### Requests to test editions and additions can be found in ERP.postman_collection.json  