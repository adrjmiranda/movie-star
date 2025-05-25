<?php

namespace MovieStar\Service;

use Medoo\Medoo;
use MovieStar\Core\Constants;
use MovieStar\Core\Database;
use MovieStar\Model\MovieModel;

class MovieService
{
    private Medoo $db;

    public function __construct(private ImageService $imageService)
    {
        $this->db = Database::instance();
    }

    public function save(MovieModel $movie): int
    {
        $createdMovieId = null;

        try {
            $this->db->insert(MovieModel::TABLE, [
                "title" => $movie->getTtitle(),
                "description" => $movie->getDescription(),
                "image" => $movie->getImage(),
                "trailer" => $movie->getTrailer(),
                "duration" => $movie->getDuration(),
                "categoryId" => $movie->getCategoryId(),
                "userId" => $movie->getUserId(),
            ]);
            $createdMovieId = (int) $this->db->id();

            if ($createdMovieId === null) {
                $imagePath = Constants::MOVIE_IMAGE_PATH . DIRECTORY_SEPARATOR . $movie->getImage();
                $this->imageService->remove($imagePath);
            }
        } catch (\Throwable $e) {
            error($e->getMessage());
        }

        match ($createdMovieId) {
            null => flashError("Failed to create movie."),
            default => flashSuccess("Movie created successfully!"),
        };

        return (int) $createdMovieId;
    }

    public function update(int $id, array $data): bool
    {
        $updated = false;

        try {
            // verify for id
            $movieById = $this->db->get(MovieModel::TABLE, "*", ["id" => $id]);
            if (!$movieById) {
                warning("Attempt to update non-existent movie");
                return false;
            }

            $movieModelKeys = array_keys($movieById);
            $data = array_intersect_key($data, array_flip($movieModelKeys));

            if (empty($data)) {
                return false;
            }

            // verify for image
            $newImage = $data["image"] ?? null;
            if ($newImage !== null) {
                $data["image"] = $this->imageService->upload($newImage, Constants::MOVIE_IMAGE_PATH);
            }
            $oldImage = $movieById["image"];

            $result = $this->db->update(MovieModel::TABLE, $data, ["id" => $id]);
            $updated = $result->rowCount() > 0;

            // remove old image
            if ($newImage !== null && !empty($oldImage) && $updated) {
                $imagePath = Constants::MOVIE_IMAGE_PATH . DIRECTORY_SEPARATOR . $oldImage;
                $this->imageService->remove($imagePath);
            }

            if ($newImage !== null && !$updated) {
                $imagePath = Constants::MOVIE_IMAGE_PATH . DIRECTORY_SEPARATOR . $data["image"];
                $this->imageService->remove($imagePath);
            }
        } catch (\Throwable $e) {
            dd($this->db->log());
            error($e->getMessage());
        }

        match ($updated) {
            false => flashError("Failed to update movie."),
            true => flashSuccess("Movie updated successfully!"),
        };

        return $updated;
    }

    public function remove(int $id): bool
    {
        $deleted = false;

        try {
            $user = $this->db->get(MovieModel::TABLE, "*", ["id" => $id]);
            if (!$user) {
                warning("Attempt to remove non-existent user");
                return false;
            }

            $result = $this->db->delete(MovieModel::TABLE, ["id" => $id]);
            $deleted = $result->rowCount() > 0;
        } catch (\Throwable $e) {
            error($e->getMessage());
        }

        match ($deleted) {
            false => flashError("Failed to remove movie."),
            true => flashSuccess("Movie removed successfully!"),
        };

        return false;
    }
}