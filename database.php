<?php
		function open_database()
        {
            $conn = new mysqli('localhost', 'root', '', 'qlct');
            if ($conn->connect_error) {
                die('cant connect' . $conn->connect_error);
            }
            $conn->set_charset('UTF8');
            return $conn;
        }
?>