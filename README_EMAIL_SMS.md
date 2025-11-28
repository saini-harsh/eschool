# Email/SMS Communication System

This system provides email and SMS functionality for the EnvisionTechSolution application using RapidAPI services.

## Features

- Send emails and SMS messages to various recipient types
- Support for individual, group, and class-based recipient selection
- Message history tracking
- Status management (pending, sent, failed)
- RapidAPI integration for reliable delivery

## Setup Instructions

### 1. Environment Configuration

Add the following variables to your `.env` file:

```env
# RapidAPI Configuration
RAPIDAPI_KEY=your_rapidapi_key_here
RAPIDAPI_EMAIL_HOST=rapid-send-email.p.rapidapi.com
RAPIDAPI_EMAIL_URL=https://rapid-send-email.p.rapidapi.com/send
RAPIDAPI_SMS_HOST=rapid-sms.p.rapidapi.com
RAPIDAPI_SMS_URL=https://rapid-sms.p.rapidapi.com/send

# SMS Configuration
SMS_FROM_NUMBER=EnvisionTechSolution
```

### 2. Database Migration

Run the migration to create the email_sms table:

```bash
php artisan migrate
```

### 3. RapidAPI Setup

1. Sign up for a RapidAPI account at [rapidapi.com](https://rapidapi.com)
2. Subscribe to email and SMS services:
   - Email service: Search for "rapid-send-email" or similar
   - SMS service: Search for "rapid-sms" or similar
3. Get your API key from the RapidAPI dashboard
4. Update your `.env` file with the actual API key and endpoints

### 4. Access the System

Navigate to `/admin/email-sms` in your application to access the email/SMS interface.

## Usage

### Sending Messages

1. **Compose Message**: Fill in the title and description
2. **Select Type**: Choose between Email or SMS
3. **Choose Recipients**: Select from individual, group, or class options
4. **Send**: Click the send button to deliver your message

### Recipient Types

- **Individual**: Select specific roles (students, teachers, parents, etc.)
- **Group**: Send to predefined groups (all students, all teachers, etc.)
- **Class**: Target specific classes and sections

### Message Management

- View message history
- Update message status
- Edit existing messages
- Delete messages

## API Endpoints

- `GET /admin/email-sms` - Main interface
- `POST /admin/email-sms/store` - Send new message
- `GET /admin/email-sms/list` - Get message list
- `GET /admin/email-sms/edit/{id}` - Edit message
- `POST /admin/email-sms/update/{id}` - Update message
- `POST /admin/email-sms/delete/{id}` - Delete message
- `POST /admin/email-sms/{id}/status` - Update status

## File Structure

```
app/
├── Http/Controllers/Admin/Communication/
│   └── EmailSmsController.php
├── Models/
│   └── EmailSms.php
resources/views/admin/communication/emailsms/
│   └── index.blade.php
public/custom/js/
│   └── email-sms.js
database/migrations/
│   └── 2025_08_16_180000_create_email_sms_table.php
```

## Troubleshooting

### Common Issues

1. **API Key Invalid**: Ensure your RapidAPI key is correct and active
2. **Service Unavailable**: Check if the RapidAPI service is active
3. **Rate Limits**: Be aware of API rate limits for your subscription

### Testing

Test the system with a small group of recipients before sending to large audiences.

## Security Notes

- API keys are stored in environment variables
- Messages are validated before sending
- Recipient lists are sanitized
- CSRF protection is enabled for all forms

## Support

For issues or questions, check the Laravel logs or contact the development team.
