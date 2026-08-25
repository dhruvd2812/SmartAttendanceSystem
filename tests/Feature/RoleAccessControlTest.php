<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_has_full_access_to_admin_pages_only(): void
    {
        $department = Department::create([
            'name' => 'Computer Science',
            'department_name' => 'Computer Science',
            'code' => 'CS',
            'department_code' => 'CS',
            'hod_name' => 'Dr. Admin',
            'email' => 'hod@cs.edu',
            'phone' => '1111111111',
            'description' => 'Computer Science Department',
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $this->get(route('dashboard'))->assertOk();
        $this->get(route('admin.students.index'))->assertOk();
        $this->get(route('departments.index'))->assertOk();
        $this->get(route('faculties.index'))->assertOk();
        $this->get(route('admin.qr.index'))->assertOk();

        $this->get(route('faculty.dashboard'))->assertForbidden();
        $this->get(route('student.dashboard'))->assertForbidden();
        $this->get(route('faculty.students.index'))->assertForbidden();
    }

    public function test_faculty_can_access_faculty_dashboard_and_student_list_only(): void
    {
        $department = Department::create([
            'name' => 'Mechanical',
            'department_name' => 'Mechanical',
            'code' => 'ME',
            'department_code' => 'ME',
            'hod_name' => 'Dr. HOD',
            'email' => 'hod@me.edu',
            'phone' => '2222222222',
            'description' => 'Mechanical Department',
        ]);

        $faculty = Faculty::create([
            'faculty_name' => 'Jane Faculty',
            'employee_id' => 'F-101',
            'email' => 'faculty@example.com',
            'phone' => '3333333333',
            'department_id' => $department->id,
        ]);

        $user = User::create([
            'name' => 'Jane Faculty',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'faculty',
            'faculty_id' => $faculty->id,
        ]);

        $this->actingAs($user);

        $this->get(route('faculty.dashboard'))->assertOk();
        $this->get(route('faculty.students.index'))->assertOk();
        $this->get(route('faculty.qr.index'))->assertOk();

        $this->get(route('dashboard'))->assertForbidden();
        $this->get(route('student.dashboard'))->assertForbidden();
        $this->get(route('admin.students.index'))->assertForbidden();
    }

    public function test_student_can_access_only_own_dashboard_and_profile(): void
    {
        $department = Department::create([
            'name' => 'Civil',
            'department_name' => 'Civil',
            'code' => 'CE',
            'department_code' => 'CE',
            'hod_name' => 'Dr. Civil',
            'email' => 'hod@ce.edu',
            'phone' => '4444444444',
            'description' => 'Civil Department',
        ]);

        $student = Student::create([
            'enrollment_no' => 'ST-1001',
            'first_name' => 'Rahul',
            'last_name' => 'Student',
            'email' => 'student@example.com',
            'gender' => 'Male',
            'department_id' => $department->id,
            'semester' => 5,
            'status' => 'active',
        ]);

        $user = User::create([
            'name' => 'Rahul Student',
            'email' => 'rahul@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'student_id' => $student->id,
        ]);

        $this->actingAs($user);

        $this->get(route('student.dashboard'))->assertOk();
        $this->get(route('student.profile'))->assertOk();
        $this->get(route('student.attendance'))->assertOk();

        $this->get(route('dashboard'))->assertForbidden();
        $this->get(route('faculty.dashboard'))->assertForbidden();
        $this->get(route('faculty.students.index'))->assertForbidden();
        $this->get(route('admin.students.index'))->assertForbidden();
    }

    public function test_student_create_pages_render_with_correct_route_names(): void
    {
        $department = Department::create([
            'name' => 'Information Technology',
            'department_name' => 'Information Technology',
            'code' => 'IT',
            'department_code' => 'IT',
            'hod_name' => 'Dr. Tech',
            'email' => 'hod@it.edu',
            'phone' => '5555555555',
            'description' => 'IT Department',
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin-create@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $faculty = Faculty::create([
            'faculty_name' => 'Faculty User',
            'employee_id' => 'F-202',
            'email' => 'faculty-create@example.com',
            'phone' => '6666666666',
            'department_id' => $department->id,
        ]);

        $facultyUser = User::create([
            'name' => 'Faculty User',
            'email' => 'faculty-user@example.com',
            'password' => bcrypt('password'),
            'role' => 'faculty',
            'faculty_id' => $faculty->id,
        ]);

        $this->actingAs($admin);
        $this->get(route('admin.students.create'))->assertOk();

        $this->actingAs($facultyUser);
        $this->get(route('faculty.students.create'))->assertOk();
    }

    public function test_dashboard_preview_routes_follow_expected_role_matrix(): void
    {
        $department = Department::create([
            'name' => 'Computer Science',
            'department_name' => 'Computer Science',
            'code' => 'CS',
            'department_code' => 'CS',
            'hod_name' => 'Dr. CS',
            'email' => 'hod@cs.edu',
            'phone' => '7777777777',
            'description' => 'Computer Science Department',
        ]);

        $student = Student::create([
            'enrollment_no' => 'ST-5001',
            'first_name' => 'Preview',
            'last_name' => 'Student',
            'email' => 'preview@student.com',
            'gender' => 'Male',
            'department_id' => $department->id,
            'semester' => 4,
            'status' => 'active',
        ]);

        $admin = User::create([
            'name' => 'Admin Preview',
            'email' => 'admin-preview@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $faculty = Faculty::create([
            'faculty_name' => 'Faculty Preview',
            'employee_id' => 'F-300',
            'email' => 'faculty-preview@example.com',
            'phone' => '8888888888',
            'department_id' => $department->id,
        ]);

        $facultyUser = User::create([
            'name' => 'Faculty Preview',
            'email' => 'faculty-preview-login@example.com',
            'password' => bcrypt('password'),
            'role' => 'faculty',
            'faculty_id' => $faculty->id,
        ]);

        $studentUser = User::create([
            'name' => 'Preview Student',
            'email' => 'student-preview@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'student_id' => $student->id,
        ]);

        $this->actingAs($admin);
        $this->get(route('admin.view.faculty.dashboard'))->assertOk();
        $this->get(route('admin.view.student.dashboard'))->assertOk();
        $this->get(route('dashboard'))->assertOk();

        $this->actingAs($facultyUser);
        $this->get(route('faculty.dashboard'))->assertOk();
        $this->get(route('faculty.view.student.dashboard'))->assertOk();
        $this->get(route('faculty.students.index'))->assertOk();
        $this->get(route('dashboard'))->assertForbidden();

        $this->actingAs($studentUser);
        $this->get(route('student.dashboard'))->assertOk();
        $this->get(route('faculty.dashboard'))->assertForbidden();
        $this->get(route('faculty.students.index'))->assertForbidden();
        $this->get(route('dashboard'))->assertForbidden();
    }
}
