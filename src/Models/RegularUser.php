<?php
require_once 'User.php';

class RegularUser extends User {
    public function __construct(int $id, string $username, string $password, bool $is_admin = false) {
        parent::__construct($id, $username, $password, $is_admin);
    }
}