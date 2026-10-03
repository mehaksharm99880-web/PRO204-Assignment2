# The Glam Room – Beauty Salon Web Application

## PRO204 Web Application Development

The Glam Room is a responsive web application developed for a small beauty salon business. The system allows customers to view salon services and submit enquiries, while authorised administrators can securely manage salon services through an authenticated administration area.

The application was developed using PHP, MySQL/MariaDB, HTML5, CSS3, JavaScript and Bootstrap 5.3.3.

---

## 1. Project Features

### Public Website

Customers can:

- View the salon homepage
- View available beauty services
- View service categories, descriptions, prices and durations
- Filter services dynamically by category
- Submit enquiries through the contact form
- Receive client-side form validation
- View a dynamic message character counter
- Use the website on desktop, tablet and mobile devices
- Navigate through a responsive Bootstrap-based interface

### Admin Area

Authorised administrators can:

- Log in using a username and password
- Access the protected administration area
- Add new salon services
- View existing services
- Edit services
- Delete services
- Log out securely

The administration area is protected using PHP sessions, password hashing, CSRF protection and access control.

---

## 2. Technologies Used

| Technology | Purpose |
|---|---|
| PHP | Server-side application logic |
| MySQL/MariaDB | Database management |
| MySQLi | Database communication |
| HTML5 | Page structure |
| CSS3 | Styling and responsive design |
| Bootstrap 5.3.3 | Responsive front-end layout |
| JavaScript | Client-side validation and dynamic interaction |
| XAMPP | Local development environment |
| Git/GitHub | Version control |
| AWS EC2 | Server deployment |

---

## 3. Project Structure

```text
PRO204 Assignment2/

├── .github/
│   └── workflows/
│       └── deploy.yml
│
├── admin/
│   ├── login.php
│   └── services.php
│
├── assets/
│   ├── css/
│   │   ├── contact.css
│   │   ├── services.css
│   │   └── style.css
│   │
│   └── js/
│       └── main.js
│
├── config/
│   ├── auth.php
│   ├── database.php
│   └── ServiceManager.php
│
├── database/
│   └── glam_room.sql
│
├── .env.example
├── .gitignore
├── contact.php
├── index.php
├── README.md
└── services.php