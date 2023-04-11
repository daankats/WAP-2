<?php
class w0007_Add_date_time_to_exam{
    public function up(){
        $db = \app\core\App::$app->db;
        $SQL = "ALTER TABLE exams ADD exam_date DATE NOT NULL,
                ADD exam_time TIME NOT NULL,
                ADD exam_place VARCHAR(255) NOT NULL;";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = \app\core\App::$app->db;
        $SQL = "ALTER TABLE exams DROP exam_date,
                DROP exam_time,
                DROP exam_place;";
        $db->pdo->exec($SQL);
    }
}