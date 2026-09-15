# NGO Management System (NGO-ERP)

A comprehensive, full-stack NGO Enterprise Resource Planning (ERP) web application designed to help Non-Governmental Organizations streamline their day-to-day operations. This system integrates multiple management modules including members, donors, beneficiaries, projects, finances, and a public-facing portal.

## 🚀 Key Features

### 🏢 Public Portal
- **Dynamic Content:** Home, About, News, Campaigns, Projects, and Gallery pages dynamically powered by the CMS.
- **Online Donations:** Integrated with **Razorpay** to accept online donations seamlessly with automated PDF receipt generation.
- **Career Portal:** Job listings and an online application system for prospective employees and volunteers.
- **Member Registration:** Public portal for individuals to apply for NGO membership.

### 🛡️ Admin & Role Management (RBAC)
- **Role-Based Access Control:** Define custom roles (Super Admin, Editor, Staff, etc.) and assign granular permissions for different modules.
- **Audit Logs:** Comprehensive security audit logging to track user actions, logins, and data modifications.
- **User Activation & Onboarding:** Automated email generation with secure login credentials upon user/member activation.

### 👥 Membership Management
- **Automated Workflows:** Approve/reject membership applications with automated email offer letters.
- **Member Dashboard:** A dedicated portal for members to view their profile, pay membership fees, view notices, download ID cards, and access their offer letters.
- **Designations & Hierarchies:** Assign specific designations to members.

### 💰 Finance & Donor Management
- **Donation Tracking:** Record online and offline donations, track campaigns, and generate automated PDF receipts.
- **Expense & Income Management:** Keep track of the NGO's overall finances.
- **Donor Directory:** Maintain a database of donors and funding partners.

### 🎯 Campaigns & Projects
- **Project Tracking:** Create and showcase ongoing or completed projects with image galleries and project status.
- **Fundraising Campaigns:** Launch dedicated fundraising campaigns with goals and direct donation links.

### 📢 Content Management System (CMS)
- **Media Gallery:** Manage images, certificates, and achievements.
- **Notices & News:** Publish announcements, press releases, and internal notices for members.
- **Legal Policies:** Easily manage terms & conditions, privacy policies, and refund policies.

### ⚙️ Advanced Settings & Integrations
- **Dynamic Settings:** Manage organization details (Name, Logo, Address, Social Links) directly from the dashboard.
- **Email/SMTP Integration:** Reliable email notifications via integrated PHP Mailer/SMTP for password resets, receipts, and offer letters.
- **Razorpay Integration:** Easy configuration for test and live payment gateways.
- **Security Features:** CSRF protection, secure password hashing, and encrypted sensitive database fields.

## 🛠️ Technology Stack
- **Backend:** PHP 8+ (Custom MVC Architecture)
- **Frontend:** HTML5, CSS3, JavaScript, Tabler/Bootstrap 5 UI Framework
- **Database:** MySQL / MariaDB
- **PDF Generation:** FPDF/TCPDF (or similar) for dynamic receipts and ID Cards
- **Payments:** Razorpay API integration

## 📦 Installation & Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/krsaurabhmca/NGO-ERP.git
   cd NGO-ERP
   ```

2. **Database Setup**
   - Create a new MySQL database.
   - Import the provided SQL schema (if applicable, typically located in `database/` or `sql/`).

3. **Environment Configuration**
   - Copy `.env.example` to `.env`.
   - Update your database credentials and `BASE_URL` in the `.env` file.

4. **Web Server Configuration**
   - Point your web server's document root to the project folder.
   - If using Apache, ensure `mod_rewrite` is enabled to support the routing system via `.htaccess`.

5. **Default Login**
   - Access the admin panel at `yourdomain.com/auth` (or `/admin`).
   - Please refer to your internal documentation for the default super admin credentials.

## 🔒 Security Notes
- Ensure your `.env` file is never publicly accessible.
- The `config/config.php` utilizes encryption functions for sensitive settings (like SMTP passwords and Razorpay API secrets).

## 📄 License
This project is proprietary and confidential. Ensure proper licensing is attached before commercial distribution.
