<?php 

class w0006_enrollmentTable {
    public function up() {
        $db = \app\core\App::$app->db;
        $SQL = "CREATE TABLE enrollment (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id INT NOT NULL,
            course_id INT NOT NULL,
            status TINYINT NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
        )  ENGINE=INNODB;";
        $db->pdo->exec($SQL);
    }

    public function down() {
        $db = \app\core\App::$app->db;
        $SQL = "DROP TABLE enrollment";
        $db->pdo->exec($SQL);
    }
}