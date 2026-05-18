<?php

class AuthHelper
{
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function role(): string
    {
        $u = self::user();
        return $u['role'] ?? 'admin';
    }

    public static function isAdmin(): bool
    {
        return self::role() === 'admin';
    }

    public static function isProfesseur(): bool
    {
        return self::role() === 'professeur';
    }

    public static function isEtudiant(): bool
    {
        return self::role() === 'etudiant';
    }

    public static function etudiantId(): ?int
    {
        $u = self::user();
        if (empty($u['etudiant_id'])) {
            return null;
        }
        return (int) $u['etudiant_id'];
    }

    public static function requireAdmin(): void
    {
        if (!self::isAdmin()) {
            header('Location: /index.php?r=dashboard');
            exit;
        }
    }

    public static function requireProfesseurOuAdmin(): void
    {
        if (!self::isAdmin() && !self::isProfesseur()) {
            header('Location: /index.php?r=dashboard');
            exit;
        }
    }

    public static function teacherClassIds(): array
    {
        if (self::isAdmin()) {
            return [];
        }
        $u = self::user();
        if (!$u || !self::isProfesseur()) {
            return [];
        }
        require_once __DIR__ . '/../models/User.php';
        return User::teacherClassIds((int) $u['id']);
    }

    public static function teacherCanAccessClass(int $classId): bool
    {
        if (self::isAdmin()) {
            return true;
        }
        if (!self::isProfesseur()) {
            return false;
        }
        return in_array($classId, self::teacherClassIds(), true);
    }

    public static function teacherCanAccessStudent(int $studentId): bool
    {
        if (self::isAdmin()) {
            return true;
        }
        if (!self::isProfesseur()) {
            return false;
        }
        require_once __DIR__ . '/../models/Student.php';
        $student = Student::find($studentId);
        if (!$student || empty($student['classe_id'])) {
            return false;
        }
        return self::teacherCanAccessClass((int) $student['classe_id']);
    }

    /**
     * Le professeur peut saisir des notes / devoirs pour cette classe et cette matiere.
     * Si aucune affectation (matiere+classe) n est definie pour ce prof, on retombe sur l acces classe seule (ancien mode).
     */
    public static function teacherCanGrade(int $classId, int $matiereId): bool
    {
        if (self::isAdmin()) {
            return true;
        }
        if (!self::isProfesseur()) {
            return false;
        }
        require_once __DIR__ . '/../models/ProfessorAssignment.php';
        if (!ProfessorAssignment::tableExists()) {
            return self::teacherCanAccessClass($classId);
        }
        $uid = (int) (self::user()['id'] ?? 0);
        if (ProfessorAssignment::countForTeacher($uid) === 0) {
            return self::teacherCanAccessClass($classId);
        }

        return ProfessorAssignment::exists($uid, $classId, $matiereId);
    }
}
