# 🚌 Ticket Sorter API

This Symfony 7 project provides a REST API to sort transport tickets (flight, bus, train) and optionally generate travel instructions.

---

## ✅ Requirements

- [Composer](https://getcomposer.org/)
- PHP 8.2 or higher
- [Symfony CLI (optional)](https://symfony.com/download)

> The Symfony binary is optional but recommended. It provides a local web server and project diagnostics.

---

## 🚀 Installation

1. Clone the project and navigate to the directory:
```bash
git clone git@github.com:benji06000/ticket-sorter.git
cd ticket-sorter
 ```

2. Copy the environment configuration files:
```bash
cp env.dist .env
cp phpunit.dist phpunit.xml
````

3. Install the dependencies:
```bash
composer install
```

4. Start the Symfony web server:
```bash
symfony server:start
```
> This starts a local web server at https://localhost:8000.

---

## 📘 API Documentation

You can access the Swagger / OpenAPI documentation here:

- OpenAPI JSON: [https://localhost:8000/doc.json](https://localhost:8000/doc.json)
- Swagger UI: [https://localhost:8000/doc](https://localhost:8000/doc)

### 📥 Import into Postman

To use this API in Postman:

1. Open Postman
2. Go to **File > Import**
3. Select **Link** and paste: `https://localhost:8000/doc.json`
4. Follow Postman's instructions to create a collection.

> Reference: [Postman - Importing from Swagger](https://learning.postman.com/docs/getting-started/importing-and-exporting/importing-from-swagger/)

---

## 🧪 Running Tests

To run functional tests using PHPUnit:

```bash
php bin/phpunit
```

> Make sure `APP_ENV=test` is used by default when executing tests.

---

## 🛠️ Tech Stack

- Symfony 7
- PHP 8.2

---

## 📂 Project Structure Highlights

- `src/Controller/`: API endpoints
- `src/Dto/`: Data Transfer Objects
- `src/Service/SorterService.php`: Business logic
- `tests/`: Functional test cases



