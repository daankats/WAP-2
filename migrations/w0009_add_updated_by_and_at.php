<?php
class w0009_add_updated_by_and_at{
    public function up() {
        $db = \app\core\App::$app->db;

        // Add updated_by and updated_at to courses table
        $SQL = "ALTER TABLE courses ADD updated_by INT NOT NULL, ADD updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        $db->pdo->exec($SQL);

        // Add updated_by and updated_at to exams table
        $SQL = "ALTER TABLE exams ADD updated_by INT NOT NULL, ADD updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        $db->pdo->exec($SQL);
    }

    public function down() {
        $db = \app\core\App::$app->db;

        // Remove updated_by and updated_at from courses table
        $SQL = "ALTER TABLE courses DROP updated_by, DROP updated_at";
        $db->pdo->exec($SQL);

        // Remove updated_by and updated_at from exams table
        $SQL = "ALTER TABLE exams DROP updated_by, DROP updated_at";
        $db->pdo->exec($SQL);
    }
}