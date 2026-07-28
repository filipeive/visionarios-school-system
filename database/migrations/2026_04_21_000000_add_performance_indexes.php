<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Indexes for Enrollments table
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['student_id', 'school_year', 'status'], 'enrollments_student_year_status');
            $table->index(['class_id', 'status'], 'enrollments_class_status');
        });

        // Indexes for Payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['student_id', 'status', 'due_date'], 'payments_student_status_duedate');
            $table->index(['status', 'due_date'], 'payments_status_duedate');
            $table->index(['year', 'month', 'type'], 'payments_year_month_type');
        });

        // Indexes for Grades table
        Schema::table('grades', function (Blueprint $table) {
            $table->index(['student_id', 'subject_id', 'term', 'year'], 'grades_student_subject_term');
            $table->index(['class_id', 'term', 'year'], 'grades_class_term_year');
            $table->index(['teacher_id', 'term', 'year'], 'grades_teacher_term_year');
        });

        // Indexes for Attendances table
        Schema::table('attendances', function (Blueprint $table) {
            $table->index(['student_id', 'attendance_date'], 'attendances_student_date');
            $table->index(['class_id', 'attendance_date'], 'attendances_class_date');
        });

        // Indexes for Students table
        Schema::table('students', function (Blueprint $table) {
            $table->index(['status', 'student_number'], 'students_status_number');
            $table->index(['first_name', 'last_name'], 'students_name');
        });
    }

    public function down()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('enrollments_student_year_status');
            $table->dropIndex('enrollments_class_status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_student_status_duedate');
            $table->dropIndex('payments_status_duedate');
            $table->dropIndex('payments_year_month_type');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropIndex('grades_student_subject_term');
            $table->dropIndex('grades_class_term_year');
            $table->dropIndex('grades_teacher_term_year');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('attendances_student_date');
            $table->dropIndex('attendances_class_date');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_status_number');
            $table->dropIndex('students_name');
        });
    }
};
