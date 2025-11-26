<?php
class User {
    protected int $id;
    protected string $username;
    protected string $passwordHash;
    protected bool $is_admin;

    public function __construct(int $id, string $username, string $passwordHash, bool $is_admin) {
        $this->id = $id;
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->is_admin = $is_admin;
    }

    public function getId(): int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getPassword(): string { return $this->passwordHash; }
    public function getIsAdmin(): bool { return $this->is_admin; }


    // Setters con tipos de parámetros
    public function setUsername(string $username): void { $this->username = $username; }
    public function setPassword(string $password): void { $this->password = $password; }
    public function setIsAdmin(bool $is_admin): void { $this->is_admin = $is_admin; }
}