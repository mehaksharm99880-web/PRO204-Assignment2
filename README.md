# The Glam Room

The Glam Room is a PHP/MySQL beauty salon web application designed for a small local beauty business. The website allows customers to view salon information and available services, submit enquiries, and provides an admin interface for managing salon services.

## Features

- Responsive salon homepage with hero, About, Services preview, contact call-to-action, and footer sections.
- Public Services page displaying services stored in the database.
- Admin Services page providing full create, read, update, and delete (CRUD) functionality.
- Public Contact/Enquiry form with database-backed service selection.
- Server-side form validation with clear validation messages.
- MySQL prepared statements for database operations.
- Accessible navigation, headings, labels, focus states, skip links, and status/error messages.
- Responsive layouts for desktop, tablet, and mobile screen sizes.

## Technologies

- PHP
- MySQL/MariaDB
- MySQLi prepared statements
- HTML5 and semantic HTML
- CSS3 with custom responsive styling
- JavaScript
- Bootstrap 5.3.3 via CDN
- Google Fonts via CDN
- XAMPP for local Apache and MySQL hosting

## Project Structure

```text
PRO204 Assignment2/
|-- index.php                  Homepage
|-- services.php               Public database-driven Services page
|-- contact.php                Public customer enquiry form
|-- admin/
|   `-- services.php           Admin Services CRUD page
|-- config/
|   `-- database.php           MySQL database connection
|-- database/
|   `-- glam_room.sql          Database schema and sample data export
|-- assets/
|   |-- css/
|   |   |-- style.css          Shared website styles
|   |   |-- services.css       Services and admin styles
|   |   `-- contact.css        Contact page styles
|   `-- js/
|       `-- main.js            Navigation and navbar behaviour
|-- .env.example               Database variable documentation template
|-- .gitignore                 Local/private file ignore rules
`-- README.md                  Project documentation
```

## Requirements

To run the project locally, the following are required:

- Windows with XAMPP installed.
- Apache enabled in XAMPP.
- MySQL enabled in XAMPP.
- PHP with the MySQLi extension. XAMPP includes PHP and MySQLi.
- A modern web browser with JavaScript enabled.
- Internet access for Bootstrap, Google Fonts, and remote homepage images.

### Local Server Ports

- Apache HTTP: Port 80
- MySQL: Port 3306

The application runs locally through Apache using the standard HTTP port.

## Local Setup with XAMPP

1. Install XAMPP.

2. Place the project folder in:

```text
C:\xampp\htdocs\PRO204 Assignment2
```

3. Open the XAMPP Control Panel.

4. Click **Start** beside **Apache**.

5. Click **Start** beside **MySQL**.

6. Open phpMyAdmin:

```text
http://localhost/phpmyadmin/
```

7. Create a new database named:

```text
glam_room
```

8. Select the `glam_room` database and open the **Import** tab.

9. Import:

```text
database/glam_room.sql
```

10. After the database import is complete, open the application:

```text
http://localhost/PRO204%20Assignment2/
```

## Database Setup

The `database/glam_room.sql` file contains the database structure required by the application.

The application uses two main database tables:

- `services` – stores salon services including service name, category, description, price, duration, and creation date.
- `enquiries` – stores customer enquiries including name, email, phone, selected service, message, and submission date.

To initialise the database on a new local environment:

1. Start Apache and MySQL in XAMPP.
2. Open phpMyAdmin.
3. Create a database named `glam_room`.
4. Select the database.
5. Open the **Import** tab.
6. Select `database/glam_room.sql`.
7. Click **Import/Go**.

After the import is complete, the database-driven areas of the application are ready to use.

## Database Configuration

The database connection is located in:

```text
config/database.php
```

The current local development configuration uses:

```text
Host: localhost
Username: root
Password: blank
Database: glam_room
```

The `.env.example` file documents the equivalent configuration variables:

```text
DB_HOST=localhost
DB_USERNAME=root
DB_PASSWORD=
DB_DATABASE=glam_room
```

The current PHP application does not automatically load `.env` files. The `.env.example` file is provided as configuration documentation and contains no real secrets.

If a real `.env` file is created in future development, it is excluded through `.gitignore`.

## Application URLs

With Apache and MySQL running, the application can be accessed at:

Homepage:

```text
http://localhost/PRO204%20Assignment2/
```

Public Services:

```text
http://localhost/PRO204%20Assignment2/services.php
```

Contact/Enquiry:

```text
http://localhost/PRO204%20Assignment2/contact.php
```

Admin Services CRUD:

```text
http://localhost/PRO204%20Assignment2/admin/services.php
```

## Public Services Page

The `services.php` page retrieves service records from the `services` database table.

Each service card displays:

- Service name
- Category
- Description
- Price
- Duration

Database failures are handled using a general user-friendly message rather than displaying raw database information. If no services are available, an appropriate empty-state message is displayed.

## Admin Services Management

The `admin/services.php` page provides full CRUD functionality for the `services` table.

### Create
Administrators can add a new service by entering its name, category, description, price, and duration.

### Read
All existing services are retrieved from the database and displayed on the admin page.

### Update
An existing service can be selected using the Edit action. Its information can then be modified and saved back to the database.

### Delete
Existing services can be removed using the Delete action with a browser confirmation prompt.

Server-side validation checks required fields, non-negative prices, positive whole-number durations, and invalid or missing service IDs.

Prepared statements are used for database operations.

The admin interface does not currently include authentication and is intended for a local/development environment.

## Contact and Enquiry Form

The `contact.php` page allows customers to submit:

- Name
- Email
- Phone (optional)
- Selected service
- Message

The Service dropdown is populated dynamically using records from the `services` table.

Server-side validation:

- Requires a customer name.
- Requires a valid email address.
- Requires a valid service selection.
- Requires an enquiry message.
- Checks that the selected service exists.

Valid enquiries are stored in the `enquiries` database table using a prepared `INSERT` statement.

When validation fails, clear error messages are displayed and previously entered values are preserved where appropriate.

Successful submissions display a confirmation message.

## Accessibility

Accessibility considerations implemented throughout the application include:

- Semantic HTML5 page structure.
- Accessible navigation labels.
- Skip links for keyboard users.
- Associated labels for form controls.
- Keyboard-accessible navigation and controls.
- Visible focus styles for links, buttons, and form elements.
- Error and success messages using appropriate alert/status semantics and ARIA live behaviour.
- Alternative text for relevant images.
- Logical heading hierarchy.
- Responsive content that remains usable when zoomed.

Keyboard navigation and 200% browser zoom were manually tested on key public pages.

## Responsive Design

The application combines Bootstrap's responsive grid and navigation components with custom CSS media queries.

The homepage, Services page, Contact page, and Admin Services interface adapt to:

- Desktop screens
- Tablet screens
- Mobile screens

On smaller screens, the navigation collapses into a Bootstrap navigation menu.

Responsive behaviour was manually checked using browser developer tools.

## Server Management and Deployment

The application is configured for local deployment using XAMPP.

Apache is used as the PHP web server and MySQL provides persistent database storage. Both services must be running in the XAMPP Control Panel before accessing the application.

The application does not require a build or compilation command because it uses standard PHP, HTML, CSS, JavaScript, and MySQL.

### Start Procedure

1. Open XAMPP Control Panel.
2. Start Apache.
3. Start MySQL.
4. Initialise the `glam_room` database using `database/glam_room.sql` if required.
5. Open:

```text
http://localhost/PRO204%20Assignment2/
```

### Stop Procedure

Apache and MySQL can be stopped from the XAMPP Control Panel after testing or development is complete.

The submitted version is documented for reproducible local deployment rather than relying on a public production server.

## Testing

The application was manually tested for its main functional requirements.

Testing included:

- Creating a new service.
- Reading/displaying database services.
- Editing an existing service.
- Deleting a service.
- Testing blank required service fields.
- Testing invalid price values.
- Testing invalid duration values.
- Testing invalid service IDs.
- Submitting an empty enquiry form.
- Testing an invalid email address.
- Submitting a valid enquiry.
- Confirming that valid enquiries are stored in the database.
- Testing responsive layouts using browser developer tools.
- Testing keyboard navigation and visible focus states.
- Testing key public pages at 200% browser zoom.

## Known Limitations

- The application currently uses local database values in `config/database.php`; `.env.example` is documentation only and is not automatically loaded.
- The Admin Services page does not currently include login or role-based access control.
- CSRF protection is not currently implemented on the forms.
- The application does not include pagination, search, advanced filtering, image uploads, a booking calendar, email notifications, or an enquiry-management interface.
- Bootstrap, Google Fonts, and remote homepage images rely on external CDN/services and therefore require an internet connection.
- The database must be initialised using `database/glam_room.sql` before the database-driven pages can be used.

## Security Notes

- Real production credentials should never be committed to the repository.
- `.env` is excluded through `.gitignore`.
- `.env.example` contains no real credentials or secrets.
- Prepared statements are used for database operations.
- User-provided output is escaped before being rendered where applicable.
- Database errors are presented to users using general messages rather than raw SQL information.
- The Admin Services page should be protected with authentication and authorisation before production use.
- CSRF protection, HTTPS, rate limiting, authentication, authorisation, and production-safe error logging should be added before a production deployment.
- The XAMPP default `root` account with a blank password is intended only for local development and should not be used in a production environment.

## Git Repository

The project source code and version history are maintained using Git and GitHub.

Repository:

```text
https://github.com/mehaksharm99880-web/PRO204-Assignment2
```

## Tools and Resources

The project was developed using Visual Studio Code, XAMPP, phpMyAdmin, Git, and GitHub.

GitHub Copilot/AI-assisted tools were used during development to assist with code generation, debugging, documentation, and development guidance. Generated suggestions were reviewed, tested, and modified as required before being included in the project.