# Security Policy

## Supported Versions

Currently supported versions with security updates:

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |

## Reporting a Vulnerability

We take the security of PT DUTA COMPUTER Sistem Manajemen Absensi seriously. If you have discovered a security vulnerability, please follow these steps:

### 1. **DO NOT** disclose the vulnerability publicly

Please do not create a public GitHub issue for security vulnerabilities.

### 2. Report via Email

Send details to: **[your-email@example.com]**

Include:
- Description of the vulnerability
- Steps to reproduce
- Potential impact
- Suggested fix (if any)

### 3. Wait for Response

We will respond within **48 hours** and provide:
- Confirmation of receipt
- Assessment of the vulnerability
- Expected timeline for fix
- Credit for discovery (if you wish)

## Security Best Practices

When deploying this application:

1. **Environment Configuration**
   - Never commit `.env` file to repository
   - Use strong `APP_KEY` (generated via `php artisan key:generate`)
   - Set `APP_DEBUG=false` in production
   - Use HTTPS in production

2. **Database Security**
   - Use strong database passwords
   - Restrict database access to application server only
   - Regular database backups
   - Keep database software updated

3. **Authentication**
   - Enforce strong password policies
   - Implement rate limiting on login attempts
   - Use secure session configuration
   - Regular password rotation for admin accounts

4. **File Uploads**
   - Validate file types and sizes
   - Store uploads outside web root when possible
   - Scan uploaded files for malware
   - Limit upload permissions

5. **Updates**
   - Keep Laravel framework updated
   - Update PHP to latest stable version
   - Regularly update composer dependencies
   - Monitor security advisories

## Known Security Considerations

1. **Location Tracking**: GPS coordinates are stored in database. Ensure compliance with privacy regulations (GDPR, etc.)

2. **File Uploads**: Complaint attachments are stored in `public/uploads`. Consider moving to private storage for sensitive documents.

3. **Audit Logs**: Contains user actions and IP addresses. Handle according to data retention policies.

4. **Password Reset**: Currently uses basic implementation. Consider adding email verification for production.

## Security Checklist for Production

- [ ] Change default credentials (admin@example.com)
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper CORS settings
- [ ] Enable HTTPS
- [ ] Set up firewall rules
- [ ] Configure rate limiting
- [ ] Enable SQL injection protection
- [ ] Implement CSRF protection (already included)
- [ ] Set secure session cookie settings
- [ ] Regular security audits
- [ ] Backup strategy in place

## Third-Party Dependencies

We regularly audit our dependencies for security vulnerabilities using:
- Composer audit: `composer audit`
- npm audit: `npm audit`

## Disclosure Policy

When we receive a security report:
1. We confirm the vulnerability
2. We develop a fix
3. We release a security patch
4. We publish a security advisory
5. We credit the reporter (with permission)

Thank you for helping keep Sistem Absensi Karyawan secure!
