<?php

namespace App\Helpers;

use App\Models\Institution;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\NonWorkingStaff;
use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Check if the current logged-in user should see a specific menu item
     * Works for Admin, Institution, Teacher, Student, and NonWorkingStaff
     */
    public static function canShowMenu($permission, $userId = null)
    {
        // If admin is logged in, always show all menus
        if (Auth::guard('admin')->check()) {
            return true;
        }

        // If institution is logged in, check their permissions
        if (Auth::guard('institution')->check()) {
            $institution = Auth::guard('institution')->user();
            return $institution->hasPermission($permission);
        }

        // If teacher is logged in, check their permissions
        if (Auth::guard('teacher')->check()) {
            $teacher = Auth::guard('teacher')->user();
            return $teacher->hasPermission($permission);
        }

        // If student is logged in, check their permissions
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            return $student->hasPermission($permission);
        }

        // If checking for a specific user (when setting permissions)
        if ($userId) {
            $institution = Institution::find($userId);
            return $institution ? $institution->hasPermission($permission) : false;
        }

        return false;
    }

    /**
     * Check if admin should show menu based on institution context
     * This is used when admin is managing specific institution
     */
    public static function canShowForInstitution($permission, Institution $institution = null)
    {
        // Admin always has access
        if (Auth::guard('admin')->check()) {
            return true;
        }

        // Institution check
        if ($institution) {
            return $institution->hasPermission($permission);
        }

        return false;
    }

    /**
     * Get all available permissions
     */
    public static function getAvailablePermissions()
    {
        return Institution::availablePermissions();
    }

    /**
     * Get permissions grouped by category (for institutions)
     */
    public static function getGroupedPermissions()
    {
        return [
            'Dashboard' => [
                'dashboard' => 'Dashboard',
            ],
            'Administration' => [
                'administration' => 'Administration (Main Menu)',
                'institutions' => 'Institutions',
                'teachers' => 'Teachers',
                'students' => 'Students',
                'nonworkingstaff' => 'Non-Working Staff',
                'attendance' => 'Attendance',
            ],
            'Academics' => [
                'academics' => 'Academics (Main Menu)',
                'classes' => 'Classes',
                'sections' => 'Sections',
                'subjects' => 'Subjects',
                'assign_teacher' => 'Assign Class Teacher',
                'assign_subject' => 'Assign Subject',
                'assignments' => 'Assignments',
                'calendar' => 'Calendar',
                'events' => 'Event Management',
            ],
            'Communication' => [
                'communication' => 'Communication (Main Menu)',
                'email_sms' => 'Email / SMS',
            ],
            'Exam Management' => [
                'exam_management' => 'Exam Management (Main Menu)',
                'exams' => 'Exams',
                'exam_type' => 'Exam Type',
                'exam_setup' => 'Exam Setup',
                'marksheet' => 'Marksheet',
            ],
            'Routine' => [
                'routine' => 'Routine (Main Menu)',
                'class_routine' => 'Class Routine',
                'lesson_plan' => 'Lesson Plan',
            ],
            'Settings' => [
                'settings' => 'Settings',
            ],
        ];
    }

    /**
     * Get permissions for teachers
     */
    public static function getTeacherPermissions()
    {
        return Teacher::availablePermissions();
    }

    /**
     * Get permissions for students
     */
    public static function getStudentPermissions()
    {
        return Student::availablePermissions();
    }

    /**
     * Get permissions for non-working staff
     */
    public static function getStaffPermissions()
    {
        return NonWorkingStaff::availablePermissions();
    }
}

