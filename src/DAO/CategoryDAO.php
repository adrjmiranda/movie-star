<?php

namespace MovieStar\DAO;

use MovieStar\Core\Constants;
use MovieStar\Model\CategoryModel;
use MovieStar\Service\CategoryService;
use MovieStar\Service\ImageService;

class CategoryDAO extends AbstractDAO
{
    public function __construct(
        private CategoryService $categoryService,
        private ImageService $imageService,
    ) {
        parent::__construct();
    }

    private function model(array $data): CategoryModel
    {
        $name = $data["name"] ?? "";
        $image = $data["image"] ?? null;

        $category = new CategoryModel();
        $category->setName($name);

        if ($image !== null) {
            $imageName = $this->imageService->upload($image, Constants::CATEGORY_IMAGE_PATH);
            $category->setImage($imageName);
        }

        return $category;
    }

    public function create(array $data): int
    {
        try {
            $movie = $this->model($data);
            return $this->categoryService->save($movie);
        } catch (\Throwable $e) {
            error($e->getMessage());
            throw new \RuntimeException("Error creating category", 0, $e);
        }
    }

    public function findById(int $id): mixed
    {
        return $this->db->get(CategoryModel::TABLE, null, "*", ["id" => $id]);
    }

    public function find(): mixed
    {
        return $this->db->select(CategoryModel::TABLE, "*");
    }

    public function update(int $id, array $data): bool
    {
        try {
            return $this->categoryService->update($id, $data);
        } catch (\Throwable $e) {
            error($e->getMessage());
            throw new \RuntimeException("Error updating category", 0, $e);
        }
    }

    public function delete(int $id): bool
    {
        return $this->categoryService->remove($id);
    }
}