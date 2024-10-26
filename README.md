# QuizQuest

Welcome to **QuizQuest**, a comprehensive web application built with Laravel and Vue.js. This project is designed to provide a seamless user experience with a robust user management system, role-based access control, and engaging quiz functionalities.

## Features

- **User Management**: 
  - Registration and authentication using Laravel Sanctum.
  - Role-based access control to manage user permissions effectively.
  - User roles and permissions are dynamically managed through a user-friendly interface.

- **Frontend**:
  - Built with Vue.js, providing a reactive and dynamic user interface.
  - Utilizes Vue Router for seamless navigation between pages.
  - Tailwind CSS is integrated for a modern and responsive design.
  - Axios is used for efficient API communication.

- **Backend**:
  - Powered by Laravel, ensuring a robust and scalable backend.
  - RESTful API endpoints for user and role management.
  - Secure password handling with hashing and validation.

- **Additional Features**:
  - Dark mode support for enhanced user experience.
  - Comprehensive error handling and validation feedback.

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/quizquest.git
   cd quizquest
   ```

2. **Install dependencies**:
   - For Laravel:
     ```bash
     composer install
     ```
   - For Vue.js:
     ```bash
     npm install
     ```

3. **Environment setup**:
   - Copy the `.env.example` to `.env` and configure your database and other environment variables.

4. **Database migration and seeding**:
   - Run the migrations to set up the database schema:
     ```bash
     php artisan migrate
     ```
   - Seed the database to create default admin and user accounts:
     ```bash
     php artisan db:seed
     ```

5. **Build assets**:
   ```bash
   npm run dev
   ```

6. **Run the application**:
   ```bash
   php artisan serve
   ```

## Usage

- **Access Default Accounts**:
  - After running the seeder, you can log in using the default admin and user credentials provided in the seeder file.

- **User Registration and Login**:
  - Users can register and log in to access quizzes and track their progress.
  - Admin users can manage other users and assign roles.

- **Role and Permission Management**:
  - Admins can create roles and assign permissions to users.
  - Permissions are managed through a dedicated interface.

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request for any improvements or bug fixes.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

## Acknowledgments

- Thanks to the Laravel and Vue.js communities for their excellent frameworks and tools.
---

Feel free to reach out if you have any questions or need further assistance with the project.
