
# Folklore Program

The Folklore Program is a PHP-based application that allows users to explore and interact with a folklore database.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Setup](#setup)
- [Installation](#installation)
- [Testing](#testing)
- [Usage](#usage)
- [Contributing](#contributing)
- [Troubleshooting](#troubleshooting)
- [License](#license)

## Prerequisites

Before getting started, ensure that you have the following prerequisites installed:

- PHP (version 8 or higher)
- Composer (version 2.5.8 or higher)
- PostgreSQL (version 15 or higher)

## Setup

To set up the Folklore Program, follow these steps:

1. Create a database named "Folklore" (with a capital "F") in your PostgreSQL server.
   - You can use any preferred method to create the database.

2. Open the `config/database.php` file and configure the necessary database connection settings.
   - Update the host, port, database name, username, and password according to your PostgreSQL server configuration.

## Installation

To install the Folklore Program, follow these steps:

1. Open a terminal or command prompt and navigate to the directory where you have downloaded or cloned the Folklore Program.

2. Run the following command to install the required dependencies using Composer:

   ```shell
   composer install
   ```

3. After the dependencies are installed, run the following command to generate the autoload files:

   ```shell
   composer dump-autoload
   ```

## Testing

To test the Folklore Program, follow these steps:

1. Run the following command in the project directory to start the program:

   ```shell
   php config.php
   ```

2. After the program has started successfully, open a web browser and navigate to http://localhost:8000.
   - Replace `localhost:8000` with the desired hostname and port if needed.

3. Use the frontend interface to interact with the Folklore Program and explore the folklore database.

## Usage

The Folklore Program provides the following features:

- Search and browse folklore entries.
- View details and descriptions of folklore items.
- Add new folklore items.
- Edit and update existing folklore items.
- Delete folklore items.

Feel free to customize and enhance the Folklore Program to suit your needs.

## Contributing

Contributions to the Folklore Program are welcome! If you would like to contribute, please follow these guidelines:
- Fork the repository and create a new branch for your feature or improvement.
- Make your changes and submit a pull request.
- Ensure that your code adheres to the project's coding standards and is well-documented.

## Troubleshooting

If you encounter any issues during setup or usage of the Folklore Program, please refer to the program's documentation or seek assistance from the support team.

## License

The Folklore Program is released under the MIT License. See the [https://opensource.org/license/mit/](LICENSE) file for more information.

---
