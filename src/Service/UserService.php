<?php

namespace MovieStar\Service;

use Medoo\Medoo;
use MovieStar\Core\Constants;
use MovieStar\Core\Database;
use MovieStar\Core\Session;
use MovieStar\Model\UserModel;

class UserService
{
    private Medoo $db;

    public function __construct(private ImageService $imageService)
    {
        $this->db = Database::instance();
    }

    public function save(UserModel $user): int
    {
        $createdUserId = null;

        try {
            // verify for email
            $userByEmail = $this->db->get(UserModel::TABLE, "*", ["email" => $user->getEmail()]);
            if ($userByEmail) {
                warning("Attempt to create user with existing user");
                flashWarning("There is already a user registered with that email. Try with another email.");
                return 0;
            }

            $this->db->insert(UserModel::TABLE, [
                "firstName" => $user->getFirstName(),
                "lastName" => $user->getLastName(),
                "email" => $user->getEmail(),
                "password" => $user->getPassword(),
                "bio" => $user->getBio(),
                "image" => $user->getImage(),
                "token" => $user->getToken(),
            ]);
            $createdUserId = $this->db->id();

            if ($createdUserId === null) {
                $imagePath = Constants::USER_IMAGE_PATH . DIRECTORY_SEPARATOR . $user->getImage();
                $this->imageService->remove($imagePath);
            }
        } catch (\Throwable $e) {
            error($e->getMessage());
        }

        match ($createdUserId) {
            null => flashError("Failed to create user."),
            default => flashSuccess("User created successfully!"),
        };

        return (int) $createdUserId;
    }

    public function update(int $id, array $data): bool
    {
        $updated = false;

        try {
            // verify for id
            $userById = $this->db->get(UserModel::TABLE, "*", ["id" => $id]);
            if (!$userById) {
                warning("Attempt to update non-existent user");
                flashWarning("User not found.");
                return false;
            }

            $userModelKeys = array_keys($userById);
            $data = array_intersect_key($data, array_flip($userModelKeys));

            if (empty($data)) {
                error("Attempt to update user by passing empty data or non-existing fields in the users table.");
                flashError("Failed to update user.");
                return false;
            }

            // verify for email
            $email = $data["email"] ?? "";
            if (!empty($email)) {
                $userByEmail = $this->db->get(UserModel::TABLE, "*", ["email" => $email]);
                if ($email !== $userById["email"] && $userByEmail) {
                    warning("Attempt to update email with another user's email");
                    flashError("There is already a registered user with that email.");
                    return false;
                }

                if ($email === $userById["email"]) {
                    unset($data["email"]);
                }
            }

            $password = $data["password"] ?? "";
            if (empty($password)) {
                unset($data["password"]);
            }

            // verify for image
            $newImage = $data["image"] ?? null;
            if ($newImage !== null) {
                $data["image"] = $this->imageService->upload($newImage, Constants::USER_IMAGE_PATH);
            }
            $oldImage = $userById["image"];

            $result = $this->db->update(UserModel::TABLE, $data, ["id" => $id]);
            $updated = $result->rowCount() > 0;

            // remove old image
            if ($newImage !== null && !empty($oldImage) && $updated) {
                $imagePath = Constants::USER_IMAGE_PATH . DIRECTORY_SEPARATOR . $oldImage;
                $this->imageService->remove($imagePath);
            }

            if ($newImage !== null && !$updated) {
                $imagePath = Constants::USER_IMAGE_PATH . DIRECTORY_SEPARATOR . $data["image"];
                $this->imageService->remove($imagePath);
            }

            if ($updated) {
                Session::set("user", $this->db->get(UserModel::TABLE, "*", ["id" => $id]));
            }
        } catch (\Throwable $e) {
            error($e->getMessage());
        }

        match ($updated) {
            false => flashError("Failed to update user."),
            true => flashSuccess("User updated successfully!"),
        };

        return $updated;
    }

    public function remove(int $id): bool
    {
        $deleted = false;

        try {
            $user = $this->db->get(UserModel::TABLE, "*", ["id" => $id]);
            if (!$user) {
                warning("Attempt to remove non-existent user");
                flashWarning("User not found.");
                return false;
            }

            $result = $this->db->delete(UserModel::TABLE, ["id" => $id]);
            $deleted = $result->rowCount() > 0;
        } catch (\Throwable $e) {
            error($e->getMessage());
        }

        match ($deleted) {
            false => flashError("Failed to remove user."),
            true => flashSuccess("User removed successfully!"),
        };

        return $deleted;
    }
}