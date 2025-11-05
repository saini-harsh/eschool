# Teacher Attendance Management API Documentation

## Base URL
```
http://localhost/api/Teacher/Academic/Attendance
```

## Authentication
All API endpoints require teacher authentication using email. Include the teacher's email in the request body to authenticate requests.

## API Endpoints

### 1. Get Attendance Data
**Endpoint:** `POST /GetAttendanceData`

**Description:** Get filtered attendance data with optional parameters. You can filter by class, section, date, date range, status, or specific student.

**Request Body:**
```json
{
  "email": "teacher@example.com", // Required: Teacher email for authentication
  "class_id": 1,           // Optional: Filter by specific class
  "section_id": 1,         // Optional: Filter by specific section  
  "date": "2025-01-15",    // Optional: Filter by specific date
  "start_date": "2025-01-01",  // Optional: Start date for date range
  "end_date": "2025-01-31",    // Optional: End date for date range
  "status": "present",     // Optional: Filter by status (present/absent/late)
  "student_id": 101        // Optional: Filter by specific student
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Attendance data retrieved successfully",
  "data": {
    "attendance": [
      {
        "id": 1,
        "user_id": 101,
        "role": "student",
        "institution_id": 1,
        "class_id": 1,
        "section_id": 1,
        "teacher_id": 5,
        "date": "2025-01-15",
        "status": "present",
        "remarks": "On time",
        "marked_by": 5,
        "marked_by_role": "teacher",
        "is_confirmed": false,
        "confirmed_by": null,
        "confirmed_at": null,
        "created_at": "2025-01-15T08:30:00.000000Z",
        "updated_at": "2025-01-15T08:30:00.000000Z",
        "student": {
          "id": 101,
          "first_name": "John",
          "last_name": "Doe",
          "roll_no": "R001"
        },
        "school_class": {
          "id": 1,
          "name": "Class 10"
        },
        "section": {
          "id": 1,
          "name": "Section A"
        }
      }
    ],
    "summary": {
      "total_records": 25,
      "present": 20,
      "absent": 3,
      "late": 2,
      "present_percentage": 80.00,
      "absent_percentage": 12.00,
      "late_percentage": 8.00
    },
    "total_records": 25
  }
}
```

### 2. Get Class Section Attendance
**Endpoint:** `POST /GetClassSectionAttendance`

**Description:** Get attendance for a specific class and section on a given date. Returns student list with their current attendance status.

**Request Body:**
```json
{
  "email": "teacher@example.com", // Required: Teacher email for authentication
  "class_id": 1,           // Required: Class ID
  "section_id": 1,         // Required: Section ID
  "date": "2025-01-15"     // Required: Date (YYYY-MM-DD)
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Class attendance retrieved successfully",
  "data": {
    "students": [
      {
        "student_id": 101,
        "student_name": "John Doe",
        "student_roll_no": "R001",
        "attendance_id": 15,
        "status": "present",
        "remarks": "On time",
        "is_confirmed": false,
        "marked_at": "2025-01-15 08:30:00"
      },
      {
        "student_id": 102,
        "student_name": "Jane Smith",
        "student_roll_no": "R002",
        "attendance_id": null,
        "status": "present",
        "remarks": "",
        "is_confirmed": false,
        "marked_at": null
      }
    ],
    "class_name": "Class 10",
    "section_name": "Section A",
    "date": "2025-01-15",
    "total_students": 30
  }
}
```

### 3. Mark Attendance
**Endpoint:** `POST /MarkAttendance`

**Description:** Mark or update attendance for students in a class. Supports bulk attendance marking with status (present, absent, late) and optional remarks.

**Request Body:**
```json
{
  "email": "teacher@example.com", // Required: Teacher email for authentication
  "class_id": 1,           // Required: Class ID
  "section_id": 1,         // Required: Section ID
  "date": "2025-01-15",    // Required: Date (YYYY-MM-DD)
  "attendance": [         // Required: Array of student attendance records
    {
      "student_id": 101,
      "status": "present",
      "remarks": "On time"
    },
    {
      "student_id": 102,
      "status": "absent",
      "remarks": "Sick leave"
    },
    {
      "student_id": 103,
      "status": "late",
      "remarks": "Traffic"
    }
  ]
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Attendance marked successfully",
  "data": {
    "attendance": [
      {
        "id": 15,
        "user_id": 101,
        "role": "student",
        "institution_id": 1,
        "class_id": 1,
        "section_id": 1,
        "teacher_id": 5,
        "date": "2025-01-15",
        "status": "present",
        "remarks": "On time",
        "marked_by": 5,
        "marked_by_role": "teacher",
        "is_confirmed": false,
        "created_at": "2025-01-15T08:30:00.000000Z",
        "updated_at": "2025-01-15T08:30:00.000000Z"
      }
    ],
    "total_marked": 3
  }
}
```

### 4. Get Attendance Summary
**Endpoint:** `POST /GetAttendanceSummary`

**Description:** Get detailed attendance statistics and summary. Returns overall statistics and breakdowns by class, section, and date.

**Request Body:**
```json
{
  "email": "teacher@example.com", // Required: Teacher email for authentication
  "class_id": 1,           // Optional: Filter by specific class
  "section_id": 1,         // Optional: Filter by specific section
  "start_date": "2025-01-01",  // Optional: Start date for date range
  "end_date": "2025-01-31",    // Optional: End date for date range
  "student_id": 101        // Optional: Filter by specific student
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Attendance summary retrieved successfully",
  "data": {
    "summary": {
      "overall": {
        "total_records": 150,
        "present": 120,
        "absent": 20,
        "late": 10,
        "present_percentage": 80.00,
        "absent_percentage": 13.33,
        "late_percentage": 6.67
      },
      "by_class": {
        "1": {
          "total_records": 75,
          "present": 60,
          "absent": 10,
          "late": 5,
          "present_percentage": 80.00,
          "absent_percentage": 13.33,
          "late_percentage": 6.67
        },
        "2": {
          "total_records": 75,
          "present": 60,
          "absent": 10,
          "late": 5,
          "present_percentage": 80.00,
          "absent_percentage": 13.33,
          "late_percentage": 6.67
        }
      },
      "by_section": {
        "1": {
          "total_records": 50,
          "present": 40,
          "absent": 7,
          "late": 3,
          "present_percentage": 80.00,
          "absent_percentage": 14.00,
          "late_percentage": 6.00
        }
      },
      "by_date": {
        "2025-01-15": {
          "total_records": 30,
          "present": 25,
          "absent": 3,
          "late": 2,
          "present_percentage": 83.33,
          "absent_percentage": 10.00,
          "late_percentage": 6.67
        }
      }
    },
    "date_range": {
      "start_date": "2025-01-01",
      "end_date": "2025-01-31"
    }
  }
}
```

## Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation errors",
  "errors": {
    "class_id": ["The class id field is required."],
    "section_id": ["The section id field is required."]
  }
}
```

### Authentication Error (404)
```json
{
  "success": false,
  "message": "No teacher found with this email",
  "data": []
}
```

### Authorization Error (403)
```json
{
  "success": false,
  "message": "You are not assigned to this class"
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Error retrieving attendance data",
  "error": "Detailed error message"
}
```

## Status Codes
- `200` - Success
- `404` - Not Found (Teacher email not found)
- `403` - Forbidden (Teacher not assigned to class)
- `422` - Validation Error
- `500` - Internal Server Error

## Notes
- All dates should be in `YYYY-MM-DD` format
- Status values: `present`, `absent`, `late`
- Teacher can only access classes they are assigned to
- Default date range is current month if not specified
- Attendance records are automatically filtered by teacher's institution