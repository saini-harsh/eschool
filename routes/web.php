<?php

use App\Models\Exam;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\Academic\EventController;
use App\Http\Controllers\Admin\Routine\RoutineController;
use App\Http\Controllers\Admin\Academic\SectionController;
use App\Http\Controllers\Admin\Academic\SubjectController;
use App\Http\Controllers\Admin\Setting\SettingsController;
use App\Http\Controllers\Admin\Academic\CalendarController;
use App\Http\Controllers\Admin\Routine\LessonPlanController;
use App\Http\Controllers\Admin\ExamManagement\ExamController;
use App\Http\Controllers\Admin\Academic\SchoolClassController;
use App\Http\Controllers\Admin\Academic\AssignSubjectController;
use App\Http\Controllers\Admin\Administration\StudentController;
use App\Http\Controllers\Admin\Administration\TeacherController;
use App\Http\Controllers\Admin\Communication\EmailSmsController;
use App\Http\Controllers\Admin\ExamManagement\ExamTypeController;
use App\Http\Controllers\Admin\ExamManagement\ClassRoomController;
use App\Http\Controllers\Admin\ExamManagement\ExamSetupController;
use App\Http\Controllers\Admin\Administration\AttendanceController;
use App\Http\Controllers\Admin\Administration\InstitutionController;
use App\Http\Controllers\Admin\Academic\AssignClassTeacherController;
use App\Http\Controllers\Admin\Academic\AssignmentController;
use App\Http\Controllers\Admin\Administration\NonWorkingStaffController;

Route::get('/', function () {
    return view('frontend.index');
});

// Auth routes

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected page example
Route::middleware('admin')->group(function () {

    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard/data', [AdminController::class, 'dashboardData'])->name('admin.dashboard.data');

        Route::prefix('settings')->group(function () {
            Route::get('/index', [SettingsController::class, 'index'])->name('admin.settings.index');
            Route::post('/profile', [SettingsController::class, 'updateProfile'])->name('admin.settings.profile');
            Route::post('/change-password', [SettingsController::class, 'changePassword'])->name('admin.settings.change-password');
            Route::post('/delete-profile-image', [SettingsController::class, 'deleteProfileImage'])->name('admin.settings.delete-profile-image');
        });

        Route::prefix('institutions')->group(function () {
            Route::get('/index', [InstitutionController::class, 'Index'])->name('admin.institutions.index');
            Route::get('/create', [InstitutionController::class, 'Create'])->name('admin.institutions.create');
            Route::post('/store', [InstitutionController::class, 'Store'])->name('admin.institutions.store');
            Route::get('/edit/{institution}', [InstitutionController::class, 'Edit'])->name('admin.institutions.edit');
            Route::post('/update/{institution}', [InstitutionController::class, 'Update'])->name('admin.institutions.update');
            Route::post('/delete/{institution}', [InstitutionController::class, 'Delete'])->name('admin.institutions.delete');
            Route::post('/status/{id}', [InstitutionController::class, 'updateStatus'])->name('admin.institutions.status');
        });
        Route::prefix('teachers')->group(function () {
            Route::get('/index', [TeacherController::class, 'Index'])->name('admin.teachers.index');
            Route::get('/show/{teacher}', [TeacherController::class, 'Show'])->name('admin.teachers.show');
            Route::get('/create', [TeacherController::class, 'Create'])->name('admin.teachers.create');
            Route::post('/store', [TeacherController::class, 'Store'])->name('admin.teachers.store');
            Route::get('/edit/{teacher}', [TeacherController::class, 'Edit'])->name('admin.teachers.edit');
            Route::post('/update/{teacher}', [TeacherController::class, 'Update'])->name('admin.teachers.update');
            Route::post('/delete/{teacher}', [TeacherController::class, 'Delete'])->name('admin.teachers.delete');
            Route::post('/status/{id}', [TeacherController::class, 'updateStatus'])->name('admin.teachers.status');
        });
        Route::prefix('students')->group(function () {
            Route::get('/index', [StudentController::class, 'Index'])->name('admin.students.index');
            Route::get('/create', [StudentController::class, 'Create'])->name('admin.students.create');
            Route::post('/store', [StudentController::class, 'Store'])->name('admin.students.store');
            Route::get('/show/{student}', [StudentController::class, 'Show'])->name('admin.students.show');
            Route::get('/edit/{student}', [StudentController::class, 'Edit'])->name('admin.students.edit');
            Route::post('/update/{student}', [StudentController::class, 'Update'])->name('admin.students.update');
            Route::post('/delete/{student}', [StudentController::class, 'Delete'])->name('admin.students.delete');
            Route::get('/download-pdf/{student}', [StudentController::class, 'downloadPdf'])->name('admin.students.download-pdf');
            Route::get('/print-id-card/{student}', [StudentController::class, 'printIdCard'])->name('admin.students.print-id-card');
            Route::get('/classes/{institutionId}', [StudentController::class, 'getClassesByInstitution'])->name('admin.students.classes');
            Route::get('/teachers/{institutionId}', [StudentController::class, 'getTeachersByInstitution'])->name('admin.students.teachers');
            Route::get('/sections/{classId}', [StudentController::class, 'getSectionsByClass'])->name('admin.students.sections');
            Route::post('/status/{id}', [StudentController::class, 'updateStatus'])->name('admin.students.status');
            Route::post('/generate-admission-number', [StudentController::class, 'generateAdmissionNumber'])->name('admin.students.generate-admission-number');
            Route::post('/generate-roll-number', [StudentController::class, 'generateRollNumber'])->name('admin.students.generate-roll-number');
        });
        Route::prefix('nonworkingstaff')->group(function () {
            Route::get('/index', [NonWorkingStaffController::class, 'Index'])->name('admin.nonworkingstaff.index');
            Route::get('/create', [NonWorkingStaffController::class, 'Create'])->name('admin.nonworkingstaff.create');
            Route::post('/store', [NonWorkingStaffController::class, 'Store'])->name('admin.nonworkingstaff.store');
            Route::get('/edit/{nonworkingstaff}', [NonWorkingStaffController::class, 'Edit'])->name('admin.nonworkingstaff.edit');
            Route::post('/update/{nonworkingstaff}', [NonWorkingStaffController::class, 'Update'])->name('admin.nonworkingstaff.update');
            Route::post('/delete/{nonworkingstaff}', [NonWorkingStaffController::class, 'Delete'])->name('admin.nonworkingstaff.delete');
            Route::post('/status/{id}', [NonWorkingStaffController::class, 'updateStatus'])->name('admin.nonworkingstaff.status');
        });

        Route::prefix('attendance')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('admin.attendance');
            Route::get('/mark', [AttendanceController::class, 'markAttendancePage'])->name('admin.attendance.mark-page');
            Route::get('/matrix', [AttendanceController::class, 'getAttendanceMatrix']);
            Route::post('/mark', [AttendanceController::class, 'markAttendance'])->name('admin.attendance.mark');
            Route::put('/{id}', [AttendanceController::class, 'updateAttendance'])->name('admin.attendance.update');
            Route::post('/{id}/confirm', [AttendanceController::class, 'confirmAttendance'])->name('admin.attendance.confirm');
            Route::delete('/{id}', [AttendanceController::class, 'deleteAttendance'])->name('admin.attendance.delete');

            // AJAX routes for dynamic dropdowns
            Route::get('/classes/{institutionId}', [AttendanceController::class, 'getClassesByInstitution']);
            Route::get('/sections/{classId}', [AttendanceController::class, 'getSectionsByClass']);
            Route::get('/students', [AttendanceController::class, 'getStudentsByClassSection']);
            Route::get('/teachers/{institutionId}', [AttendanceController::class, 'getTeachersByInstitution']);
            Route::get('/staff/{institutionId}', [AttendanceController::class, 'getStaffByInstitution']);
        });


        // Classes
        Route::prefix('classes')->group(function () {
            Route::get('/',[SchoolClassController::class,'index'])->name('admin.classes.index');
            Route::post('/store',[SchoolClassController::class,'store'])->name('admin.classes.store');
            Route::get('/list',[SchoolClassController::class,'getSchoolClasses'])->name('admin.classes.list');
            Route::get('/edit/{id}', [SchoolClassController::class, 'edit'])->name('admin.classes.edit');
            Route::post('/update/{id}', [SchoolClassController::class, 'update'])->name('admin.classes.update');
            Route::post('/delete/{id}', [SchoolClassController::class, 'delete'])->name('admin.classes.delete');
            Route::post('/{id}/status',[SchoolClassController::class,'updateStatus'])->name('admin.classes.status');
        });

        // SECTIONS
        Route::prefix('sections')->group(function(){
            Route::get('/',[SectionController::class,'index'])->name('admin.sections.index');
            Route::post('/store',[SectionController::class,'store'])->name('admin.sections.store');
            Route::get('/by-institution/{institution_id}', [SectionController::class, 'getByInstitution']);
            Route::get('/classes/{institutionId}', [SectionController::class, 'getClassesByInstitution'])->name('admin.sections.classes');
            Route::get('/list',[SectionController::class,'getSections'])->name('admin.sections.list');
            Route::get('/edit/{id}', [SectionController::class, 'edit'])->name('admin.sections.edit');
            Route::post('/update/{id}', [SectionController::class, 'update'])->name('admin.sections.update');
            Route::post('/delete/{id}', [SectionController::class, 'delete'])->name('admin.sections.delete');
            Route::post('/{id}/status',[SectionController::class,'updateStatus'])->name('admin.sections.status');
        });

        // SUBJECTS
        Route::prefix('subjects')->group(function () {
            Route::get('/', [SubjectController::class, 'Index'])->name('admin.subjects.index');
            Route::post('/store', [SubjectController::class, 'store'])->name('admin.subjects.store');
            Route::get('/list', [SubjectController::class, 'getSubjects'])->name('admin.subjects.list');
            Route::post('/filter', [SubjectController::class, 'filter'])->name('admin.subjects.filter');
            Route::post('/{id}/status', [SubjectController::class, 'updateStatus'])->name('admin.subjects.status');
            Route::get('/edit/{id}', [SubjectController::class, 'edit'])->name('admin.subjects.edit');
            Route::post('/update/{id}', [SubjectController::class, 'update'])->name('admin.subjects.update');
            Route::post('/delete/{id}', [SubjectController::class, 'delete'])->name('admin.subjects.delete');

            // AJAX routes for dynamic dropdowns
            Route::get('/classes/{institutionId}', [SubjectController::class, 'getClassesByInstitution'])->name('admin.subjects.classes');
        });

        // ASSIGN CLASS TEACHER
        Route::prefix('academic')->group(function () {
            Route::prefix('assign-class-teacher')->group(function () {
                Route::get('/', [AssignClassTeacherController::class, 'index'])->name('admin.academic.assign-teacher.index');
                Route::post('/', [AssignClassTeacherController::class, 'store'])->name('admin.assign-class-teacher.store');
                Route::get('/list', [AssignClassTeacherController::class, 'list'])->name('admin.assign-class-teacher.list');
                Route::post('/filter', [AssignClassTeacherController::class, 'filter'])->name('admin.assign-class-teacher.filter');

                // AJAX routes for dynamic dropdowns (must come before parameterized routes)
                Route::get('/classes/{institutionId}', [AssignClassTeacherController::class, 'getClassesByInstitution']);
                Route::get('/teachers/{institutionId}', [AssignClassTeacherController::class, 'getTeachersByInstitution']);
                Route::get('/sections/{classId}', [AssignClassTeacherController::class, 'getSectionsByClass']);

                // Parameterized routes (must come after specific routes)
                Route::get('/{id}/edit', [AssignClassTeacherController::class, 'edit'])->name('admin.assign-class-teacher.edit');
                Route::post('/{id}', [AssignClassTeacherController::class, 'update'])->name('admin.assign-class-teacher.update');
                Route::delete('/{id}', [AssignClassTeacherController::class, 'destroy'])->name('admin.assign-class-teacher.destroy');
                Route::post('/{id}/status', [AssignClassTeacherController::class, 'updateStatus'])->name('admin.assign-class-teacher.status');
            });
        });

        // ASSIGN SUBJECT
        Route::prefix('assign-subject')->group(function () {
            Route::get('/', [AssignSubjectController::class, 'index'])->name('admin.assign-subject.index');
            Route::post('/', [AssignSubjectController::class, 'store'])->name('admin.assign-subject.store');
            Route::get('/list', [AssignSubjectController::class, 'getAssignments'])->name('admin.assign-subject.list');
            Route::post('/filter', [AssignSubjectController::class, 'filter'])->name('admin.assign-subject.filter');

            // AJAX routes for dynamic dropdowns (must come before parameterized routes)
            Route::get('/classes/{institutionId}', [AssignSubjectController::class, 'getClassesByInstitution']);
            Route::get('/teachers/{institutionId}', [AssignSubjectController::class, 'getTeachersByInstitution']);
            Route::get('/subjects/{institutionId}/{classId}', [AssignSubjectController::class, 'getSubjectsByInstitutionClass']);
            Route::get('/sections/{classId}', [AssignSubjectController::class, 'getSectionsByClass']);

            // Parameterized routes (must come after specific routes)
            Route::get('/{id}/edit', [AssignSubjectController::class, 'edit'])->name('admin.assign-subject.edit');
            Route::post('/{id}', [AssignSubjectController::class, 'update'])->name('admin.assign-subject.update');
            Route::delete('/{id}', [AssignSubjectController::class, 'destroy'])->name('admin.assign-subject.destroy');
            Route::post('/{id}/status', [AssignSubjectController::class, 'updateStatus'])->name('admin.assign-subject.status');
        });

        // ASSIGNMENTS
        Route::prefix('assignments')->group(function () {
            Route::get('/', [AssignmentController::class, 'index'])->name('admin.assignments.index');
            Route::post('/', [AssignmentController::class, 'store'])->name('admin.assignments.store');
            Route::get('/{id}/edit', [AssignmentController::class, 'edit'])->name('admin.assignments.edit');
            Route::post('/{id}', [AssignmentController::class, 'update'])->name('admin.assignments.update');
            Route::delete('/{id}', [AssignmentController::class, 'destroy'])->name('admin.assignments.destroy');
            Route::post('/{id}/status', [AssignmentController::class, 'updateStatus'])->name('admin.assignments.status');
            Route::get('/{id}/submissions', [AssignmentController::class, 'viewSubmissions'])->name('admin.assignments.submissions');
            Route::post('/{id}/grade', [AssignmentController::class, 'gradeAssignment'])->name('admin.assignments.grade');
            Route::get('/submission/{id}/download', [AssignmentController::class, 'downloadStudentSubmission'])->name('admin.assignments.download-submission');
            Route::get('/{id}/download', [AssignmentController::class, 'downloadAssignment'])->name('admin.assignments.download-assignment');

            // AJAX routes for dynamic dropdowns (must come before parameterized routes)
            Route::get('/classes/{institutionId}', [AssignmentController::class, 'getClassesByInstitution']);
            Route::get('/teachers/{institutionId}', [AssignmentController::class, 'getTeachersByInstitution']);
            Route::get('/subjects/{institutionId}/{classId}', [AssignmentController::class, 'getSubjectsByInstitutionClass']);
            Route::get('/sections/{classId}', [AssignmentController::class, 'getSectionsByClass']);
        });

        // Class Room Routes


        // EXAM MANAGEMENT
        Route::prefix('exam-management')->group(function () {
            Route::get('/exams',[ExamController::class,'index'])->name('admin.exam-management.exams');
            Route::get('/exams/get-classes-sections/{institution}', [ExamController::class, 'getClassesSections'])->name('admin.exams.getClassesSections');
            Route::get('/exams/get-exam-type/{institution}', [ExamController::class, 'getExamType'])->name('admin.exams.getExamType');

            // Future exam management routes can be added here
            Route::get('/exam-type',[ExamTypeController::class,'index'])->name('admin.exam-management.exam-type');
            Route::post('/exam-type/store',[ExamTypeController::class,'store'])->name('admin.exam-management.exam-type.store');
            Route::post('/exam-type/update',[ExamTypeController::class,'update'])->name('admin.exam-management.exam-type.update');

            Route::get('/exam-setup',[ExamSetupController::class,'index'])->name('admin.exam-management.exam-setup');
            Route::get('/exam-setup/fetch-data',[ExamSetupController::class,'fetchData'])->name('admin.exam-management.exam-setup.fetchdata');
            Route::get('/exam-setup/fetch-subjects', [ExamSetupController::class, 'fetchSubjects'])->name('admin.exam-management.exam-setup.fetch-subjects');
            Route::post('/exam-setup/store',[ExamSetupController::class,'store'])->name('admin.exam-management.exam-setup.store');

            Route::prefix('rooms')->group(function () {
                Route::get('/', [ClassRoomController::class, 'index'])->name('admin.exam-management.rooms.index');
                Route::get('/create', [ClassRoomController::class, 'create'])->name('admin.exam-management.rooms.create');
                Route::post('/store', [ClassRoomController::class, 'store'])->name('admin.exam-management.rooms.store');
                Route::post('/store-with-layout', [ClassRoomController::class, 'storeWithLayout'])->name('admin.exam-management.rooms.store-with-layout');
                Route::get('/{id}', [ClassRoomController::class, 'show'])->name('admin.exam-management.rooms.show');
                Route::get('/{id}/edit', [ClassRoomController::class, 'edit'])->name('admin.exam-management.rooms.edit');
                Route::get('/{id}/design-layout', [ClassRoomController::class, 'designLayout'])->name('admin.exam-management.rooms.design-layout');
                Route::post('/{id}/update', [ClassRoomController::class, 'update'])->name('admin.exam-management.rooms.update');
                Route::post('/{id}/update-layout', [ClassRoomController::class, 'updateLayout'])->name('admin.exam-management.rooms.update-layout');
                Route::delete('/{id}', [ClassRoomController::class, 'destroy'])->name('admin.exam-management.rooms.destroy');
            });
        });

        // Academic Calendar Routes
        Route::prefix('calendar')->group(function () {
            Route::get('/', [CalendarController::class, 'index'])->name('admin.academic.calendar.index');
            Route::get('/events', [CalendarController::class, 'getEvents'])->name('admin.academic.calendar.events');
            Route::get('/check-database', [CalendarController::class, 'checkDatabase'])->name('admin.academic.calendar.check-database');
            Route::post('/events', [CalendarController::class, 'store'])->name('admin.academic.calendar.store');
            Route::put('/events/{id}', [CalendarController::class, 'update'])->name('admin.academic.calendar.update');
            Route::delete('/events/{id}', [CalendarController::class, 'destroy'])->name('admin.academic.calendar.destroy');
            Route::get('/events/{id}', [CalendarController::class, 'getEvent'])->name('admin.academic.calendar.show');
        });

        // Event Management Routes
        Route::prefix('events')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('admin.events.index');
            Route::post('/', [EventController::class, 'store'])->name('admin.events.store');
            Route::get('/list', [EventController::class, 'getEvents'])->name('admin.events.list');
            Route::get('/{id}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
            Route::put('/{id}', [EventController::class, 'update'])->name('admin.events.update');
            Route::post('/delete/{id}', [EventController::class, 'delete'])->name('admin.events.delete');
            Route::post('/{id}/status', [EventController::class, 'updateStatus'])->name('admin.events.status');
        });


        // Email / Sms Routes
        Route::prefix('email-sms')->group(function () {
            Route::get('/', [EmailSmsController::class, 'index'])->name('admin.email-sms.index');
            Route::get('/index', [EmailSmsController::class, 'index'])->name('admin.email-sms.index');
            Route::post('/store', [EmailSmsController::class, 'store'])->name('admin.email-sms.store');
            Route::get('/list', [EmailSmsController::class, 'getEmailSms'])->name('admin.email-sms.list');
            Route::get('/edit/{id}', [EmailSmsController::class, 'edit'])->name('admin.email-sms.edit');
            Route::post('/update/{id}', [EmailSmsController::class, 'update'])->name('admin.email-sms.update');
            Route::post('/delete/{id}', [EmailSmsController::class, 'delete'])->name('admin.email-sms.delete');
            Route::post('/{id}/status', [EmailSmsController::class, 'updateStatus'])->name('admin.email-sms.status');

            // Group selection routes
            Route::get('/institutions', [EmailSmsController::class, 'getInstitutions'])->name('admin.email-sms.institutions');
            Route::get('/teachers/{institutionId}', [EmailSmsController::class, 'getTeachersByInstitution'])->name('admin.email-sms.teachers');
            Route::get('/students/{institutionId}', [EmailSmsController::class, 'getStudentsByInstitution'])->name('admin.email-sms.students');
            Route::get('/parents/{institutionId}', [EmailSmsController::class, 'getParentsByInstitution'])->name('admin.email-sms.parents');
            Route::get('/non-working-staff/{institutionId}', [EmailSmsController::class, 'getNonWorkingStaffByInstitution'])->name('admin.email-sms.non-working-staff');

            // Class selection routes
            Route::get('/classes/{institutionId}', [EmailSmsController::class, 'getClassesByInstitution'])->name('admin.email-sms.classes');
            Route::get('/sections/{classId}', [EmailSmsController::class, 'getSectionsByClass'])->name('admin.email-sms.sections');
            Route::get('/class-students-parents/{classId}/{sectionId?}', [EmailSmsController::class, 'getStudentsAndParentsByClassSection'])->name('admin.email-sms.class-students-parents');
        });

        // ROUTINE MANAGEMENT
        Route::prefix('routines')->group(function () {
            Route::get('/', [RoutineController::class, 'index'])->name('admin.routines.index');
            Route::get('/create', [RoutineController::class, 'create'])->name('admin.routines.create');
            Route::post('/', [RoutineController::class, 'store'])->name('admin.routines.store');
            Route::get('/report', [RoutineController::class, 'getRoutineReport'])->name('admin.routines.report');
            Route::post('/{id}/status', [RoutineController::class, 'updateStatus'])->name('admin.routines.status');
            Route::delete('/{id}', [RoutineController::class, 'destroy'])->name('admin.routines.destroy');

            // API routes for dynamic dropdowns
            Route::get('/classes/{institutionId}', [RoutineController::class, 'getClassesByInstitution'])->name('admin.routines.classes');
            Route::get('/sections/{classId}', [RoutineController::class, 'getSectionsByClass'])->name('admin.routines.sections');
            Route::get('/subjects/{institutionId}/{classId}', [RoutineController::class, 'getSubjectsByInstitutionClass'])->name('admin.routines.subjects');
            Route::get('/teachers/{institutionId}', [RoutineController::class, 'getTeachersByInstitution'])->name('admin.routines.teachers');
        });
        // LESSON PLANS
        Route::prefix('lesson-plans')->group(function () {
            Route::get('/', [LessonPlanController::class, 'index'])->name('admin.lesson-plans.index');
            Route::post('/', [LessonPlanController::class, 'store'])->name('admin.lesson-plans.store');
            Route::get('/{id}/edit', [LessonPlanController::class, 'edit'])->name('admin.lesson-plans.edit');
            Route::POST('/{id}', [LessonPlanController::class, 'update'])->name('admin.lesson-plans.update');
            Route::delete('/{id}', [LessonPlanController::class, 'destroy'])->name('admin.lesson-plans.destroy');
            Route::post('/{id}/status', [LessonPlanController::class, 'updateStatus'])->name('admin.lesson-plans.status');
            Route::get('/{id}/download', [LessonPlanController::class, 'download'])->name('admin.lesson-plans.download');

            // API routes for dynamic dropdowns
            Route::get('/teachers/{institutionId}', [LessonPlanController::class, 'getTeachersByInstitution'])->name('admin.lesson-plans.teachers');
            Route::get('/classes/{institutionId}', [LessonPlanController::class, 'getClassesByInstitution'])->name('admin.lesson-plans.classes');
            Route::get('/classes-by-teacher/{institutionId}/{teacherId}', [LessonPlanController::class, 'getClassesByTeacher'])->name('admin.lesson-plans.classes-by-teacher');
            Route::get('/subjects/{institutionId}/{classId}', [LessonPlanController::class, 'getSubjectsByInstitutionClass'])->name('admin.lesson-plans.subjects');
        });
    });
});



require_once __DIR__ . '/institution.php';
require_once __DIR__ . '/teacher.php';
require_once __DIR__ . '/student.php';
