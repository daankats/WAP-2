<?php
class w0005_gradesTable {
    public function up() {
        $db = \app\core\App::$app->db;
        $SQL = "CREATE TABLE grades (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            exam_id INT NOT NULL,
            grade FLOAT NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=INNODB;";
        $db->pdo->exec($SQL);
    }

    public function down() {
        $db = \app\core\App::$app->db;
        $SQL = "DROP TABLE grades";
        $db->pdo->exec($SQL);
    }
}