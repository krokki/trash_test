<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        $this->db = db_connect();

        $this->db->query("
            CREATE TABLE comments(
            id INT PRIMARY KEY auto_increment,
            name VARCHAR(255),
            text VARCHAR(255),
            date datetime);
        ");
    }

    public function down()
    {
        $this->db = db_connect();

        $this->db->query("DROP TABLE comments");
    }
}
