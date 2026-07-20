# Software Requirements Specification (SRS) for Grape Cultivation Advisory Chat System

## 1. Introduction

### 1.1 Purpose
The purpose of this document is to define the software requirements for the Grape Cultivation Advisory Chat System. It aims to provide a digital platform that connects grape farmers with agricultural experts for real-time advisory consultations, knowledge sharing, and payment management.

### 1.2 Scope
The System will:
- Allow users to register, log in, and manage their profiles (Farmers, Experts, Admins).
- Enable Farmers to create advisory consultations with descriptions and images.
- Allow Experts to accept, reject, and respond to consultations via real-time chat.
- Support a full consultation lifecycle from creation through payment, chat, and completion.
- Process consultation payments ($29.99 fee) with transaction image upload and admin verification.
- Handle expert payouts with an 80/20 revenue split (expert/platform).
- Enable Experts to publish knowledge-base articles and Admins to review/approve them.
- Provide role-based access control (RBAC) with granular permissions.
- Send in-app notifications for consultations, messages, articles, and system events.
- Generate PDF invoices for completed payments.
- Log all system activities for audit purposes.

### 1.3 Definitions
- **Admin**: System administrator who manages users, roles, permissions, consultations, payments, and platform operations.
- **Expert**: Agricultural expert who provides advisory services, responds to consultations, and publishes articles.
- **Farmer**: Grape farmer who seeks advice, creates consultations, uploads images, makes payments, and chats with experts.
- **Consultation**: An advisory session between a Farmer and an Expert regarding grape cultivation.
- **RBAC**: Role-Based Access Control
- **DDD**: Domain-Driven Design

## 2. Overall Description

### 2.1 Product Perspective
Web-based system accessible via browser, deployed on an Apache server with MySQL database. The system includes a separate WebSocket server for real-time chat functionality.

### 2.2 User Classes
- **Admin**: Full system access, manages users, roles, permissions, consultations, payments, and payouts.
- **Expert**: Can accept consultations, chat with farmers, publish articles, and view payouts.
- **Farmer**: Can create consultations, upload images, make payments, chat with experts, and browse articles.

### 2.3 Assumptions
- Users have basic OS/browser knowledge.
- Farmers and Experts have reliable internet access.
- Experts are pre-approved domain specialists in grape cultivation.

## 3. System Modules and Features

### Module: Authentication & User Management

#### 1. User Registration/Login
This sub-module allows users to create accounts and securely access the system.

- **User Registration:**
    - Users (Farmers) can sign up by providing username, email, password, phone, and address.
    - Passwords are securely stored using hashing algorithms.
    - Email verification via token sent to user's email address.
    - Google reCAPTCHA v2 integration to prevent automated registrations.
    - Google OAuth 2.0 login option for quick registration/sign-in.

- **User Login:**
    - Users can log in using email and password.
    - Implements session-based authentication.
    - Supports error handling for incorrect credentials.
    - Password reset/recovery via email token.

- **Expert Account Creation:**
    - Admin can create Expert accounts directly (no self-registration for Experts).

#### 2. Role Assignment (Admin, Expert, Farmer)
This part of the module controls what users can see and do based on their assigned role.

- **Role Definitions:**
    - **Admin**: Full access, including user and system management.
    - **Expert**: Can manage consultations, chat with farmers, publish articles, and view payouts.
    - **Farmer**: Can create consultations, make payments, chat with experts, browse articles, and manage profile.

- **Role Assignment:**
    - Roles are assigned during account creation or by Admin after account activation.
    - Role-based access control (RBAC) ensures users only access permitted features.
    - Roles are managed via the `master_data` table and linked to permissions through `user_type_permissions`.

- **Permission Management:**
    - Admin can define permissions and assign them to user types.
    - Permissions are checked on every request via middleware.

#### 3. Profile Management
Users have the ability to view and update their personal information.

- **Profile Viewing:**
    - Users can view their details such as username, email, phone, address, and profile image.
    - Farmers can view consultation history, payment history, and notifications.

- **Profile Editing:**
    - Users can update their contact information and password.
    - Profile image upload support.
    - Changes are validated and saved securely.

- **Security Features:**
    - Email verification for new accounts.
    - Password reset via email.

### Module: Consultation Management

#### 1. Create Consultation
This functionality allows Farmers to create advisory consultation requests.

- **Process:**
    - Farmer provides a title, detailed description of the grape cultivation issue.
    - Farmer can upload multiple images (disease symptoms, plant photos, etc.).
    - Consultation is created in the "pending" status.
    - System generates a unique idempotency key to prevent duplicate submissions.

- **Features:**
    - Image upload for visual diagnosis.
    - Consultation fee of $29.99 is required before expert assignment.
    - Expiration mechanism for unpaid consultations.

#### 2. Consultation Lifecycle
The consultation progresses through a defined state machine with 11 statuses.

- **Status Flow:**
    - `pending` → `assigned` (Admin assigns an Expert or expert accepts)
    - `assigned` → `expert_accepted` (Expert confirms readiness)
    - `expert_accepted` → `awaiting_payment` (Farmer is prompted to pay)
    - `awaiting_payment` → `payment_submitted` (Farmer submits payment proof)
    - `payment_submitted` → `accepted` (Admin verifies payment)
    - `accepted` → `chat_started` (Chat session begins)
    - `chat_started` → `completed` (Consultation resolved)
    - Any state can transition to `closed`, `rejected`, or `expired`.

- **Features:**
    - Admin can view all consultations, assign experts, and update statuses.
    - Experts can view assigned consultations and accept/reject them.
    - Farmers can track their consultation status in real time.

#### 3. Consultation Viewing
Different views for each user role.

- **Admin View:**
    - List all consultations with filters by status.
    - View consultation details including images, farmer info, assigned expert.
    - Assign/reassign experts to consultations.
    - Approve/reject payments and release expert payouts.

- **Expert View:**
    - List assigned consultations.
    - View consultation details and farmer profile.
    - Accept or reject consultation requests.
    - Access consultation hub for active consultations.

- **Farmer View:**
    - List personal consultations with status tracking.
    - View consultation details, assigned expert, and chat history.
    - Access payment portal for pending consultations.

### Module: Real-Time Chat System

#### 1. Live Messaging
This feature enables real-time communication between Farmers and Experts within an active consultation.

- **Process:**
    - Chat is available only after a consultation status reaches "chat_started".
    - Messages are delivered in real-time via WebSocket (Ratchet).
    - Text messages with support for reply-to functionality.
    - Image attachment support within chat messages.

- **Features:**
    - Real-time delivery via WebSocket on port 8080.
    - Messages are persisted in the database for history.
    - Typing indicators and read status (via WebSocket).
    - Each consultation acts as a separate chat room.

#### 2. Conversation History
Allows users to view past messages within a consultation.

- **Features:**
    - Retrieve full message history for any consultation.
    - Messages displayed in chronological order.
    - Support for pagination of large conversations.
    - Available for Farmers, Experts, and Admins.

### Module: Payment & Payout System

#### 1. Consultation Payment
Farmers pay a consultation fee to access expert advice.

- **Process:**
    - Consultation fee is fixed at $29.99.
    - Farmer uploads a transaction image as proof of payment.
    - Admin verifies the payment and approves/rejects it.
    - Payment status tracked: PENDING → SUBMITTED → PAID → REJECTED → REFUNDED.

- **Features:**
    - Idempotency key prevents duplicate payment submissions.
    - Payment history viewable by Farmer.
    - Admin can process refunds with partial or full refund amounts.

#### 2. Expert Payouts
Experts receive compensation for completed consultations.

- **Revenue Split:**
    - Expert receives 80% of the consultation fee.
    - Platform retains 20% as service fee.
    - Gross amount: $29.99, Expert net: ~$23.99, Platform fee: ~$6.00.

- **Payout Process:**
    - Admin reviews completed consultations.
    - Admin releases payout to the Expert.
    - Payout status tracked: pending → released.
    - Payout history viewable by Expert.

#### 3. Invoice Generation
Automated invoice creation for completed payments.

- **Features:**
    - PDF invoice generation using Dompdf.
    - Invoice includes consultation details, fee breakdown, and payment info.
    - Downloadable by Farmer from their consultation history.

### Module: Knowledge Base (Articles)

#### 1. Article Management
Experts can publish grape cultivation articles for public access.

- **Process:**
    - Expert creates article with title, content, and optional cover image.
    - Article is submitted with "pending" status for Admin review.
    - Admin reviews and accepts or rejects with a rejection note.
    - Accepted articles are published publicly.

- **Features:**
    - Rich text content for articles.
    - Multiple image attachments per article.
    - Article status tracking: draft/pending → accepted → rejected.

#### 2. Public Article Browsing
Public-facing article directory for knowledge sharing.

- **Features:**
    - Browse all accepted articles.
    - View article details with full content and author info.
    - Filter and search capabilities.

### Module: Notification System

#### 1. In-App Notifications
Real-time notifications delivered within the application dashboard.

- **Types of Notifications:**
    - **System**: General platform announcements.
    - **Expert Reply**: When an expert responds to a consultation.
    - **Announcement**: Platform-wide announcements.
    - **Consultation Created/Assigned/Accepted/Rejected**: Status updates.
    - **Message Received**: New chat message in an active consultation.
    - **Article Created/Accepted/Rejected**: Article workflow updates.

- **Features:**
    - Role-based notification delivery.
    - Notification includes type, message, and link to relevant resource.
    - Read/unread tracking.
    - Unread count displayed in the dashboard.
    - Time-ago display for notification timestamps.

#### 2. Email Notifications
Email communication for critical events.

- **Types of Email Alerts:**
    - Email verification link on registration.
    - Password reset link.
    - (Extensible for future consultation/payment notifications.)

### Module: System Administration

#### 1. Dashboard
Role-based dashboards with key metrics and activity feeds.

- **Admin Dashboard:**
    - Overview of platform statistics (users, consultations, payments).
    - Recent system activity log.
    - Quick links to management functions.

- **Expert Dashboard:**
    - Assigned consultations overview.
    - Recent notifications.
    - Payout summary.

- **Farmer Dashboard:**
    - Active consultations status.
    - Payment history summary.
    - Recent notifications.

#### 2. User Management (Admin)
Admin can view and manage all platform users.

- **Features:**
    - List all users with filters (role, status).
    - View user details.
    - Assign/change user roles.
    - Activate/deactivate user accounts.

#### 3. Role & Permission Management (Admin)
Admin can define roles and assign granular permissions.

- **Role Management:**
    - Create, edit, and delete user roles.
    - Roles stored in the master_data lookup table.

- **Permission Management:**
    - Define permission keys and descriptions.
    - Assign permissions to user types via many-to-many relationship.
    - Permissions are reloaded into session for enforcement.

#### 4. System Activity Log
Audit trail of all significant user actions.

- **Features:**
    - Log activities with user, role, description, and timestamp.
    - Viewable by Admin on the dashboard.
    - Persistent storage for auditing purposes.

## 4. Functional Requirements

- **FR1**: The system shall allow users to register as Farmers with username, email, password, phone, and address.
- **FR2**: The system shall allow users to log in and log out securely using email and password.
- **FR3**: The system shall support Google OAuth 2.0 authentication for login and registration.
- **FR4**: The system shall verify user email addresses via a token-based email verification process.
- **FR5**: The system shall allow users to reset forgotten passwords via email.
- **FR6**: The system shall implement role-based access control with three roles: Admin, Expert, Farmer.
- **FR7**: The system shall allow Admin to create, edit, and delete user roles.
- **FR8**: The system shall allow Admin to assign permissions to user types.
- **FR9**: The system shall allow Farmers to create consultation requests with title, description, and images.
- **FR10**: The system shall support a multi-status consultation lifecycle with at least 11 distinct statuses.
- **FR11**: The system shall allow Admin to view all consultations and assign Experts.
- **FR12**: The system shall allow Experts to view, accept, and reject assigned consultations.
- **FR13**: The system shall provide real-time chat between Farmer and Expert via WebSocket.
- **FR14**: The system shall persist chat messages and allow retrieval of conversation history.
- **FR15**: The system shall require a $29.99 consultation fee before expert services are delivered.
- **FR16**: The system shall allow Farmers to submit payment proof by uploading a transaction image.
- **FR17**: The system shall allow Admin to verify, approve, or reject submitted payments.
- **FR18**: The system shall calculate expert payouts at 80% of the consultation fee.
- **FR19**: The system shall allow Admin to release payouts to Experts for completed consultations.
- **FR20**: The system shall generate PDF invoices for completed payments.
- **FR21**: The system shall allow Experts to create, edit, and delete knowledge-base articles.
- **FR22**: The system shall allow Admin to review and accept/reject submitted articles.
- **FR23**: The system shall display accepted articles publicly for browsing.
- **FR24**: The system shall deliver in-app notifications for consultation, message, article, and system events.
- **FR25**: The system shall track read/unread status for notifications.
- **FR26**: The system shall log all system activities with user, role, and timestamp for audit purposes.
- **FR27**: The system shall allow Admin to manage users (view, assign roles, activate/deactivate).
- **FR28**: The system shall integrate Google reCAPTCHA v2 on the registration form.

## 5. Non-Functional Requirements

- **NFR1 - Performance**: The system shall support 100+ concurrent users with real-time chat functionality.
- **NFR2 - Scalability**: The system architecture shall support horizontal scaling of the WebSocket server.
- **NFR3 - Security**: All passwords shall be hashed; session-based authentication with RBAC enforcement.
- **NFR4 - Usability**: The system shall provide a responsive, mobile-compatible UI using Tailwind CSS.
- **NFR5 - Availability**: The system shall target 99.9% uptime for the web application.
- **NFR6 - Maintainability**: The codebase shall follow Domain-Driven Design principles with PSR-12 coding standards.
- **NFR7 - Reliability**: The payment system shall use idempotency keys to prevent duplicate transactions.

## 6. External Interfaces

- **Hardware**: Standard PC or mobile device with a web browser and internet connection.
- **Software**: 
    - PHP 8.1+ (custom DDD framework)
    - MySQL database
    - Apache web server (XAMPP)
    - Composer for dependency management
    - WebSocket server (Ratchet) for real-time chat
- **Third-Party Services**:
    - Google OAuth 2.0 (social login)
    - Google reCAPTCHA v2 (spam prevention)
    - Gmail SMTP (email delivery via PHPMailer)
    - Dompdf (PDF invoice generation)
