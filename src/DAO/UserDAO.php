<?php

namespace MovieStar\DAO;

use MovieStar\Core\Constants;
use MovieStar\Model\UserModel;
use MovieStar\Service\AuthService;
use MovieStar\Service\ImageService;
use MovieStar\Service\UserService;

class UserDAO extends AbstractDAO
{
    public function __construct(
        private AuthService $authService,
        private UserService $userService,
        private ImageService $imageService,
    ) {
        parent::__construct();
    }

    public function model(array $data): UserModel
    {
        $firstName = $data["first_name"] ?? "";
        $lastName = $data["last_name"] ?? "";
        $email = $data["email"] ?? "";
        $password = $data["password"] ?? "";
        $bio = $data["bio"] ?? "";
        $image = $data["image"] ?? null;

        $user = new UserModel();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setPassword($password);

        if (!empty($bio)) {
            $user->setBio($bio);
        }

        if ($image !== null) {
            $imageName = $this->imageService->upload($image, Constants::USER_IMAGE_PATH);
            $user->setImage($imageName);
        }

        $user->setToken($this->authService->token());

        return $user;
    }

    public function create(array $data): int
    {
        try {
            $user = $this->model($data);
            return $this->userService->save($user);
        } catch (\Throwable $e) {
            error($e->getMessage());
            throw new \RuntimeException("Error creating user", 0, $e);
        }
    }

    public function findById(int $id): mixed
    {
        return $this->db->get(UserModel::TABLE, null, "*", ["id" => $id]);
    }

    public function find(): mixed
    {
        return $this->db->select(UserModel::TABLE, "*");
    }

    public function update(int $id, array $data): bool
    {
        try {
            return $this->userService->update($id, $data);
        } catch (\Throwable $e) {
            error($e->getMessage());
            throw new \RuntimeException("Error updating user", 0, $e);
        }
    }

    public function delete(int $id): bool
    {
        return $this->userService->remove($id);
    }
}