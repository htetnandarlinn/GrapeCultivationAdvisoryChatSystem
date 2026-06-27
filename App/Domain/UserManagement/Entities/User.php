<?php

namespace App\Domain\UserManagement\Entities;

use App\Domain\UserManagement\ValueObjects\Email;
use App\Domain\UserManagement\ValueObjects\UserStatus;
use App\Domain\UserManagement\ValueObjects\UserType;

final class User
{
    private ?int $id;

    private string $username;

    private string $name;

    private Email $email;

    private string $phoneNumber;

    private string $address;

    private ?string $profileImage;

    private string $passwordHash;

    private UserType $type;

    private UserStatus $status;

    private \DateTimeImmutable $createdAt;

    private ?\DateTimeImmutable $updatedAt;

    private ?\DateTimeImmutable $deletedAt;

    public function __construct(
        ?int $id,
        string $username,
        Email $email,
        string $phoneNumber,
        string $address,
        string $passwordHash,
        UserType $type,
        UserStatus $status,
        ?string $profileImage = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null,
        ?\DateTimeImmutable $deletedAt = null
    ) {
        $this->id = $id;
        $this->username = trim($username);
        $this->email = $email;
        $this->phoneNumber = trim($phoneNumber);
        $this->address = trim($address);
        $this->passwordHash = $passwordHash;
        $this->type = $type;
        $this->status = $status;
        $this->profileImage = $profileImage;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = trim($username);
    }

    public function setName(string $name): void
    {
        $this->name = trim($name);
    }

    /**
     * Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * Phone
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getPhone(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = trim($phoneNumber);
    }

    /**
     * Address
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = trim($address);
    }

    /**
     * Profile Image
     */
    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }

    public function setProfileImage(?string $profileImage): void
    {
        $this->profileImage = $profileImage;
    }

    /**
     * Password
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }

    /**
     * User Type
     */
    public function getType(): UserType
    {
        return $this->type;
    }

    public function setType(UserType $type): void
    {
        $this->type = $type;
    }

    /**
     * Status
     */
    public function getStatus(): UserStatus
    {
        return $this->status;
    }

    public function setStatus(UserStatus $status): void
    {
        $this->status = $status;
    }

    public function isActive(): bool
    {
        return strtolower($this->status->getValue()) === 'active';
    }

    /**
     * Dates
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function delete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    public function restore(): void
    {
        $this->deletedAt = null;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }
}