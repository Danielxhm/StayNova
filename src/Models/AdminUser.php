<?php
require_once 'User.php';

class AdminUser extends User {
    public function __construct(int $id, string $username, string $password, bool $is_admin = true) {
        parent::__construct($id, $username, $password, $is_admin);
    }
}