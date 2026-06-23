# CEB Job Management System - Overview & Plan

## 1. System Overview (මෙම පද්ධතිය ක්‍රියාත්මක වන ආකාරය)
The CEB Job Management System is a dynamic PHP/MySQL-based web application designed to digitize and manage daily operations at the CEB Customer Service Center (CSC). It replaces manual record-keeping with a secure, centralized database, streamlining tasks like meter operations and new electricity connections.

### Architecture & Technology Stack:
* **Frontend:** HTML, CSS, JavaScript (Bootstrap 5 for responsive design). Support for Light and Dark modes.
* **Backend:** PHP (Modular structure using partials, middleware, and controllers).
* **Database:** MySQL relational database (`ceb_project`) handling data persistence.

## 2. Core Modules & Workflows (මූලික අංග සහ ක්‍රියාකාරීත්වය)

### A. User Management & Authentication
* **Roles:** Super Admin and Officer.
* **Features:** Secure login (hashed passwords), Remember Me functionality, Theme preferences, and secure Session Management.

### B. Meter Removal Management (`meter_removal`)
* **Workflow:** Allows tracking of physical meter removal jobs.
* **Details tracked:** Account No, Meter No, Removal Dates, and Officer Notes.
* **Status cycle:** `Pending` → `Removed` → `Returned - Paid` → `Cancelled`.

### C. Meter Change Management (`meter_change`)
* **Workflow:** Upgrading or replacing existing meters.
* **Phase Support:** Accommodates both Single Phase and Three Phase.
* **Details tracked:** Old readings and New readings mapping.
* **Status cycle:** `Pending` → `Completed`.

### D. New Connections (`new_connections`)
* **Workflow:** End-to-end lifecycle tracking of new electricity connection requests.
* **Service Types:** `Normal`, `3 Phase`, `Augmentation`, `Over 100k`.
* **Status cycle:** `Registered` → `Shortcoming` → `Estimated` → `Pending Approval` → `Job Created` → `Completed`.

### E. Activity Audit Logs (`activity_logs`)
* **Tracking:** Automatically logs **Who** did **What** and **When** (e.g., User logins, job updates, deletions) to maintain security and accountability.

## 3. Directory Structure & Development Plan (ගොනු ව්‍යුහය සහ ඉදිරි සැලසුම)

### Directory Structure:
* `/admin` - Secure dashboard portal for officers.
    * `/auth` - Processes user authentication.
    * `/controllers` - Handles core backend business logic, database CRUD operations.
    * `/pages` - Dashboard UI and module interfaces.
    * `/middleware` - Security and session verification.
    * `/layout` - Shared UI elements (Navigation, Footer, Sidebar).
* `/public` - Front-facing portal (if applicable) for customers.
* `/config` - Database and environment configurations.
* `/includes` - Shared functions and external libraries.

### Future Development Plan (ඉදිරි ක්‍රියාමාර්ග):
1. **Interactive Dashboard Analytics:** Implement charts and statistical widgets on the admin dashboard to visualize pending vs. completed jobs in real-time.
2. **Improved User Experience (AJAX):** Upgrade the `controllers` to utilize AJAX requests instead of hard page reloads where applicable.
3. **Public Status Tracking:** Finalize the public-facing section allowing customers to check their New Connection status online.
4. **Reporting / Export Tools:** Enhance robust reporting tools in the `/exports` directory to generate PDF/Excel summaries of completed monthly jobs.
5. **Role-Based Access Control (RBAC):** Refine permissions so Officers cannot delete records, maintaining Super Admin exclusivity on critical actions.


siip ugsh jzya appe
