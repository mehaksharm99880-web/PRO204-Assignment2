# The Glam Room

The Glam Room is a PHP/MySQL beauty salon web application for displaying salon information and services, managing services through an admin page, and receiving customer enquiries.

## Features

- Responsive salon homepage with hero, About, Services preview, contact call-to-action, and footer sections.
- Public Services page displaying services from the database.
- Admin Services page with create, read, update, and delete functionality.
- Public Contact/Enquiry form with a database-backed service selection.
- Server-side validation and prepared MySQL statements.
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
|-- index.php                 Homepage
|-- services.php              Public database-driven Services page
|-- contact.php               Public customer enquiry form
|-- admin/
|   `-- services.php          Admin Services CRUD page
|-- config/
|   `-- database.php          Existing MySQL connection
|-- database/
|   `-- glam_room.sql         Database SQL file; currently empty
|-- assets/
|   |-- css/
|   |   |-- style.css         Shared website styles
|   |   |-- services.css      Services and admin styles
|   |   `-- contact.css        Contact page styles
|   `-- js/
|       `-- main.js           Navigation and navbar behavior
|-- .env.example              Database variable documentation template
|-- .gitignore                Local/private file ignore rules
`-- README.md                 Project documentation
```

## Requirements

- Windows with XAMPP installed.
- Apache enabled in XAMPP.
- MySQL enabled in XAMPP.
- PHP with the MySQLi extension. XAMPP includes PHP and MySQLi.
- A browser with JavaScript enabled for the responsive Bootstrap navigation.
- Internet access for Bootstrap and Google Fonts CDN resources, and the homepage's remote Unsplash images.

## Local Setup with XAMPP

1. Install XAMPP.
2. Copy or place this project directory at:

   ```text
   C:\xampp\htdocs\PRO204 Assignment2
   ```

3. Open the XAMPP Control Panel.
4. Click **Start** beside **Apache**.
5. Click **Start** beside **MySQL**.
6. Open phpMyAdmin at `http://localhost/phpmyadmin/`.
7. Create a database named `glam_room` using the **New** database option.
8. Select the `glam_room` database and import `database/glam_room.sql` from the **Import** tab.
9. Start the application at:

   `http://localhost/PRO204%20Assignment2/`

### Current SQL File Limitation

The file `database/glam_room.sql` is currently present but empty. It therefore does not create the required tables when imported. The application expects at least these tables and columns:

- `services`: `id`, `service_name`, `category`, `description`, `price`, `duration`, `created_at`
- `enquiries`: `id`, `name`, `email`, `phone`, `service_id`, `message`, `created_at`

A populated schema/data SQL export must be supplied before a fresh database can run the database-driven pages. Do not delete or replace the existing SQL file without the required assignment schema.

## Database Configuration

The existing connection is in `config/database.php` and currently uses these local values directly:

```text
Host: localhost
Username: root
Password: blank
Database: glam_room
```

`.env.example` documents the equivalent variables:

```text
DB_HOST=localhost
DB_USERNAME=root
DB_PASSWORD=
DB_DATABASE=glam_room
```

The current PHP application does not automatically read `.env` files. The example file is documentation for local configuration and does not contain real secrets. If a real `.env` file is created, it is excluded by `.gitignore`.

## Application URLs

With Apache running, use these URLs:

- Homepage: `http://localhost/PRO204%20Assignment2/`
- Public Services: `http://localhost/PRO204%20Assignment2/services.php`
- Contact/Enquiry: `http://localhost/PRO204%20Assignment2/contact.php`
- Admin Services CRUD: `http://localhost/PRO204%20Assignment2/admin/services.php`

## Public Services Page

`services.php` reads service records from the `services` table with a prepared `SELECT` statement. Each service card displays:

- Service name
- Category
- Description
- Price
- Duration

Database query failures show a general customer-friendly message. If no records are available, the page displays an empty-state message instead of failing.

## Admin Services Management

`admin/services.php` provides full CRUD management for the `services` table:

- **Create:** add a service with name, category, description, price, and duration.
- **Read:** view all services in an admin table.
- **Update:** select an existing service with its Edit action and save changes.
- **Delete:** remove a service after a browser confirmation prompt.

The admin page uses prepared statements and validates required text fields, non-negative prices, positive whole-number durations, and invalid or missing service IDs. It does not include authentication, so it should only be used in a protected local/development environment.

## Contact and Enquiry Form

`contact.php` allows customers to submit:

- Name
- Email
- Phone (optional)
- Service
- Message

The Service dropdown is loaded dynamically from the `services` table. Server-side validation requires the name, email, selected service, and message; validates email format; and confirms that the selected service exists. Valid submissions are stored in the `enquiries` table using a prepared `INSERT` statement.

Validation errors are shown in an accessible alert summary and submitted values are preserved when validation fails. Successful submissions show a confirmation message. Database and query failures are presented with general messages rather than raw SQL details.

## Accessibility

- Semantic HTML5 elements are used for page structure.
- Navigation has an accessible label and responsive Bootstrap toggle button.
- A skip link provides keyboard access to main content.
- Form controls have associated `label` elements.
- Error and success messages use alert/status semantics and ARIA live behavior.
- Visible focus styles are defined for links and buttons.
- Images include descriptive alternative text where images are used.
- Headings provide a logical page hierarchy.

## Responsive Design

Bootstrap's responsive grid and navbar are combined with custom media queries in the project CSS. The homepage, Services page, Contact page, and admin service interface adapt for desktop, tablet, and mobile widths. On small screens, the navigation collapses into a Bootstrap menu button.

## Known Limitations

- `database/glam_room.sql` is empty and does not currently create or seed the required database tables.
- The application currently uses hardcoded local database values in `config/database.php`; `.env.example` is documentation only and is not loaded automatically.
- The admin Services page has no login or role-based access control.
- There is no CSRF protection on admin or enquiry forms.
- The application has no pagination, search, filtering, image upload, booking calendar, email notification, or enquiry-management page.
- Bootstrap, Google Fonts, and homepage images are loaded from external CDNs/services, so those resources require network access.
- The current application expects the database and tables to exist before the public Services and Contact pages can load their dynamic content.

## Security Notes

- Keep real credentials in a local `.env` or server configuration and never commit them. `.env` is ignored by `.gitignore`; `.env.example` contains no secrets.
- Use prepared statements for database input, as implemented by the public Services, Contact, and admin Services pages.
- User-provided output is escaped before being rendered in the public and admin pages.
- Restrict access to `admin/services.php` before deploying publicly because authentication is not implemented.
- Add CSRF protection, authentication, authorization, HTTPS, rate limiting, and production-safe error logging before using the application in production.
- Avoid using the XAMPP default `root` account with a blank password outside a local development environment.
- Do not expose database connection errors or PHP error details in production.
