<?php
class w0003_examTable{
    public function up(){
        $db = \app\core\App::$app->db;
        $SQL = "CREATE TABLE exams (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            course_id INT NOT NULL,
            FOREIGN KEY (course_id) REFERENCES courses(id),
            created_by INT NOT NULL,
            FOREIGN KEY (created_by) REFERENCES users(id),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = \app\core\App::$app->db;
        $SQL = "DROP TABLE exams";
        $db->pdo->exec($SQL);
    }
}