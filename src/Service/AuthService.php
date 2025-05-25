<?php

namespace MovieStar\Service;

use Medoo\Medoo;
use MovieStar\Core\Database;
use MovieStar\Core\Session;
use MovieStar\Model\UserModel;

class AuthService
{
    private const HASH_SIZE = 72;
    private const CSRF_KEY = "csrftoken";

    private Medoo $db;

    public function __construct(
        private UserService $userService,
    ) {
        $this->db = Database::instance();
    }

    public function token(): string
    {
        return bin2hex(random_bytes(self::HASH_SIZE));
    }

    public function csrf(): string
    {
        $csrf = bin2hex(random_bytes(self::HASH_SIZE));
        Session::set(self::CSRF_KEY, $csrf);
        return $csrf;
    }

    public function csrfVerify(string $csrf): bool
    {
        $sessionCsrf = Session::get(self::CSRF_KEY);
        return !empty($csrf) && $csrf === $sessionCsrf;
    }

    public function isAuth(): bool
    {
        if (!Session::has("user")) {
            return false;
        }

        $sessionUser = Session::get("user");
        $id = (int) ($sessionUser["id"] ?? "");
        $dbUser = $this->db->get(UserModel::TABLE, "*", ["id" => $id]);

        if (!$dbUser) {
            return false;
        }

        $dbToken = $dbUser["token"] ?? null;
        $sessionToken = $sessionUser["token"] ?? null;

        $tokenMatched = $dbToken === $sessionToken;
        $tokenIsValid = $dbToken !== null && $sessionToken !== null;

        return $tokenIsValid && $tokenMatched;
    }

    public function login(string $email, mixed $password): bool
    {
        $updated = false;

        try {
            $user = $this->db->get(UserModel::TABLE, "*", ["email" => $email]);
            if (!$user) {
                InputDataService::all([
                    "email" => $email,
                    "password" => $password
                ]);

                warning("Login attempt with non-existent user");
                flashError("E-mail {$email} not found.");
                return $updated;
            }

            if (!password_verify($password, $user["password"])) {
                InputDataService::all([
                    "email" => $email,
                    "password" => $password
                ]);

                warning("User tried to log in with incorrect password.");
                flashError("Incorrect password. Please try again.");
                return $updated;
            }

            // update user
            $token = $this->token();
            $data = [
                "token" => $token
            ];

            $updated = $this->userService->update($user["id"], $data);
            if ($updated) {
                unset($user["password"]);
                $user["token"] = $token;
                Session::set("user", $user);
            }
        } catch (\Throwable $e) {
            error($e->getMessage());
        }

        match ($updated) {
            false => flashError("Login attempt failed. Please try again."),
            true => flashSuccess("Login successful!"),
        };

        return $updated;
    }

    public function register(int $id): bool
    {
        $registered = true;

        try {
            $user = $this->db->get(UserModel::TABLE, "*", ["id" => $id]);
            if (!$user) {
                warning("Attempt to register user not found");
                $registered = false;
            }

            unset($user["password"]);
            Session::set("user", $user);
        } catch (\Throwable $e) {
            error($e->getMessage());
        }

        match ($registered) {
            false => flashWarning("User not found in database after registration. Try logging in."),
            true => flashSuccess("User registered successfully!"),
        };

        return $registered;
    }

    public function logout(): bool
    {
        $loggedOut = true;

        try {
            Session::delete("user");
            $loggedOut = !$this->isAuth();

            match ($loggedOut) {
                false => flashError("Failed to log out. Please try again."),
                true => flashSuccess("Logout successful!"),
            };
        } catch (\Throwable $e) {
            error($e->getMessage());
        }

        return $loggedOut;
    }
}