# Exam Portal

A web-based online examination and student management system built with Laravel 11 and Breeze.

## Tech Stack

- **Backend:** Laravel 11
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Database:** MySQL
- **Authentication:** Laravel Breeze

## Roles

The system has three roles:

- **Admin** → manages users, classes, subjects, and enrollment requests
- **Lecturer** → creates and manages exams, grades student submissions
- **Student** → enrolls in classes, takes exams, views results

## Features

- Role-based access control with three roles (Admin, Lecturer, Student)
- Secure authentication via Laravel Breeze
- Class and subject management by admin
- Student enrollment request system with statement and supporting document upload
- Admin approves or rejects enrollment requests with remarks
- Lecturers create exams with MCQ and open-text questions per subject per class
- Exam time limit with frontend countdown timer and backend enforcement
- MCQ auto grading on submission
- Manual grading for open-text questions by lecturer
- Students can only access exams assigned to their enrolled class
- Exam publish/unpublish control for lecturers
- Results page showing marks and answer review

## Setup Instructions

### Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL

### Installation

1. Clone the repository:
```bash
git clone https://github.com/amhu01/exam-portal-v2.git
cd exam-portal-v2
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node dependencies:
```bash
npm install
```

4. Copy the environment file:
```bash
cp .env.example .env
```

5. Generate the application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=exam_portal
DB_USERNAME=root
DB_PASSWORD=
```

7. Run migrations:
```bash
php artisan migrate
```

8. Seed the admin account:
```bash
php artisan db:seed
```

9. Create the storage link for file uploads:
```bash
php artisan storage:link
```

10. Start the frontend:
```bash
npm run dev
```

11. Serve the application:
```bash
php artisan serve
```

## Default Accounts

After seeding, the following admin account is available:

| Role  | Email                  | Password |
|-------|------------------------|----------|
| Admin | admin@examportal.com   | password |

Lecturer and student accounts can be created via the Admin dashboard.

## Usage Flow

### Admin
1. Log in with the default admin account
2. Go to User Management to change a registered user's role to Lecturer
3. Create classes and subjects under their respective sections
4. Assign subjects to classes and assign a lecturer to each subject
5. Review and approve or reject student enrollment requests

### Lecturer
1. Register for an account (you will be a student by default)
2. Contact the admin to have your role changed to Lecturer
3. Once your role is updated, log in and you will be redirected to the Lecturer dashboard
4. Go to My Exams and create an exam for an assigned subject and class
5. Add MCQ and open-text questions to the exam
6. Publish the exam when ready
7. Go to Grading to grade student submissions

### Student
1. Register for an account
2. Go to Class Enrollment and apply for a class with a statement and optional document
3. Wait for admin approval
4. Once enrolled, go to My Exams to view and take available exams
5. View results after submission

## Database Structure
```
users               → stores all users with role and class assignment
classes             → stores class information
subjects            → stores subject information
class_subject       → pivot table linking classes, subjects and lecturers
exams               → stores exams linked to a class_subject
questions           → stores questions linked to an exam
options             → stores MCQ options linked to a question
exam_submissions    → stores student exam attempts
answers             → stores student answers per submission
enrollment_requests → stores student class enrollment requests
```

## Additional Features

- **Admin role** — a superuser above lecturer and student for full system management
- **Enrollment request system** — students submit a written statement and optional supporting document to join a class, reviewed and approved by admin
- **Auto grading** — MCQ answers are automatically graded on submission
- **Publish control** — lecturers control when students can see an exam via publish/unpublish
- **Answer review** — students can review their answers and see correct MCQ answers after grading