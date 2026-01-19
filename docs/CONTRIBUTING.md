# Contributing to PT DUTA COMPUTER

First off, thank you for considering contributing to PT DUTA COMPUTER Sistem Manajemen Absensi! It's people like you that make this project better.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the issue list as you might find out that you don't need to create one. When you are creating a bug report, please include as many details as possible:

* **Use a clear and descriptive title** for the issue
* **Describe the exact steps to reproduce the problem**
* **Provide specific examples to demonstrate the steps**
* **Describe the behavior you observed and what you expected**
* **Include screenshots if possible**
* **Note your environment** (OS, PHP version, Laravel version, etc.)

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. Create an issue and provide:

* **A clear and descriptive title**
* **A detailed description of the suggested enhancement**
* **Explain why this enhancement would be useful**
* **List any similar features in other applications if applicable**

### Pull Requests

1. Fork the repo and create your branch from `main`
2. If you've added code, add tests that cover your changes
3. Ensure the test suite passes
4. Make sure your code follows Laravel coding standards
5. Update documentation if needed
6. Write a clear commit message

## Development Setup

```bash
# Clone your fork
git clone https://github.com/YOUR-USERNAME/laravel-kosong.git

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate:fresh --seed

# Start development server
php artisan serve
```

## Coding Standards

* Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards
* Use Laravel best practices
* Write meaningful commit messages
* Add comments for complex logic
* Keep methods small and focused

## Database Migrations

* Always create new migrations, never modify existing ones in production
* Use descriptive migration names
* Include both `up()` and `down()` methods
* Test rollbacks

## Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter TestName
```

## Git Commit Messages

* Use present tense ("Add feature" not "Added feature")
* Use imperative mood ("Move cursor to..." not "Moves cursor to...")
* Limit first line to 72 characters
* Reference issues and pull requests after the first line

Example:
```
Add employee shift assignment feature

- Create shift management controller
- Add shift assignment views
- Update user model with shift relationship
- Add validation for shift assignments

Fixes #123
```

## Code Review Process

All submissions require review. We use GitHub pull requests for this purpose. The core team will review your code and may request changes before merging.

## Community

* Be respectful and constructive
* Welcome newcomers and encourage diverse perspectives
* Follow our [Code of Conduct](CODE_OF_CONDUCT.md)

## Questions?

Feel free to open an issue with the tag "question" if you have any questions about contributing.

Thank you! 🙏
