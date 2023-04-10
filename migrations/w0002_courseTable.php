<?php
class w0002_courseTable{
public function up(){
    $db = \app\core\App::$app->db;
    $SQL = "CREATE TABLE courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        code VARCHAR(20) NOT NULL,
        created_by INT NOT NULL,
        FOREIGN KEY (created_by) REFERENCES users(id),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=INNODB;";
    $db->pdo->exec($SQL);
}

public function down(){
    $db = \app\core\App::$app->db;
    $SQL = "DROP TABLE courses";
    $db->pdo->exec($SQL);
}
}