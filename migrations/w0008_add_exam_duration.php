<?php
class w0008_add_exam_duration{
    public function up(){
        $db = \app\core\App::$app->db;
        $SQL = "ALTER TABLE exams ADD exam_duration TIME NOT NULL;";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = \app\core\App::$app->db;
        $SQL = "ALTER TABLE exams DROP exam_duration;";
        $db->pdo->exec($SQL);
    }
}