<?php

class w0004_RegistrationTable{
    public function up(){
        $db = \app\core\App::$app->db;
        $SQL = "CREATE TABLE registrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            exam_id INT NOT NULL,
            FOREIGN KEY (exam_id) REFERENCES exams(id),
            student_id INT NOT NULL,
            FOREIGN KEY (student_id) REFERENCES users(id),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = \app\core\App::$app->db;
        $SQL = "DROP TABLE registrations";
        $db->pdo->exec($SQL);
    }
}