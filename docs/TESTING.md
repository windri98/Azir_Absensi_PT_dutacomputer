# Testing Guide

## Overview

Dokumen ini menjelaskan strategi testing untuk Sistem Absensi Karyawan.

## Testing Pyramid

```
        /\
       /  \  E2E Tests (10%)
      /____\
     /      \
    /        \ Integration Tests (30%)
   /          \
  /____________\
 /              \
/                \ Unit Tests (60%)
/__________________\
```

## Unit Tests

### Backend Unit Tests

#### Test Structure
```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\AttendanceService;

class AttendanceServiceTest extends TestCase
{
    private $attendanceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->attendanceService = new AttendanceService();
    }

    public function test_check_in_creates_attendance_record()
    {
        // Arrange
        $userId = 1;
        $data = ['location' => 'Office'];

        // Act
        $result = $this->attendanceService->processCheckIn($userId, $data);

        // Assert
        $this->assertTrue($result['success']);
    }
}
```

#### Test Coverage
- Service classes: >80%
- Model methods: >80%
- Helper functions: >90%

### Frontend Unit Tests

#### Vue Component Tests
```javascript
import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import Button from '@/components/Button.vue';

describe('Button Component', () => {
  it('renders button with text', () => {
    const wrapper = mount(Button, {
      props: {
        text: 'Click me'
      }
    });

    expect(wrapper.text()).toContain('Click me');
  });

  it('emits click event', async () => {
    const wrapper = mount(Button);
    await wrapper.trigger('click');
    expect(wrapper.emitted('click')).toBeTruthy();
  });
});
```

#### Test Coverage
- Components: >80%
- Stores: >80%
- Utils: >90%

## Integration Tests

### API Integration Tests

#### Test Structure
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class AttendanceApiTest extends TestCase
{
    public function test_check_in_endpoint()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/v1/attendances/check-in', [
                'location' => 'Office'
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'data']);
    }
}
```

#### Test Coverage
- API endpoints: >80%
- Database operations: >80%
- Authentication: >90%

### Frontend Integration Tests

#### Store Integration Tests
```javascript
import { describe, it, expect, beforeEach } from 'vitest';
import { useAuthStore } from '@/stores/auth';
import { setActivePinia, createPinia } from 'pinia';

describe('Auth Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia());
  });

  it('logs in user successfully', async () => {
    const store = useAuthStore();
    await store.login('user@example.com', 'password');
    expect(store.isAuthenticated).toBe(true);
  });
});
```

## E2E Tests

### User Flows

#### Login Flow
```javascript
describe('Login Flow', () => {
  it('user can login and access dashboard', () => {
    cy.visit('/login');
    cy.get('[data-cy=email]').type('employee1@example.com');
    cy.get('[data-cy=password]').type('password123');
    cy.get('[data-cy=login-button]').click();
    cy.url().should('include', '/dashboard');
  });
});
```

#### Attendance Flow
```javascript
describe('Attendance Flow', () => {
  it('user can check-in and check-out', () => {
    cy.login('employee1@example.com', 'password123');
    cy.visit('/attendance');
    cy.get('[data-cy=check-in-button]').click();
    cy.contains('Check-in berhasil').should('be.visible');
    cy.get('[data-cy=check-out-button]').click();
    cy.contains('Check-out berhasil').should('be.visible');
  });
});
```

## Running Tests

### Backend Tests
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AttendanceApiTest.php

# Run with coverage
php artisan test --coverage

# Run specific test method
php artisan test --filter test_check_in_endpoint
```

### Frontend Tests
```bash
# Run all tests
npm run test

# Run tests in watch mode
npm run test:watch

# Run with coverage
npm run test:coverage

# Run specific test file
npm run test -- Button.test.js
```

### E2E Tests
```bash
# Run all E2E tests
npm run cypress:run

# Run in interactive mode
npm run cypress:open

# Run specific test file
npm run cypress:run --spec "cypress/e2e/login.cy.js"
```

## Test Data

### Factories
```php
// Create test user
$user = User::factory()->create();

// Create multiple users
$users = User::factory(10)->create();

// Create with specific attributes
$user = User::factory()->create([
    'email' => 'test@example.com'
]);
```

### Seeders
```bash
# Run seeders for testing
php artisan db:seed --class=TestSeeder
```

## Continuous Integration

### GitHub Actions
- Runs on every push and pull request
- Runs unit tests
- Runs integration tests
- Runs code quality checks
- Generates coverage reports

### Coverage Reports
- Minimum coverage: 80%
- Coverage reports generated automatically
- Available in GitHub Actions artifacts

## Best Practices

### Test Naming
- Use descriptive names
- Follow pattern: `test_<feature>_<scenario>`
- Example: `test_check_in_creates_attendance_record`

### Test Organization
- One test per behavior
- Arrange-Act-Assert pattern
- Setup and teardown properly

### Test Data
- Use factories for test data
- Clean up after tests
- Use transactions for isolation

### Assertions
- Use specific assertions
- Test one thing per test
- Avoid testing implementation details

## Debugging Tests

### Backend
```bash
# Run with verbose output
php artisan test --verbose

# Run with debug output
php artisan test --debug

# Use dd() to dump and die
dd($variable);
```

### Frontend
```bash
# Run with debug output
npm run test -- --reporter=verbose

# Use console.log for debugging
console.log(variable);

# Use debugger statement
debugger;
```

## Performance Testing

### Load Testing
```bash
# Using Apache Bench
ab -n 1000 -c 100 http://localhost:8000/api/v1/attendances

# Using wrk
wrk -t12 -c400 -d30s http://localhost:8000/api/v1/attendances
```

### Profiling
```bash
# Using Laravel Debugbar
# Enable in .env: DEBUGBAR_ENABLED=true

# Using Xdebug
# Configure in php.ini
```

## Test Maintenance

### Regular Updates
- Update tests when features change
- Remove obsolete tests
- Refactor duplicate test code

### Test Review
- Code review for tests
- Ensure tests are meaningful
- Check test coverage

## Resources

- PHPUnit Documentation: https://phpunit.de/
- Vitest Documentation: https://vitest.dev/
- Cypress Documentation: https://docs.cypress.io/
- Laravel Testing: https://laravel.com/docs/testing

---

**Last Updated**: 2026-01-18
