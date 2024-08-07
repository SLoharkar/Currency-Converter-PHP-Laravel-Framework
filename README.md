# Currency Converter PHP Framework Laravel Application

A robust currency converter application built with Laravel 10. This application provides multiple data source options, user roles with specific permissions, and extensive features for both normal users and admin users.

## Features

- **Data Source Options**:
  - Excel
  - Default through floatrates website
  - Database
- **Currency Conversion**:
  - Convert all available currencies with limit options
  - Export currencies in Excel and database
- **User Roles**:
  - Normal User: Access to dashboard and currency converter with default data source
  - Admin User: Manage data sources (Excel and database), export currencies, user management, and IP management
- **User Management**:
  - Delete, update users
  - Register users
  - Reset password functionality
  - Remember Me functionality
- **IP Management**:
  - Add, delete, update IPs
- **Security**:
  - IP restriction for normal users
  - Authentication with hashed passwords
- **Database**:
  - Database migration at startup
  - MySQL
- **Development Practices**:
  - Laravel MVC architecture (Model, View, Controller)
  - Custom requests
  - Global environment variables
  - Laravel doc commenting
  - Laravel logging
  - Laravel Blade template engine
  - Controller, Model, Service, and Repository pattern
  - Middleware for authentication
  - Laravel validation

## Installation

1. **Clone the repository**:
    ```sh
    git clone https://github.com/yourusername/currency-converter.git
    cd currency-converter
    ```

2. **Install dependencies**:
    ```sh
    composer install
    npm install
    npm run dev
    ```

3. **Environment Setup**:
    - `.env` and update the necessary environment variables, including database credentials and other configurations.

4. **Run migrations**:
    ```sh
    php artisan migrate
    ```

5. **Start the development server**:
    ```sh
    php artisan serve
    ```

## Usage

- **Normal Users**:
  - Access the dashboard and use the currency converter with the default data source.
  
- **Admin Users**:
  - Manage data sources (Excel and database).
  - Export currencies in Excel and database.
  - Manage users (delete, update).
  - Manage IPs (add, delete, update).

## Middleware

- **ShareUserData**:
  - Checks if the users' table is empty and logs out the user if true.
  - Shares authenticated user's role and username with all views.
  - Redirects to the login page if no user is authenticated.

## Custom Requests

- Handle data validation and business logic separation using custom request classes.

## Global Environment Variables

- Use environment variables for configuration settings.

## Logging

- Utilize Laravel's logging capabilities for error tracking and debugging.

## Blade Template Engine

- Create dynamic and reusable views using Laravel's Blade template engine.

## Security

- **IP Restriction**: Restrict access based on IP for normal users.
- **Authentication**: Secure authentication with hashed passwords.

## Acknowledgements

- Laravel framework and its community
- Floatrates for currency conversion data
```
