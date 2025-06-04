# Stock Management System

A modern, web-based stock management system built with Laravel and Tailwind CSS. This application provides comprehensive inventory management capabilities with role-based access control.

## 🚀 Features

### Core Functionality
- **Product Management**: Add, view, and manage products with categories
- **Inventory Tracking**: Real-time stock level monitoring
- **Sales Processing**: Point-of-sale system with shopping cart
- **Search & Filtering**: Advanced product search and filtering options
- **Role-Based Access**: Admin, Employee, and Stock Manager roles

### User Interface
- **Modern Design**: Clean, responsive UI built with Tailwind CSS
- **Interactive Shopping Cart**: Slide-out cart with real-time updates
- **Stock Status Indicators**: Visual indicators for stock levels (In Stock, Low Stock, Out of Stock)
- **Advanced Filters**: Search by name, category, and stock status
- **Mobile Responsive**: Works seamlessly on all devices

### Technical Features
- **Laravel Framework**: Built on Laravel with MVC architecture
- **Database Relations**: Proper relationships between products, sales, and users
- **Authentication**: Secure user authentication and authorization
- **Validation**: Comprehensive form validation
- **Pagination**: Efficient data pagination for large inventories

## 🛠️ Technology Stack

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates, Tailwind CSS, Vanilla JavaScript
- **Database**: MySQL/SQLite
- **Build Tools**: Vite
- **Authentication**: Laravel Breeze

## 📋 Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/SQLite database

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/stock-management.git
   cd stock-management
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   - Configure your database settings in `.env`
   - Run migrations:
   ```bash
   php artisan migrate
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

## 👥 User Roles

### Admin
- Full system access
- User management
- Sales reports and analytics
- System configuration

### Employee
- Product management
- Sales processing
- Inventory requests
- Daily sales reports

### Stock Manager
- Inventory oversight
- Stock level management
- Supply chain coordination

## 🎯 Key Features Showcase

### Product Management
- Add new products with detailed information
- Categorize products for better organization
- Set pricing and stock levels
- Track inventory changes

### Sales System
- Interactive shopping cart
- Real-time price calculations
- Stock validation during sales
- Sales history tracking

### Search & Filtering
- Text-based product search
- Category filtering
- Stock status filtering
- Collapsible filter interface

## 🔧 Configuration

### Database
Configure your database connection in the `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stock_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Application Settings
```env
APP_NAME="Stock Management System"
APP_ENV=local
APP_KEY=base64:your_app_key
APP_DEBUG=true
APP_URL=http://localhost
```

## 📱 Screenshots

*Add screenshots of your application here*

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🐛 Issues

If you discover any bugs or have feature requests, please create an issue on GitHub.

## 📞 Support

For support and questions, please open an issue on GitHub or contact the development team.

---

**Built with ❤️ using Laravel and Tailwind CSS**
