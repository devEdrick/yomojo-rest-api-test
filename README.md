# Customer Management API
## Introduction
This project is a simple Customer Management API built with Laravel. It supports basic CRUD operations on customer data and includes a web interface. The API is protected with Laravel Passport for OAuth authentication.

## Installation
Follow these steps to set up the project on your local machine:

### Clone the Repository
```bash
git clone https://github.com/devEdrick/yomojo-rest-api-test.git
cd yomojo-rest-api-test
```

### Install Dependencies
Install the necessary PHP and Node.js dependencies:
```bash
composer install
npm install
```

### Configure Environment
Copy the example environment file and set up your environment variables:
```bash
cp .env.example .env
```

### Generate Application Key
```bash
php artisan key:generate
```

### Run Migrations
```bash
php artisan migrate
```

### Set Up Laravel Passport
Generate Passport encryption keys:
```bash
php artisan passport:keys
```

Create a password grant client, then add the generated keys to your .env file:
```bash
php artisan passport:client --password
```

### Serve the Application
Run the following commands in separate terminal windows to serve the website and the API:

```bash
php artisan serve --port=8880
php artisan serve --port=8881
```

## Usage
### Web Interface
The web interface is accessible at:
```bash
http://localhost:8880
```

### API Endpoints
The API is accessible at:
```bash
http://localhost:8881
```

The following API endpoints are available:

- GET /api/customers: Retrieve all customers
- GET /api/customers/{id}: Retrieve a customer by ID
- POST /api/customers: Create a new customer
- PUT /api/customers/{id}: Update a customer
- DELETE /api/customers/{id}: Delete a customer

### API Documentation
API documentation is generated using a tool like Swagger or ApiDoc. To generate the API documentation, use the following command:
```bash
npm run apidoc:generate
```

Access the documentation at:
```bash
http://localhost:8881/apidoc/index.html
```

## Testing
### Running Unit Tests
To run the unit tests, use the following command:
```bash
php artisan test
```

This command will execute all the tests in your tests directory. The tests are designed to ensure that the application functions as expected.

### Middleware and Authentication
For testing purposes, you may want to disable middleware or handle authentication differently. You can do this by modifying your test cases or by using Laravel's actingAs method to simulate a logged-in user.
