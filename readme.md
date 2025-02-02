# [The Gallery Café Website](https://github.com/Sandakan/TheGalleryCafe)

A dynamic, database-driven web application designed to enhance customer experience for The Gallery Café in Colombo.
Features include an attractive and user-friendly interface, online table reservations, pre-ordering capabilities,
detailed menus, high-quality images, and user account management for admins, operational staff, and customers. Developed
using modern web technologies for front-end and back-end functionalities, ensuring a seamless and engaging user
experience.

Initial Figma design for this website can be found
[here](https://www.figma.com/design/m4hjzkCX4neCWxMEINX0pS/The-Gallery-Cafe?node-id=223-2526&t=pzHELjAOtfxDG1Wb-1).

## Prerequisites

- PHP v8.3.6 or higher
- MySQL v5 or higher

## Installation

- #### Server configuration using WAMP Server

  - Make sure the WAMP Server is installed and running
  - Clone the repository to the `www` folder in the root directory of the WAMP server
  - Go to the WAMP server dashboard by opening `http://localhost:<WAMP_SERVER_PORT EXAMPLE:80>` in your browser
  - Verify whether the `TheGalleryCafé` project appears in the list of projects
  - Open the `TheGalleryCafé` project in the WAMP server dashboard by clicking on the `TheGalleryCafé` project name.

> [!IMPORTANT]  
> Ensure the `BASE_URL` variable in the `config.php` file is set to the correct URL. This is required for the static
> files to appear correctly in the browser. For example, if the URL to the `index.php` file on your browser's address
> bar is `http://localhost/TheGalleryCafe/index.php`, then the BASE_URL would be `http://localhost/TheGalleryCafe`.

- #### Database configuration

  - Import the [thegallerycafe_with_seeds.sql](/utils/thegallerycafe_with_seeds.sql) file (includes seed data) in the
    `utils` folder into the mysql server.
  - Configure the database credentails in the `config.php` file

  User credentials

  ```
  Admin - admin@gmail.com - 12345678
  Staff - staff@gmail.com - 12345678
  ```
