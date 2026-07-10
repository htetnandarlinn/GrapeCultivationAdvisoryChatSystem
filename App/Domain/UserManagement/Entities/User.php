<?php

namespace App\Domain\UserManagement\Entities;

use App\Domain\UserManagement\ValueObjects\Email;
use App\Domain\UserManagement\ValueObjects\UserStatus;
use App\Domain\UserManagement\ValueObjects\UserType;

final class User
{
    private ?int $id;

    private string $username;

    private Email $email;

    private string $phoneNumber;

    private string $address;

    private ?string $profileImage;

    private ?string $googleId;

    private string $passwordHash;

    private UserType $type;

    private UserStatus $status;

    private bool $isVerified;

    private bool $isLogin;

    private ?string $verificationToken;

    private ?\DateTimeImmutable $verificationTokenExpireAt;

    private ?\DateTimeImmutable $emailVerifiedAt;

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
        bool $isVerified = false,
        bool $isLogin = false,
        ?string $profileImage = null,
        ?string $googleId = null,
        ?string $verificationToken = null,
        ?\DateTimeImmutable $verificationTokenExpireAt = null,
        ?\DateTimeImmutable $emailVerifiedAt = null,
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
        $this->isVerified = $isVerified;
        $this->isLogin = $isLogin;
        $this->profileImage = $profileImage;
        $this->googleId = $googleId;
        $this->verificationToken = $verificationToken;
        $this->verificationTokenExpireAt = $verificationTokenExpireAt;
        $this->emailVerifiedAt = $emailVerifiedAt;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    /* ===========================
       ID
    =========================== */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /* ===========================
       Username
    =========================== */

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = trim($username);
    }

    /* ===========================
       Email
    =========================== */

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    /* ===========================
       Phone
    =========================== */

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = trim($phoneNumber);
    }

    /* ===========================
       Address
    =========================== */

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = trim($address);
    }

    /* ===========================
       Password
    =========================== */

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $hash): void
    {
        $this->passwordHash = $hash;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }

    /* ===========================
       Profile Image
    =========================== */

    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }

    public function setProfileImage(?string $image): void
    {
        $this->profileImage = $image;
    }

    /* ===========================
       Google ID
    =========================== */

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): void
    {
        $this->googleId = $googleId;
    }

    /* ===========================
       User Type
    =========================== */

    public function getType(): UserType
    {
        return $this->type;
    }

    public function setType(UserType $type): void
    {
        $this->type = $type;
    }

    /* ===========================
       Status
    =========================== */

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
        return $this->status->isActive();
    }

    /* ===========================
       Verification
    =========================== */

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $verified): void
    {
        $this->isVerified = $verified;
    }

    public function getVerificationToken(): ?string
    {
        return $this->verificationToken;
    }

    public function setVerificationToken(?string $token): void
    {
        $this->verificationToken = $token;
    }

    public function getVerificationTokenExpireAt(): ?\DateTimeImmutable
    {
        return $this->verificationTokenExpireAt;
    }

    public function setVerificationTokenExpireAt(?\DateTimeImmutable $expire): void
    {
        $this->verificationTokenExpireAt = $expire;
    }

    public function getEmailVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->emailVerifiedAt;
    }

    public function setEmailVerifiedAt(?\DateTimeImmutable $date): void
    {
        $this->emailVerifiedAt = $date;
    }

    /* ===========================
       Login
    =========================== */

    public function isLogin(): bool
    {
        return $this->isLogin;
    }

    public function setLogin(bool $login): void
    {
        $this->isLogin = $login;
    }

    /* ===========================
       Dates
    =========================== */

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
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