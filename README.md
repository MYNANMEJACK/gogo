# GoGo Restaurant Management System

GoGo is a modern restaurant management system that provides a complete solution for restaurant operations. The system supports menu management, order processing, user management, and other features to help restaurants improve operational efficiency.

## Features

- 🔐 Secure User Authentication System
- 🍽️ Menu Management
  - Add/Edit/Delete Dishes
  - Menu Category Management
  - Image Upload Functionality
- 📦 Order Management
  - Order Status Tracking
  - Order History
- 👥 User Management
  - Customer Information Management
  - Staff Permission Control
- 📍 Pickup Location Management
- 📊 Data Statistics and Analytics

## Tech Stack

- Backend: PHP
- Frontend: HTML, CSS, JavaScript
- Database: MySQL
- Containerization: Docker

## System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web Server (Apache/Nginx)
- Docker (optional)

## Installation

1. Clone the repository
```bash
git clone [repository-url]
cd gogo
```

2. Configure Database
- Create a new MySQL database
- Import database structure (SQL files located in `includes` directory)

3. Environment Setup
- Copy `.env.example` to `.env`
- Modify database connection information

4. Start Services
```bash
# Using Docker
docker-compose up -d

# Or using traditional method
php -S localhost:8000
```

## Usage

1. Access the System
- Open browser and visit `http://localhost:8000`
- Login with administrator account

2. Main Features
- Admin Login: `/admin_login.php`
- Menu Management: `/dish_management.php`
- Order Management: `/order_management.php`
- User Management: `/user_management.php`

## Directory Structure

```
gogo/
├── admin/          # Admin backend files
├── api/           # API interfaces
├── css/           # Style files
├── includes/      # Common components and configurations
├── js/            # JavaScript files
└── uploads/       # Upload file storage
```

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

[MIT License](LICENSE)

## Contact

For questions or suggestions, please submit an Issue or contact the project maintainer.
