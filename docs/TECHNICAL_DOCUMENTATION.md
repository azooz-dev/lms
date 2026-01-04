# LMS Technical Documentation

**Version**: 1.0.0  
**Last Updated**: December 27, 2024  
**Platform**: Laravel 10 Learning Management System

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [System Architecture](#2-system-architecture)
3. [Technology Stack](#3-technology-stack)
4. [Project Structure](#4-project-structure)
5. [Core Domains & Modules](#5-core-domains--modules)
6. [Data Flow & Request Lifecycle](#6-data-flow--request-lifecycle)
7. [Authentication & Authorization](#7-authentication--authorization)
8. [Database Design](#8-database-design)
9. [Error Handling & Logging](#9-error-handling--logging)
10. [Testing Strategy](#10-testing-strategy)
11. [Environment Setup](#11-environment-setup)
12. [Deployment & CI/CD](#12-deployment--cicd)
13. [Coding Standards & Conventions](#13-coding-standards--conventions)
14. [Scalability & Future Improvements](#14-scalability--future-improvements)

---

## 1. Project Overview

### 1.1 Purpose of the System

The LMS (Learning Management System) is a comprehensive online education platform designed to facilitate the creation, delivery, and management of educational courses. The system provides a full-featured marketplace where instructors can publish courses, students can purchase and consume educational content, and administrators can manage the entire platform.

### 1.2 High-Level Business Problem It Solves

This platform addresses several key challenges in the online education space:

- **Content Delivery**: Enables structured delivery of educational content through courses, sections, and lectures
- **Monetization**: Provides instructors with a platform to monetize their expertise through course sales
- **User Engagement**: Facilitates interaction between students and instructors through Q&A and reviews
- **Platform Management**: Gives administrators tools to manage users, content, and platform operations
- **E-commerce Integration**: Handles payments, coupons, and order management

### 1.3 Target Users and Stakeholders

The system serves three distinct user roles:

| Role | Description | Primary Functions |
|------|-------------|-------------------|
| **Admin** | Platform administrators | User management, content approval, platform settings, reporting |
| **Instructor** | Content creators | Course creation, student management, Q&A responses, earnings tracking |
| **User (Student)** | Learners | Course browsing, purchasing, learning, reviewing |

### 1.4 System Boundaries and Responsibilities

**In Scope:**
- User registration and authentication
- Course creation and management
- Payment processing (via Stripe)
- Order and coupon management
- Reviews and Q&A system
- Blog/content management
- Administrative dashboard
- Wishlist functionality

**Out of Scope (External Dependencies):**
- Payment processing infrastructure (delegated to Stripe)
- Email delivery (delegated to SMTP provider)
- Video hosting (external video links supported)

---

## 2. System Architecture

### 2.1 Overall Architecture Style

The LMS follows a **Traditional Monolith** architecture with clear separation of concerns using:

- **Repository Pattern**: Abstracts data access layer
- **Service Layer**: Encapsulates business logic
- **MVC Pattern**: Laravel's Model-View-Controller implementation
- **Event-Driven Communication**: For decoupled side effects

```
┌─────────────────────────────────────────────────────────────────┐
│                        HTTP Request                              │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                     Middleware Stack                             │
│  (Authentication, CSRF, Role Check, Session Management)         │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                       Controllers                                │
│  (Admin, Instructor, User, Frontend, Backend)                   │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                      Service Layer                               │
│  (CourseService, OrderService, UserService, etc.)               │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    Repository Layer                              │
│  (CourseRepository, OrderRepository, UserRepository, etc.)      │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                     Eloquent Models                              │
│  (Course, User, Order, Payment, Review, etc.)                   │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                        Database                                  │
│                        (MySQL)                                   │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Major System Components and Interactions

#### Component Overview

| Component | Responsibility | Dependencies |
|-----------|---------------|--------------|
| **Controllers** | Request handling, response generation | Services, Views |
| **Services** | Business logic encapsulation | Repositories, External APIs |
| **Repositories** | Data access abstraction | Eloquent Models |
| **Models** | Data structure, relationships | Database |
| **Events/Listeners** | Decoupled side effects | Mail, Notifications |
| **Middleware** | Request filtering, authorization | Auth, Session |

#### Controller Hierarchy

```
Controllers/
├── AdminController           # Admin dashboard and profile management
├── InstructorController      # Instructor dashboard and profile
├── UserController           # Student dashboard and profile
├── ProfileController        # Shared profile operations
├── Auth/                    # Laravel Breeze authentication
│   ├── AuthenticatedSessionController
│   ├── RegisteredUserController
│   ├── PasswordController
│   └── ...
├── backend/                 # Admin panel operations
│   ├── CategoryController
│   ├── CourseController
│   ├── OrderController
│   ├── CouponController
│   ├── ReviewController
│   └── ...
└── frontend/               # Public-facing operations
    ├── IndexController
    ├── CartController
    └── WishListController
```

### 2.3 Request Lifecycle Overview

1. **Request Entry**: HTTP request enters through `public/index.php`
2. **Middleware Processing**: Global middleware (CORS, CSRF, Session) executes
3. **Route Matching**: Routes from `routes/web.php` (which loads modular route files)
4. **Role Middleware**: Custom `Role` middleware validates user access
5. **Controller Action**: Business logic executed via Services
6. **Response Generation**: View rendered or JSON returned

### 2.4 Design Decisions and Trade-offs

| Decision | Rationale | Trade-off |
|----------|-----------|-----------|
| **Monolith Architecture** | Simpler deployment, easier to develop | Limited horizontal scaling |
| **Repository Pattern** | Testability, swappable implementations | Added complexity layer |
| **Single Auth Guard** | Simplified authentication flow | All users share same auth mechanism |
| **Blade Templates** | Laravel native, no additional tooling | Limited SPA capabilities |
| **External Video Links** | Reduced storage/bandwidth costs | Dependency on external services |

---

## 3. Technology Stack

### 3.1 Backend Technologies

| Technology | Version | Purpose |
|------------|---------|---------|
| **PHP** | ^8.2.4 | Core programming language |
| **Laravel** | ^10.0 | Web application framework |
| **Laravel Sanctum** | ^3.2 | API token authentication |
| **Laravel Breeze** | ^1.29 | Authentication scaffolding |

#### Why Laravel 10?
- Mature ecosystem with extensive documentation
- Strong ORM (Eloquent) for database operations
- Built-in security features (CSRF, SQL injection prevention)
- Robust testing support
- Active community and long-term support

### 3.2 Frontend Technologies

| Technology | Purpose |
|------------|---------|
| **Blade Templates** | Server-side templating |
| **TailwindCSS** | Utility-first CSS framework |
| **Vite** | Frontend build tool |
| **TinyMCE** | Rich text editor for course content |

### 3.3 Database & Storage

| Technology | Purpose |
|------------|---------|
| **MySQL** | Primary relational database |
| **Eloquent ORM** | Database abstraction layer |
| **File Storage** | Local storage for images/videos |

### 3.4 Third-Party Services and Integrations

| Package | Version | Purpose |
|---------|---------|---------|
| **spatie/laravel-permission** | ^6.9 | Role-based access control |
| **stripe/stripe-php** | ^14.8 | Payment processing |
| **maatwebsite/excel** | ^3.1 | Excel import/export |
| **barryvdh/laravel-dompdf** | ^2.2 | PDF generation |
| **yajra/laravel-datatables** | ^10.11 | Server-side data tables |
| **anayarojo/shoppingcart** | ^4.2 | Shopping cart functionality |
| **intervention/image** | ^3.6 | Image manipulation |

### 3.5 Development Tools

| Tool | Purpose |
|------|---------|
| **Laravel Pint** | PHP code style fixer |
| **PHPUnit** | ^10.0 - Testing framework |
| **Faker** | Test data generation |
| **Laravel Sail** | Docker development environment |

---

## 4. Project Structure

### 4.1 Directory Structure Explanation

```
lms/
├── app/                    # Application source code
│   ├── Checkout/          # Checkout process classes (Assumed Design)
│   ├── Console/           # Artisan commands
│   ├── Enums/             # PHP 8.1+ enum definitions
│   ├── Events/            # Event classes
│   ├── Exceptions/        # Exception handlers
│   ├── Exports/           # Excel export classes
│   ├── Helpers/           # Global helper functions
│   ├── Http/              # Controllers, Middleware, Requests
│   ├── Imports/           # Excel import classes
│   ├── Listeners/         # Event listeners
│   ├── Mail/              # Mailable classes
│   ├── Models/            # Eloquent models
│   ├── Notifications/     # Notification classes
│   ├── Payment/           # Payment gateway integration (Assumed Design)
│   ├── Providers/         # Service providers
│   ├── Repositories/      # Repository pattern implementations
│   ├── Services/          # Business logic services
│   └── View/              # View composers
├── bootstrap/             # Framework bootstrap files
├── config/                # Configuration files
├── database/              # Migrations, factories, seeders
├── public/                # Public assets and entry point
├── resources/             # Views, assets, language files
│   ├── views/
│   │   ├── admin/         # Admin panel views (47 files)
│   │   ├── instructor/    # Instructor views (22 files)
│   │   ├── frontend/      # Public views (42 files)
│   │   ├── auth/          # Authentication views
│   │   ├── components/    # Blade components
│   │   └── layouts/       # Layout templates
├── routes/                # Route definitions
│   ├── web.php           # Main web routes (loads modular files)
│   ├── auth.php          # Authentication routes
│   ├── api.php           # API routes
│   └── web/              # Modular route files (empty - inline in main files)
├── storage/              # Logs, cache, uploaded files
├── tests/                # Test suites
│   ├── Feature/          # Feature tests
│   └── Unit/             # Unit tests
└── vendor/               # Composer dependencies
```

### 4.2 Purpose of Each Major Folder/Module

#### `app/Enums/`
PHP 8.1+ backed enums providing type-safe constants:
- `UserRole` - Admin, Instructor, User
- `UserStatus` - Active, Inactive states
- `CourseStatus` - Active, Inactive, Draft
- `PaymentStatus` - Pending, Completed, Failed
- `ReviewStatus` - Approved, Pending, Rejected
- `AlertType` - Notification alert types

#### `app/Services/`
Business logic layer with 8 service classes:
- `CartService` - Shopping cart operations
- `CheckoutService` - Checkout process management
- `CouponService` - Coupon validation and application
- `CourseService` - Course CRUD and management
- `DashboardService` - Dashboard statistics
- `FileUploadService` - Image/video upload handling
- `OrderService` - Order processing
- `UserService` - User management operations

#### `app/Repositories/`
Data access abstraction with interface contracts:
- Base `RepositoryInterface` defining common operations
- Specific interfaces per entity (CourseRepositoryInterface, etc.)
- Concrete implementations (CourseRepository, etc.)

### 4.3 Naming Conventions and Standards

| Element | Convention | Example |
|---------|------------|---------|
| **Controllers** | PascalCase + Controller suffix | `CourseController` |
| **Models** | PascalCase singular | `Course`, `User` |
| **Services** | PascalCase + Service suffix | `CourseService` |
| **Repositories** | PascalCase + Repository suffix | `CourseRepository` |
| **Interfaces** | PascalCase + Interface suffix | `CourseRepositoryInterface` |
| **Migrations** | snake_case with date prefix | `2024_04_09_171625_create_courses_table.php` |
| **Views** | snake_case | `course_details.blade.php` |
| **Routes** | kebab-case for URLs | `/admin/all-courses` |

---

## 5. Core Domains & Modules

### 5.1 User Management Module

**Business Responsibility**: Manages all user-related operations including registration, profile management, and role assignment.

**Models**:
- `User` - Core user entity with role-based access

**Public Interfaces**:
- `AdminController` - Admin user management
- `InstructorController` - Instructor profile operations
- `UserController` - Student profile operations

**Services**:
- `UserService` - User CRUD, profile updates, password changes

**Repository**:
- `UserRepository` implementing `UserRepositoryInterface`

**Key Attributes (User Model)**:
| Attribute | Type | Description |
|-----------|------|-------------|
| name | string | Full name |
| username | string | Unique username |
| email | string | Unique email |
| password | hashed | Bcrypt hashed password |
| role | UserRole enum | admin/instructor/user |
| status | UserStatus enum | active/inactive |
| photo | string | Profile photo path |
| phone | string | Contact number |
| address | text | Physical address |
| bio | text | User biography |
| last_seen | timestamp | Last activity timestamp |

---

### 5.2 Course Management Module

**Business Responsibility**: Complete course lifecycle management including creation, sections, lectures, and goals.

**Models**:
- `Course` - Main course entity
- `Course_Section` - Course section/chapter
- `Course_Lecture` - Individual lecture within section
- `Course_goal` - Learning objectives
- `Category` - Course categories
- `SubCategory` - Course subcategories

**Public Interfaces**:
- `backend/CourseController` - Admin course management
- `InstructorController` - Instructor course creation

**Services**:
- `CourseService` - Course CRUD, section/lecture management, goal management

**Key Workflows**:

1. **Course Creation Flow**:
   ```
   Instructor submits form → CourseService.createCourse() →
   Upload image → Upload video → Create goals →
   Save course (status: active) → Redirect with success
   ```

2. **Section/Lecture Management**:
   ```
   Add Section → CourseService.createSection() →
   Add Lecture → CourseService.createLecture() →
   Update content → CourseService.updateLecture()
   ```

**Course Relationships**:
```
Course
├── belongsTo: User (instructor)
├── belongsTo: Category
├── belongsTo: SubCategory
├── hasMany: Course_Section
├── hasMany: Course_Lecture
├── hasMany: Course_goal
├── hasMany: Order
├── hasMany: Question
└── hasMany: Review
```

---

### 5.3 Order & Payment Module

**Business Responsibility**: Handles course purchases, payment processing, and order management.

**Models**:
- `Order` - Purchase records
- `Payment` - Payment transaction records
- `Coupon` - Discount codes

**Public Interfaces**:
- `backend/OrderController` - Admin order management
- `frontend/CartController` - Shopping cart operations

**Services**:
- `CartService` - Cart add/remove/update
- `CheckoutService` - Checkout process
- `OrderService` - Order creation and management
- `CouponService` - Coupon validation and application

**Repositories**:
- `OrderRepository` implementing `OrderRepositoryInterface`
- `PaymentRepository` implementing `PaymentRepositoryInterface`
- `CouponRepository` implementing `CouponRepositoryInterface`

**Key Workflows**:

1. **Purchase Flow**:
   ```
   Add to Cart → Apply Coupon (optional) →
   Checkout → Stripe Payment → Create Order →
   Create Payment Record → Dispatch OrderPlaced Event →
   Send Confirmation Email → Notify Instructors
   ```

**Events**:
- `OrderPlaced` → Triggers `SendOrderConfirmationEmail`, `NotifyInstructorsOfNewOrder`
- `OrderConfirmed` → Triggers `SendOrderConfirmedNotification`

---

### 5.4 Review & Q&A Module

**Business Responsibility**: Manages student feedback and instructor-student communication.

**Models**:
- `Review` - Course reviews with ratings
- `Question` - Student questions on courses
- `ReplyQuestion` - Instructor responses

**Public Interfaces**:
- `backend/ReviewController` - Admin review moderation
- `backend/QuestionController` - Admin Q&A management

**Repositories**:
- `ReviewRepository` implementing `ReviewRepositoryInterface`
- `QuestionRepository` implementing `QuestionRepositoryInterface`

**Events**:
- `ReviewSubmitted` → Triggers `NotifyInstructorOfNewReview`

---

### 5.5 Blog Module

**Business Responsibility**: Content marketing and knowledge sharing platform.

**Models**:
- `Post` - Blog articles
- `BlogCategory` - Blog categorization
- `Tag` - Post tagging (many-to-many with Post)
- `Comment` - Post comments

**Public Interfaces**:
- `backend/BlogController` - Admin blog management

**Repositories**:
- `PostRepository` implementing `PostRepositoryInterface`
- `BlogCategoryRepository` implementing `BlogCategoryRepositoryInterface`
- `TagRepository` implementing `TagRepositoryInterface`

---

### 5.6 Wishlist Module

**Business Responsibility**: Allows students to save courses for later purchase.

**Models**:
- `Wish_list` - User-course association

**Public Interfaces**:
- `frontend/WishListController` - Wishlist operations

**Repositories**:
- `WishListRepository` implementing `WishListRepositoryInterface`

---

## 6. Data Flow & Request Lifecycle

### 6.1 From HTTP Request to Response

```
┌──────────────────────────────────────────────────────────────────────────┐
│ 1. HTTP REQUEST (public/index.php)                                       │
└──────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌──────────────────────────────────────────────────────────────────────────┐
│ 2. GLOBAL MIDDLEWARE                                                      │
│    - TrustProxies                                                         │
│    - HandleCors                                                           │
│    - PreventRequestsDuringMaintenance                                     │
│    - ValidatePostSize                                                     │
│    - TrimStrings                                                          │
│    - ConvertEmptyStringsToNull                                           │
└──────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌──────────────────────────────────────────────────────────────────────────┐
│ 3. WEB MIDDLEWARE GROUP                                                   │
│    - EncryptCookies                                                       │
│    - AddQueuedCookiesToResponse                                          │
│    - StartSession                                                         │
│    - ShareErrorsFromSession                                              │
│    - VerifyCsrfToken                                                     │
│    - SubstituteBindings                                                  │
└──────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌──────────────────────────────────────────────────────────────────────────┐
│ 4. ROUTE MIDDLEWARE                                                       │
│    - auth (Authenticate)                                                  │
│    - roles (Custom Role middleware)                                       │
│    - permission (Spatie Permission)                                       │
└──────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌──────────────────────────────────────────────────────────────────────────┐
│ 5. FORM REQUEST VALIDATION (if applicable)                               │
│    - app/Http/Requests/*.php                                              │
│    - Validates input before controller                                    │
└──────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌──────────────────────────────────────────────────────────────────────────┐
│ 6. CONTROLLER                                                             │
│    - Receives validated request                                           │
│    - Calls Service layer for business logic                              │
│    - Returns View or JSON response                                        │
└──────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌──────────────────────────────────────────────────────────────────────────┐
│ 7. SERVICE LAYER                                                          │
│    - Executes business logic                                              │
│    - Calls Repository for data operations                                 │
│    - Dispatches Events for side effects                                   │
└──────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌──────────────────────────────────────────────────────────────────────────┐
│ 8. REPOSITORY LAYER                                                       │
│    - Abstracts database operations                                        │
│    - Uses Eloquent Models                                                 │
└──────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌──────────────────────────────────────────────────────────────────────────┐
│ 9. HTTP RESPONSE                                                          │
│    - Blade view rendered                                                  │
│    - OR JSON response for AJAX                                            │
│    - OR Redirect with flash message                                       │
└──────────────────────────────────────────────────────────────────────────┘
```

### 6.2 Middleware Usage

The application uses middleware at multiple levels:

**Global Middleware** (runs on every request):
| Middleware | Purpose |
|------------|---------|
| `TrustProxies` | Handle load balancer/proxy headers |
| `HandleCors` | Cross-Origin Resource Sharing |
| `PreventRequestsDuringMaintenance` | Maintenance mode handling |
| `TrimStrings` | Trim whitespace from input |

**Route Middleware Aliases**:
| Alias | Middleware | Purpose |
|-------|------------|---------|
| `auth` | `Authenticate` | Require authentication |
| `guest` | `RedirectIfAuthenticated` | Redirect authenticated users |
| `roles` | `Role` (custom) | Role-based access control |
| `permission` | `Spatie\Permission\PermissionMiddleware` | Permission checking |
| `role` | `Spatie\Permission\RoleMiddleware` | Role checking |

**Custom Role Middleware Behavior** (`app/Http/Middleware/Role.php`):
1. Updates user's `last_seen` timestamp
2. Caches user's online status for 30 seconds
3. Validates user role against route requirement
4. Redirects to appropriate dashboard if unauthorized

### 6.3 Validation Flow

Form requests are used for input validation:
- Located in `app/Http/Requests/`
- 32 request classes covering various operations
- Automatically validates before reaching controller
- Returns validation errors with old input

**Example Request Classes**:
- `StoreAdminRequest`
- `UpdateAdminRequest`
- `ChangePasswordRequest`
- `ProfileUpdateRequest`
- `RegisterInstructorRequest`

### 6.4 Error Handling Strategy

The application handles errors through:

1. **Exception Handler** (`app/Exceptions/Handler.php`)
   - Catches all unhandled exceptions
   - Transforms exceptions to HTTP responses
   - Logs errors appropriately

2. **Form Request Validation**
   - Automatic validation error responses
   - Redirects back with errors and old input

3. **Flash Notifications**
   - `FlashNotification` helper for user feedback
   - Success, error, warning, and info messages

4. **Error Views**
   - Custom error pages in `resources/views/errors/`
   - 404 and 500 error pages

---

## 7. Authentication & Authorization

### 7.1 Authentication Mechanism

The LMS uses **Laravel Breeze** for session-based authentication:

**Configuration** (`config/auth.php`):
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
],
```

**Authentication Features**:
- Email/password login
- User registration
- Password reset via email
- Email verification (configurable)
- Remember me functionality
- Session-based authentication

**Auth Controllers** (`app/Http/Controllers/Auth/`):
| Controller | Purpose |
|------------|---------|
| `AuthenticatedSessionController` | Login/logout |
| `RegisteredUserController` | User registration |
| `PasswordResetLinkController` | Forgot password |
| `NewPasswordController` | Reset password |
| `PasswordController` | Change password |
| `EmailVerificationController` | Email verification |

### 7.2 Roles and Permissions Model

The system implements a **dual authorization layer**:

#### Layer 1: Role-Based Access (Enum-based)

Using `UserRole` enum (`app/Enums/UserRole.php`):
```php
enum UserRole: string
{
    case ADMIN = 'admin';
    case INSTRUCTOR = 'instructor';
    case USER = 'user';
}
```

Custom `Role` middleware enforces role-based routing.

#### Layer 2: Permission-Based Access (Spatie)

Using **Spatie Laravel Permission** (`spatie/laravel-permission`):
- Granular permissions (e.g., `create.course`, `edit.user`)
- Permission groups for organization
- Database-stored permissions (flexible configuration)

**User Model Traits**:
```php
class User extends Authenticatable
{
    use HasRoles;      // Spatie roles/permissions
    use HasApiTokens; // Sanctum tokens
    // ...
}
```

### 7.3 Authorization Flow

```
Request
    │
    ▼
┌─────────────────────────────────────┐
│ 1. `auth` middleware                 │
│    - Check if user is authenticated │
│    - Redirect to login if not       │
└─────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────┐
│ 2. `roles` middleware                │
│    - Check user's role              │
│    - Redirect to appropriate        │
│      dashboard if wrong role        │
└─────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────┐
│ 3. `permission` middleware           │
│    - Check specific permission      │
│    - Return 403 if unauthorized     │
└─────────────────────────────────────┘
    │
    ▼
Controller Action
```

### 7.4 Security Considerations

| Protection | Implementation |
|------------|----------------|
| **CSRF Protection** | `VerifyCsrfToken` middleware on all POST/PUT/DELETE |
| **Password Hashing** | Bcrypt via `HashedCast` on password attribute |
| **SQL Injection** | Eloquent ORM parameterized queries |
| **XSS Prevention** | Blade `{{ }}` automatic escaping |
| **Session Security** | HTTP-only cookies, HTTPS support |
| **Rate Limiting** | Throttle middleware on auth routes |

---

## 8. Database Design

### 8.1 Database Philosophy

The database design follows these principles:

1. **Relational Integrity**: Foreign keys enforce relationships
2. **Normalized Structure**: Avoid data duplication
3. **Soft Deletes**: Not currently implemented (data is hard-deleted)
4. **Timestamps**: All tables include `created_at` and `updated_at`
5. **Enum Usage**: Status fields use string enums for flexibility

### 8.2 Important Tables and Relationships

#### Entity Relationship Overview

```
                    ┌──────────────┐
                    │    users     │
                    └──────────────┘
                           │
       ┌───────────────────┼───────────────────┐
       │                   │                   │
       ▼                   ▼                   ▼
┌──────────────┐    ┌──────────────┐    ┌──────────────┐
│   courses    │    │   orders     │    │  wish_lists  │
│(as instructor)│    │(as buyer)    │    │(as user)     │
└──────────────┘    └──────────────┘    └──────────────┘
       │                   │
       │                   │
       ▼                   ▼
┌──────────────┐    ┌──────────────┐
│course_sections│   │   payments   │
└──────────────┘    └──────────────┘
       │
       ▼
┌──────────────┐
│course_lectures│
└──────────────┘
```

#### Key Tables

**users**
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | varchar | Full name |
| username | varchar | Unique username |
| email | varchar | Unique email |
| password | varchar | Hashed password |
| role | varchar | admin/instructor/user |
| status | varchar | active/inactive |
| photo | varchar | Profile photo path |
| last_seen | timestamp | Last activity |

**courses**
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| category_id | bigint | FK to categories |
| sub_category_id | bigint | FK to sub_categories |
| instructor_id | bigint | FK to users |
| name | varchar | Course name |
| title | varchar | Course title |
| slug | varchar | URL-friendly identifier |
| description | text | Full description |
| selling_price | decimal | Original price |
| discount_price | decimal | Sale price |
| status | varchar | active/inactive |

**orders**
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | FK to users (buyer) |
| course_id | bigint | FK to courses |
| instructor_id | bigint | FK to users (seller) |
| course_title | varchar | Denormalized title |
| price | decimal | Purchase price |

**payments**
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | FK to users |
| order_id | varchar | External order reference |
| payment_method | varchar | stripe/etc |
| status | varchar | pending/completed/failed |
| total_amount | decimal | Payment amount |

### 8.3 Migration History

31 migrations covering the complete schema evolution:

1. **Core Tables** (2014-2019): users, password_resets, failed_jobs, personal_access_tokens
2. **Categories** (2024-03-30): categories table
3. **Subcategories** (2024-04-06): sub_categories table
4. **Courses** (2024-04-09): courses, course_goals
5. **Course Content** (2024-04-15): course_sections, course_lectures
6. **E-commerce** (2024-05): wish_lists, coupons, payments, orders, jobs
7. **Communication** (2024-05): questions, reply_questions, reviews
8. **Blog** (2024-05): blog_categories, posts, comments, tags, post_tag
9. **Settings** (2024-05): setting_smtps, site_settings
10. **Permissions** (2024-05): permission_tables (Spatie)
11. **Updates**: Various alter table migrations

### 8.4 Performance Considerations

**Implemented**:
- Primary keys on all tables
- Foreign key constraints for referential integrity
- Index on commonly queried fields (inferred from Eloquent usage)

**Recommended Improvements**:
- Add composite indexes on frequently joined columns
- Consider query result caching for dashboard statistics
- Implement database-level rate limiting for heavy queries

---

## 9. Error Handling & Logging

### 9.1 Exception Handling Strategy

**Default Handler** (`app/Exceptions/Handler.php`):
- Extends Laravel's base handler
- Handles authentication, validation, and HTTP exceptions
- Renders appropriate error pages (404, 500)

**Error Views** (`resources/views/errors/`):
- Custom 404 page
- Custom 500 page

### 9.2 Custom Error Responses

The application uses flash notifications for user-facing errors:

```php
// From FlashNotification helper
FlashNotification::error('Something went wrong');
FlashNotification::success('Operation completed');
FlashNotification::warning('Please check your input');
```

### 9.3 Logging Configuration

**Configuration** (`config/logging.php`):
- Default channel: `stack`
- Available channels: single, daily, slack, papertrail, stderr
- Log levels: debug, info, notice, warning, error, critical, alert, emergency

**Log Storage**: `storage/logs/laravel.log`

**Recommended Logging Levels**:
| Level | Use Case |
|-------|----------|
| `debug` | Development debugging |
| `info` | Normal operations logging |
| `warning` | Unusual but handled situations |
| `error` | Failed operations requiring attention |
| `critical` | System failures |

---

## 10. Testing Strategy

### 10.1 Test Types Used

The project includes:

| Type | Location | Purpose |
|------|----------|---------|
| **Feature Tests** | `tests/Feature/` | Full HTTP request/response testing |
| **Unit Tests** | `tests/Unit/` | Isolated component testing |
| **Auth Tests** | `tests/Feature/Auth/` | Authentication flow testing |

### 10.2 Test Organization

```
tests/
├── CreatesApplication.php    # Test bootstrap
├── TestCase.php              # Base test class
├── Feature/
│   ├── Auth/                 # 6 authentication tests
│   │   ├── AuthenticationTest.php
│   │   ├── EmailVerificationTest.php
│   │   ├── PasswordConfirmationTest.php
│   │   ├── PasswordResetTest.php
│   │   ├── PasswordUpdateTest.php
│   │   └── RegistrationTest.php
│   ├── ExampleTest.php
│   └── ProfileTest.php
└── Unit/
    └── ExampleTest.php
```

### 10.3 Testing Philosophy

The test suite focuses on:
1. **Authentication Flows**: Registration, login, password reset
2. **Profile Management**: Profile updates, password changes
3. **HTTP Responses**: Correct status codes and redirects

### 10.4 Running Tests

```bash
# Run all tests
php artisan test

# Run with stop on failure
php artisan test --stop-on-failure

# Run specific test file
php artisan test tests/Feature/ProfileTest.php

# Run with coverage (requires Xdebug)
php artisan test --coverage
```

### 10.5 Not Present in Code

- Integration tests for payment processing
- Repository/Service unit tests
- Database seeding for test data
- API endpoint testing

---

## 11. Environment Setup

### 11.1 Local Development Setup

**Prerequisites**:
- PHP 8.2.4 or higher
- Composer
- Node.js and NPM
- MySQL 8.0+

**Setup Steps**:

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd lms
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Node Dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Setup**
   ```bash
   # Configure .env with database credentials
   php artisan migrate
   php artisan db:seed  # If seeders exist
   ```

6. **Build Assets**
   ```bash
   npm run dev      # Development
   npm run build    # Production
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

### 11.2 Environment Variables

**Required Variables** (`.env`):
```bash
# Application
APP_NAME=LMS
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls

# Stripe
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
```

### 11.3 Docker Usage

**Laravel Sail** is available for containerized development:

```bash
# Start containers
./vendor/bin/sail up -d

# Run artisan commands
./vendor/bin/sail artisan migrate

# Stop containers
./vendor/bin/sail down
```

### 11.4 Common Setup Issues

| Issue | Solution |
|-------|----------|
| Missing PHP extensions | Install: `mbstring`, `xml`, `curl`, `mysql` |
| Permission denied on storage | `chmod -R 775 storage bootstrap/cache` |
| NPM build fails | Clear cache: `npm cache clean --force` |
| Database connection refused | Check MySQL service is running |
| Class not found | Run `composer dump-autoload` |

---

## 12. Deployment & CI/CD

### 12.1 Deployment Strategy

**Recommended Production Deployment**:

1. **Pre-deployment**:
   ```bash
   composer install --optimize-autoloader --no-dev
   npm run build
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Migration**:
   ```bash
   php artisan migrate --force
   ```

3. **Post-deployment**:
   ```bash
   php artisan queue:restart
   php artisan cache:clear
   ```

### 12.2 Environment Separation

| Environment | Purpose | Debug | Cache |
|-------------|---------|-------|-------|
| **local** | Development | ON | OFF |
| **staging** | Pre-production testing | ON | ON |
| **production** | Live system | OFF | ON |

### 12.3 CI/CD Pipeline

**Assumed Design** - Recommended GitHub Actions workflow:

```yaml
name: LMS CI/CD

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main, develop]

jobs:
  tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Install Dependencies
        run: composer install -q --no-ansi --no-interaction
      - name: Run Tests
        run: php artisan test
```

> **Note**: CI/CD configuration is not present in the codebase. The above is a recommended configuration.

---

## 13. Coding Standards & Conventions

### 13.1 Code Style Guidelines

The project uses **Laravel Pint** for code styling:

**Configuration** (`pint.json`):
```json
{
    "preset": "laravel",
    "rules": {
        "simplified_null_return": true,
        "braces": {
            "position_after_control_structures": "same"
        }
    }
}
```

**Running Code Style Fixes**:
```bash
./vendor/bin/pint
```

### 13.2 PHP Standards

- **PSR-4 Autoloading**: All classes follow PSR-4 namespace conventions
- **Strict Types**: Many files use `declare(strict_types=1)`
- **PHP 8.1+ Enums**: Used for type-safe constants
- **Constructor Promotion**: Used in services for dependency injection

### 13.3 Git Workflow

**Assumed Design** - Recommended branching strategy:

| Branch | Purpose |
|--------|---------|
| `main` | Production-ready code |
| `develop` | Integration branch |
| `feature/*` | New features |
| `hotfix/*` | Production fixes |

### 13.4 Commit Message Conventions

**Recommended format**:
```
type(scope): description

[optional body]

[optional footer]
```

**Types**:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation
- `style`: Code style changes
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Maintenance tasks

---

## 14. Scalability & Future Improvements

### 14.1 Known Limitations

1. **Monolithic Architecture**: Limits horizontal scaling
2. **Session-Based Auth**: May need stateless auth for API scaling
3. **File Storage**: Local storage limits multi-server deployment
4. **No Queue Workers**: Background jobs may block requests
5. **Limited Caching**: Dashboard queries could benefit from caching

### 14.2 Planned/Recommended Improvements

| Area | Current State | Recommendation |
|------|---------------|----------------|
| **Caching** | Minimal | Implement Redis caching for queries |
| **File Storage** | Local | Migrate to S3/cloud storage |
| **Queue Jobs** | Sync | Configure Redis queues with workers |
| **Search** | Database queries | Implement Elasticsearch/Algolia |
| **API** | Limited | Develop full REST API layer |
| **Testing** | Basic | Expand test coverage to 80%+ |

### 14.3 Scaling Strategies

**Vertical Scaling** (current architecture supports):
- Upgrade server resources (CPU, RAM)
- Database optimization (indexes, query tuning)
- OPcache for PHP performance

**Horizontal Scaling** (requires modifications):
1. Externalize session storage (Redis)
2. Migrate file storage to cloud (S3)
3. Load balancer configuration
4. Database read replicas

### 14.4 Performance Optimization Checklist

- [ ] Enable OPcache in production
- [ ] Configure Redis for session and cache
- [ ] Implement database query caching
- [ ] Add CloudFlare or CDN for static assets
- [ ] Enable HTTP/2 on web server
- [ ] Implement lazy loading for images
- [ ] Add database indexes on frequently queried columns
- [ ] Configure queue workers for background jobs

---

## Appendix

### A. Glossary

| Term | Definition |
|------|------------|
| **LMS** | Learning Management System |
| **RBAC** | Role-Based Access Control |
| **ORM** | Object-Relational Mapping |
| **DI** | Dependency Injection |
| **CRUD** | Create, Read, Update, Delete |

### B. External Resources

- [Laravel Documentation](https://laravel.com/docs/10.x)
- [Spatie Permission Documentation](https://spatie.be/docs/laravel-permission)
- [Stripe PHP Documentation](https://stripe.com/docs/api/php)

### C. Support Contacts

*Not Present in Code* - Define internal support contacts here.

---

**Document Revision History**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0.0 | 2024-12-27 | AI Documentation System | Initial documentation |
