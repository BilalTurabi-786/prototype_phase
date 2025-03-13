Pharmacy System - Local Environment Setup
This is a simple PHP project for managing pharmacy-related functionalities. Follow these instructions to run the project in your local environment.

Prerequisites
Make sure you have the following installed on your system:

PHP (version 7.x or higher)
MySQL (version 5.x or higher)
A web server (Apache/Nginx)
A text editor or IDE (e.g., VSCode)
Steps to Run the Project Locally
Clone or Download the Project: Download the project files or clone the repository into your local system.

Import Database:

In the root folder of the project, you will find the pharmacy_db.sql file.
Open your MySQL client (e.g., phpMyAdmin, MySQL Workbench, or command line).
Create a new database (e.g., pharmacy_db) and import the pharmacy_db.sql file into the database.
Update Database Credentials:

Go to the root folder and open the db_connect.php file.
Update the database connection details (e.g., username, password, database name) according to your local MySQL setup.
Example:

php
Copy
Edit
$servername = "localhost";
$username = "root";
$password = "your_password";
$dbname = "pharmacy_db";
Update Admin Database Credentials:

Navigate to the admin folder and open the db.php file.
Update the database credentials in this file as well to match your local MySQL setup.
Start the Web Server:

Use the built-in PHP server or a web server like Apache or Nginx.
To start the PHP built-in server, navigate to the root directory in your terminal and run:

bash
Copy
Edit
php -S localhost:8000
Access the Project: Open your browser and go to http://localhost:8000 to see the project running locally.
