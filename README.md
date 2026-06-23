# EDL Office Portal | Bandarawela Consumer Service Center (CEB Job Management System)

Welcome to the **EDL Office Portal**, a dynamic web application designed specifically for the Bandarawela Consumer Service Center (Electricity Distribution Lanka). This internal portal serves as a secure, centralized digital platform for managing field operations, meter management, and service request coordination.

## 🌐 Live Website

**Access the portal here:** [https://edl.unaux.com/](https://edl.unaux.com/)

---

## 🚀 Overview

The CEB Job Management System digitizes and manages daily operations at the CEB Customer Service Center (CSC). By replacing manual record-keeping with a secure and robust database-driven application, it streamlines mission-critical tasks like meter operations, new electricity connections, and job tracking.

**Key Highlights:**
- Internal Use Only for authorized personnel.
- Secure Officer and Super Admin login.
- Beautiful, modern UI with Bootstrap 5, interactive carousels, and responsive design.

## 🛠️ Technology Stack

- **Frontend:** HTML5, CSS3, JavaScript (ES6+), Bootstrap 5, Swiper JS, FontAwesome 6
- **Backend:** PHP (Modular architecture using partials, controllers, and middleware)
- **Database:** MySQL relational database (`ceb_project`)

## 📦 Core Modules & Features

1. **User Management & Authentication**
   - Super Admin and Officer roles.
   - Secure login with hashed passwords and Session Management.
   
2. **Meter Removal Management**
   - Track physical meter removal jobs.
   - Details: Account No, Meter No, Removal Dates, and Officer Notes.
   - Statuses: `Pending` → `Removed` → `Returned - Paid` → `Cancelled`.

3. **Meter Change Management**
   - Manage meter upgrades or replacements.
   - Supports both Single Phase and Three Phase.
   - Statuses: `Pending` → `Completed`.

4. **New Connections**
   - End-to-end lifecycle tracking for new electricity connection requests.
   - Service Types: Normal, 3 Phase, Augmentation, Over 100k.
   - Statuses: `Registered` → `Shortcoming` → `Estimated` → `Pending Approval` → `Job Created` → `Completed`.

5. **Activity Audit Logs**
   - Security tracking logging "Who did What and When".

6. **Community & Updates (CSR)**
   - View recent organizational updates, community welfare drives, and public notices directly from the homepage.

## 📁 Directory Structure

```text
/
├── admin/               # Secure dashboard portal for officers
│   ├── auth/            # User authentication logic
│   ├── controllers/     # Core backend business logic & CRUD ops
│   ├── pages/           # Dashboard UI and module interfaces
│   ├── middleware/      # Security and session verification
│   └── layout/          # Shared UI elements (Nav, Footer, Sidebar)
├── assets/              # CSS, Images, Fonts, and JS files
├── config/              # Database connections and env config
├── includes/            # Shared components (e.g., Notifications)
├── public/              # Front-facing assets and components
├── structure_and_plan/  # Project planning, DB SQL dumps, and documentation
├── uploads/             # User and system file uploads
└── index.php            # Main public landing page
```

## ⚙️ Setup & Installation

1. **Clone the repository:**
   ```bash
   git clone <repository_url>
   ```
2. **Set up the Database:**
   - Import the `ceb_project.sql` file located in `structure_and_plan/` into your MySQL server.
3. **Configure Environment:**
   - Update `config/db_conn.php` with your local database credentials (DB Host, User, Password, DB Name).
4. **Run the Application:**
   - Place the project folder in your local server directory (e.g., `htdocs` for XAMPP or `www` for WAMP).
   - Access the site via `http://localhost/ceb/`.

---

*Developed by Hathisa Thissara (v4.0)*
