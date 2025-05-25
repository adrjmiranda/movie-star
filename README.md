# MovieStar 🎥

> A social network where people can share movie reviews, comment and rate other people's posts!

---

## 📌 Description

**MovieStar** is a platform created for movie fans to exchange experiences, opinions and recommendations about movies. Each user can:

- Publish complete reviews with ratings;
- Comment on other users' reviews;
- Rate (rate) reviews published by other members of the community.

Ideal for creating engagement around the cinematic universe.

---

## 🧰 Technologies Used

- **PHP** (PSR-4 Autoload)
- **Slim Framework 4** – for routes and middleware
- **Medoo** – lightweight ORM for database manipulation
- **Twig** – template engine
- **PHPdotenv** – environment variable management
- **Monolog** – system logs
- **Whoops** – error handling
- **Respect/Validation** – form validation
- **Symfony VarDumper** – debugging (development environment)

---

## 🗂️ Project Structure

- public/ # Public files and entry point (index.php)
- src/ # Main source code (controllers, models, etc.)
- helpers/ # Global utility functions
- templates/ # Twig templates
- config/ # System settings
- logs/ # Monolog logs
- .env # Environment variables

---

## 🔧 How to Run the Project

### 1. Clone the repository

```bash
git clone https://github.com/adrjmiranda/movie-star.git
cd movie-star
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure the .env

Copy the example file:

```bash
cp .env.example .env
```

Edit according to your needs (database, etc.)

### 4. Inicie o servidor

```bash
composer serve
```

Access: http://localhost:8000

## 📚 Banco de Dados

The project uses Medoo as ORM. Make sure to correctly configure the .env file with the data from your SQLite or MySQL database. Execute:

```bash
sudo mysql -u root -p < db.sql
```

## 📋 Licença

MIT License – see the LICENSE file for more details.

## 💬 Créditos

Developed by (Adriano Miranda)[https://github.com/adrjmiranda]

## 📩 Email: adrjmiranda@gmail.com

## 🔗 GitHub: @adrjmiranda
