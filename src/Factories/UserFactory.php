<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/RegularUser.php';
require_once __DIR__ . '/../Models/AdminUser.php';

class UserFactory {
    public static function createUser(int $id, string $username, string $passwordHash, bool $is_admin): User {
        if ($is_admin) {
            return new AdminUser($id, $username, $passwordHash, $is_admin);
        } else {
            return new RegularUser($id, $username, $passwordHash, $is_admin);
        }
    }
}
