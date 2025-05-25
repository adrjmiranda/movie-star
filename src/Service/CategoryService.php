<?php

namespace MovieStar\Service;

use Medoo\Medoo;
use MovieStar\Core\Constants;
use MovieStar\Core\Database;
use MovieStar\Model\CategoryModel;

class CategoryService
{
    private Medoo $db;

    public function __construct(private ImageService $imageService)
    {
        $this->db = Database::instance();
    }

    public function save(CategoryModel $category): int
    {
        $createdCategoryId = null;

        try {
            $categoryByName = $this->db->get(CategoryModel::TABLE, "*", ["name" => $category->getName()]);

            if ($categoryByName) {
                warning("Attempt to insert category with repeated name into database.");
                return 0;
            }

            $this->db->insert(CategoryModel::TABLE, [
                "name" => $category->getName(),
                "image" => $category->getImage(),
            ]);
            $createdCategoryId = (int) $this->db->id();

            if ($createdCategoryId === null) {
                $imagePath = Constants::CATEGORY_IMAGE_PATH . DIRECTORY_SEPARATOR . $category->getImage();
                $this->imageService->remove($imagePath);
            }
        } catch (\Throwable $e) {
            error($e->getMessage());
        }

        match ($createdCategoryId) {
            null => flashError("Failed to create category."),
            default => flashSuccess("Category created successfully!"),
        };

        return (int) $createdCategoryId;
    }

    public function update(int $id, array $data): bool
    {
        $updated = false;

        try {
            // verify for id
            $categoryById = $this->db->get(CategoryModel::TABLE, "*", ["id" => $id]);
            if (!$categoryById) {
                warning("Attempt to update non-existent category");
                return false;
            }

            $categoryModelKeys = array_keys($categoryById);
            $data = array_intersect_key($data, array_flip($categoryModelKeys));

            if (empty($data)) {
                return false;
            }

            // verify for image
            $newImage = $data["image"] ?? null;
            if ($newImage !== null) {
                $data["image"] = $this->imageService->upload($newImage, Constants::CATEGORY_IMAGE_PATH);
            }
            $oldImage = $categoryById["image"];

            $result = $this->db->update(CategoryModel::TABLE, $data, ["id" => $id]);
            $updated = $result->rowCount() > 0;

            // remove old image
            if ($newImage !== null && !empty($oldImage) && $updated) {
                $imagePath = Constants::CATEGORY_IMAGE_PATH . DIRECTORY_SEPARATOR . $oldImage;
                $this->imageService->remove($imagePath);
            }

            if ($newImage !== null && !$updated) {
                $imagePath = Constants::CATEGORY_IMAGE_PATH . DIRECTORY_SEPARATOR . $data["image"];
                $this->imageService->remove($imagePath);
            }
        } catch (\Throwable $e) {
            error($e->getMessage());
        }

        match ($updated) {
            false => flashError("Failed to update category."),
            true => flashSuccess("Category updated successfully!"),
        };

        return $updated;
    }

    public function remove(int $id): bool
    {
        $deleted = false;

        try {
            $user = $this->db->get(CategoryModel::TABLE, "*", ["id" => $id]);
            if (!$user) {
                warning("Attempt to remove non-existent user");
                return false;
            }

            $result = $this->db->delete(CategoryModel::TABLE, ["id" => $id]);
            $deleted = $result->rowCount() > 0;
        } catch (\Throwable $e) {
            error($e->getMessage());
        }

        match ($deleted) {
            false => flashError("Failed to remove category."),
            true => flashSuccess("Category removed successfully!"),
        };

        return $deleted;
    }
}