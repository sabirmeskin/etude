<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';
require_once __DIR__ . '/../models/Note.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Subject.php';

class NoteController extends BaseController
{
    public function index()
    {
        AuthHelper::requireProfesseurOuAdmin();

        $studentId = $_GET['student_id'] ?? null;
        if ($studentId) {
            $this->listByStudent();
            return;
        }

        header('Location: /index.php?r=notes/bulk');
        exit;
    }

    public function create()
    {
        header('Location: /index.php?r=notes/bulk');
        exit;
    }

    public function listByStudent()
    {
        AuthHelper::requireProfesseurOuAdmin();

        $studentId = (int) ($_GET['student_id'] ?? 0);
        if (!$studentId) {
            header('Location: /index.php?r=notes/bulk');
            exit;
        }

        if (AuthHelper::isProfesseur() && !AuthHelper::teacherCanAccessStudent($studentId)) {
            http_response_code(403);
            echo 'Acces refuse';
            exit;
        }

        $student = Student::find($studentId);
        if (!$student) {
            header('Location: /index.php?r=notes/bulk');
            exit;
        }

        $search = $_GET['search'] ?? '';
        $page = (int) ($_GET['page'] ?? 1);

        $matiereFilter = null;
        if (AuthHelper::isProfesseur()) {
            require_once __DIR__ . '/../models/ProfessorAssignment.php';
            $uid = (int) (AuthHelper::user()['id'] ?? 0);
            if (ProfessorAssignment::tableExists() && ProfessorAssignment::countForTeacher($uid) > 0) {
                $classeId = (int) ($student['classe_id'] ?? 0);
                $matiereFilter = $classeId > 0
                    ? ProfessorAssignment::matiereIdsForTeacherClass($uid, $classeId)
                    : [];
            }
        }

        $pager = Note::paginateByStudent($studentId, $page, 5, $search, $matiereFilter);
        $notes = $pager['data'];
        $avg = Note::average($studentId, $matiereFilter);
        $stats = Student::getGradeStats($studentId, $matiereFilter);
        $this->render('notes/list', [
            'notes' => $notes,
            'avg' => $avg,
            'student' => $student,
            'stats' => $stats,
            'pager' => $pager,
            'search' => $search,
        ]);
    }

    public function stats()
    {
        AuthHelper::requireProfesseurOuAdmin();

        $classId = $_GET['class_id'] ?? null;
        if (!$classId) {
            header('Location: /index.php?r=notes/bulk');
            exit;
        }

        if (AuthHelper::isProfesseur() && !AuthHelper::teacherCanAccessClass((int) $classId)) {
            http_response_code(403);
            echo 'Acces refuse';
            exit;
        }

        require_once __DIR__ . '/../models/ClassModel.php';
        $class = ClassModel::find($classId);
        $classStats = Student::getClassStats($classId);
        $this->render('notes/stats', ['class' => $class, 'classStats' => $classStats]);
    }

    public function bulk()
    {
        AuthHelper::requireProfesseurOuAdmin();

        require_once __DIR__ . '/../models/ClassModel.php';
        $subjects = Subject::all();
        $classes = ClassModel::all();

        if (AuthHelper::isProfesseur()) {
            $allowed = AuthHelper::teacherClassIds();
            $classes = array_values(array_filter($classes, static function ($c) use ($allowed) {
                return in_array((int) $c['id'], $allowed, true);
            }));
        }

        $selectedClass = $_GET['class_id'] ?? null;
        $selectedSubject = $_GET['subject_id'] ?? null;

        if (AuthHelper::isProfesseur() && $selectedClass) {
            require_once __DIR__ . '/../models/ProfessorAssignment.php';
            $uid = (int) (AuthHelper::user()['id'] ?? 0);
            if (ProfessorAssignment::tableExists() && ProfessorAssignment::countForTeacher($uid) > 0) {
                $allowedM = ProfessorAssignment::matiereIdsForTeacherClass($uid, (int) $selectedClass);
                $subjects = array_values(array_filter($subjects, static function ($s) use ($allowedM) {
                    return in_array((int) $s['id'], $allowedM, true);
                }));
            }
        }

        $students = [];

        if ($selectedClass && $selectedSubject) {
            if (!AuthHelper::teacherCanAccessClass((int) $selectedClass)) {
                http_response_code(403);
                echo 'Acces refuse';
                exit;
            }
            if (AuthHelper::isProfesseur() && !AuthHelper::teacherCanGrade((int) $selectedClass, (int) $selectedSubject)) {
                http_response_code(403);
                echo 'Acces refuse : matiere non assignee pour cette classe.';
                exit;
            }
            $students = Student::byClass($selectedClass);
            foreach ($students as &$student) {
                $existing = Note::getByStudentAndSubject($student['id'], $selectedSubject);
                $student['existing_grade'] = $existing ? $existing['note'] : '';
            }
        }

        $this->render('notes/bulk', [
            'subjects' => $subjects,
            'classes' => $classes,
            'students' => $students,
            'selectedClass' => $selectedClass,
            'selectedSubject' => $selectedSubject,
        ]);
    }

    public function saveBulk()
    {
        AuthHelper::requireProfesseurOuAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?r=notes/bulk');
            exit;
        }

        $classId = $_POST['class_id'] ?? null;
        $subjectId = $_POST['subject_id'] ?? null;

        if (!$classId || !$subjectId) {
            header('Location: /index.php?r=notes/bulk');
            exit;
        }

        if (!AuthHelper::teacherCanAccessClass((int) $classId)) {
            http_response_code(403);
            echo 'Acces refuse';
            exit;
        }

        if (AuthHelper::isProfesseur() && !AuthHelper::teacherCanGrade((int) $classId, (int) $subjectId)) {
            http_response_code(403);
            echo 'Acces refuse';
            exit;
        }

        $grades = [];
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'grade_') === 0) {
                $sid = (int) str_replace('grade_', '', $key);
                if (!AuthHelper::teacherCanAccessStudent($sid)) {
                    continue;
                }
                if (!empty($value) || $value === '0') {
                    $grades[] = [
                        'student_id' => $sid,
                        'matiere_id' => $subjectId,
                        'note' => $value,
                    ];
                }
            }
        }

        if (!empty($grades)) {
            Note::createBulk($grades);
        }

        header('Location: /index.php?r=notes/bulk&class_id=' . $classId . '&subject_id=' . $subjectId . '&saved=1');
        exit;
    }
}

