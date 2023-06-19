Folklore Program
The Folklore Program is a PHP-based application that allows users to explore and interact with a folklore database. This readme provides instructions on how to set up and run the program on your local machine.

Prerequisites
Before getting started, ensure that you have the following prerequisites installed:

PHP (version 8 or higher)
Composer (version 2.5.8 or higher)
PostgreSQL (version 15 or higher)
Setup
Follow the steps below to set up the Folklore Program:

Create a database named "Folklore" (with a capital "F") in your PostgreSQL server. You can use any preferred method to create the database.

Open the config/database.php file and configure the necessary database connection settings. Update the host, port, database name, username, and password according to your PostgreSQL server configuration.

Installation
Open a terminal or command prompt and navigate to the directory where you have downloaded or cloned the Folklore Program.

Run the following command to install the required dependencies using Composer:

shell
Copy code
composer install
After the dependencies are installed, run the following command to generate the autoload files:

shell
Copy code
composer dump-autoload
Testing
To test the Folklore Program, follow these steps:

Run the following command in the project directory to start the program:

shell
Copy code
php config.php
After the program has started successfully, open a web browser and navigate to http://localhost:8000 (or replace localhost:8000 with the desired hostname and port).

Use the frontend interface to interact with the Folklore Program and explore the folklore database.

Notes
Ensure that your PostgreSQL server is running before starting the Folklore Program.

If you encounter any issues during setup or testing, refer to the program's documentation or seek assistance from the support team.

Customize the hostname and port number used for the server according to your requirements.

Feel free to modify and enhance the Folklore Program as per your needs.