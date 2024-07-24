# The Gallery Café Website

A dynamic, database-driven web application designed to enhance customer experience for The Gallery Café in Colombo.
Features include an attractive and user-friendly interface, online table reservations, pre-ordering capabilities,
detailed menus, high-quality images, and user account management for admins, operational staff, and customers. Developed
using modern web technologies for front-end and back-end functionalities, ensuring a seamless and engaging user
experience.

## Prerequisites

- PHP v8.3.6 or higher
- MySQL v5 or higher

## Installation

- #### With WAMP Server

  - Make sure the WAMP Server is installed and running
  - Clone the repository to the `www` folder in the root directory of the WAMP server
  - Go to the WAMP server dashboard by opening `http://localhost:<WAMP_SERVER_PORT EXAMPLE:80>` in your browser
  - Verify whether the `TheGalleryCafé` project appears in the list of projects
  - Open the `TheGalleryCafé` project in the WAMP server dashboard by clicking on the `TheGalleryCafé` project name.

> [!IMPORTANT] Ensure the `BASE_URL` variable in the `config.php` file is set to the correct URL. This is required for
> the static files to appear correctly in the browser. For example, if the URL to the `index.php` file on your browser's
> address bar is `http://localhost/TheGalleryCafe/index.php`, then the BASE_URL would be
> `http://localhost/TheGalleryCafe/`.
