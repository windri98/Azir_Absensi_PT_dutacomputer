# PT DUTA COMPUTER API Documentation

## Base URL
```
https://api.dutacomputer.local/api/v1
```

## Authentication
All endpoints (except login) require Bearer token authentication.

```
Authorization: Bearer {token}
```

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {}
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "error_code": "ERROR_CODE"
}
```

## Endpoints

### Authentication

#### Login
- **POST** `/auth/login`
- **Body:**
  ```json
  {
    "email": "user@example.com",
    "password": "password123"
  }
  ```
- **Response:**
  ```json
  {
    "success": true,
    "message": "Login successful",
    "data": {
      "token": "token_string",
      "user": { ... }
    }
  }
  ```

#### Get Current User
- **GET** `/auth/me`
- **Headers:** `Authorization: Bearer {token}`
- **Response:** User object

#### Logout
- **POST** `/auth/logout`
- **Headers:** `Authorization: Bearer {token}`
- **Response:** Success message

### Attendance

#### Get All Attendances
- **GET** `/attendances`
- **Query Parameters:**
  - `limit` (default: 30)
  - `start_date` (YYYY-MM-DD)
  - `end_date` (YYYY-MM-DD)
  - `month` (1-12)
  - `year` (YYYY)
- **Headers:** `Authorization: Bearer {token}`
- **Response:** Paginated attendance list

#### Get Today's Attendance
- **GET** `/attendances/today`
- **Headers:** `Authorization: Bearer {token}`
- **Response:** Today's attendance record or null

#### Get Attendance Statistics
- **GET** `/attendances/statistics`
- **Query Parameters:**
  - `month` (default: current month)
  - `year` (default: current year)
- **Headers:** `Authorization: Bearer {token}`
- **Response:** Statistics object

#### Check-in
- **POST** `/attendances/check-in`
- **Headers:** `Authorization: Bearer {token}`
- **Body:**
  ```json
  {
    "location": "Office",
    "latitude": -6.2088,
    "longitude": 106.8456
  }
  ```
- **Response:** Attendance record

#### Check-out
- **POST** `/attendances/check-out`
- **Headers:** `Authorization: Bearer {token}`
- **Body:**
  ```json
  {
    "location": "Office",
    "latitude": -6.2088,
    "longitude": 106.8456
  }
  ```
- **Response:** Attendance record

#### Get Single Attendance
- **GET** `/attendances/{id}`
- **Headers:** `Authorization: Bearer {token}`
- **Response:** Attendance record

#### Update Attendance (Admin)
- **PUT** `/attendances/{id}`
- **Headers:** `Authorization: Bearer {token}`
- **Body:**
  ```json
  {
    "status": "present",
    "check_in": "08:00:00",
    "check_out": "17:00:00",
    "notes": "Optional notes"
  }
  ```
- **Response:** Updated attendance record

## Error Codes

| Code | Status | Description |
|------|--------|-------------|
| INVALID_CREDENTIALS | 401 | Email or password is incorrect |
| UNAUTHORIZED | 401 | Token is missing or invalid |
| FORBIDDEN | 403 | User doesn't have permission |
| NOT_FOUND | 404 | Resource not found |
| VALIDATION_ERROR | 422 | Validation failed |
| RATE_LIMIT | 429 | Too many requests |
| SERVER_ERROR | 500 | Internal server error |

## Rate Limiting
- 60 requests per minute per IP address
- Rate limit headers included in response

## Pagination
Paginated responses include:
```json
{
  "data": [...],
  "pagination": {
    "total": 100,
    "per_page": 30,
    "current_page": 1,
    "last_page": 4
  }
}
```
