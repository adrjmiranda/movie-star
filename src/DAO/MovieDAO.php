<?php

namespace MovieStar\DAO;

use MovieStar\Core\Constants;
use MovieStar\Core\Session;
use MovieStar\Model\CategoryModel;
use MovieStar\Model\MovieModel;
use MovieStar\Model\ReviewModel;
use MovieStar\Model\UserModel;
use MovieStar\Service\ImageService;
use MovieStar\Service\MovieService;

class MovieDAO extends AbstractDAO
{
    public function __construct(
        private MovieService $movieService,
        private ImageService $imageService,
    ) {
        parent::__construct();
    }

    private function model(array $data): MovieModel
    {
        $title = $data["title"] ?? "";
        $description = $data["description"] ?? "";
        $trailer = $data["trailer"] ?? "";
        $duration = (int) ($data["duration"] ?? "");
        $categoryId = (int) ($data["category_id"] ?? "");
        $userId = (int) (Session::get("user")["id"] ?? "");
        $image = $data["image"] ?? null;

        $movie = new MovieModel();
        $movie->setTitle($title);
        $movie->setCategoryId($categoryId);
        $movie->setUserId($userId);

        if (!empty($description)) {
            $movie->setDescription($description);
        }

        if (!empty($trailer)) {
            $movie->setTrailer($trailer);
        }

        if ($duration > 0) {
            $movie->setDuration($duration);
        }

        if ($image !== null) {
            $imageName = $this->imageService->upload($image, Constants::MOVIE_IMAGE_PATH);
            $movie->setImage($imageName);
        }

        return $movie;
    }

    public function create(array $data): int
    {
        try {
            $movie = $this->model($data);
            return $this->movieService->save($movie);
        } catch (\Throwable $e) {
            error($e->getMessage());
            throw new \RuntimeException("Error creating movie", 0, $e);
        }
    }

    public function findById(int $id): mixed
    {
        $movie = $this->db->get(MovieModel::TABLE, [
            "[><]" . CategoryModel::TABLE => ["categoryId" => "id"],
            "[><]" . UserModel::TABLE => ["userId" => "id"],
        ], [
            MovieModel::TABLE . ".id",
            MovieModel::TABLE . ".title",
            MovieModel::TABLE . ".image",
            MovieModel::TABLE . ".trailer",
            MovieModel::TABLE . ".description",
            MovieModel::TABLE . ".duration",
            MovieModel::TABLE . ".userId",
            MovieModel::TABLE . ".categoryId",
            CategoryModel::TABLE . ".name (category)",
            UserModel::TABLE . ".firstName (userFirstName)",
            UserModel::TABLE . ".lastName (userLastName)",
            UserModel::TABLE . ".image (userImage)",
        ], [
            MovieModel::TABLE . ".id" => $id
        ]);
        $reviews = $this->db->select(ReviewModel::TABLE, [
            "[><]" . UserModel::TABLE => ["userId" => "id"],
        ], [
            ReviewModel::TABLE . ".comment",
            ReviewModel::TABLE . ".rating",
            ReviewModel::TABLE . ".createdAt",
            UserModel::TABLE . ".firstName (userFirstName)",
            UserModel::TABLE . ".lastName (userLastName)",
            UserModel::TABLE . ".image (userImage)",
            UserModel::TABLE . ".id (userId)",
        ], [
            "movieId" => $id,
            "ORDER" => [
                ReviewModel::TABLE . ".createdAt" => "DESC"
            ]
        ]);
        $rating = $this->db->avg(ReviewModel::TABLE, "rating", ["movieId" => $id]);

        if (!$movie) {
            return [];
        }

        $movie["reviews"] = array_values($reviews) ?? [];
        $movie["rating"] = (int) ($rating ?? "");

        return $movie;
    }

    private function groupWithRating(array $data): array
    {
        $groups = [];

        foreach ($data as $key => $item) {
            $id = $item["id"];
            $rating = $item["rating"];

            if (!isset($groups[$id])) {
                $groups[$id] = [
                    ...$item,
                    "ratings" => [$rating]
                ];
            } else {
                $groups[$id]["ratings"][] = $rating;
            }
        }

        $result = array_map(function ($item): array {
            $rating = array_sum($item["ratings"]) / count($item["ratings"]);
            unset($item["ratings"]);
            return [...$item, "rating" => (int) floor($rating)];
        }, array_values($groups));

        return $result;
    }

    public function find(): mixed
    {
        $data = $this->db->select(MovieModel::TABLE, [
            "[><]" . ReviewModel::TABLE => ["id" => "movieId"]
        ], [
            MovieModel::TABLE . ".id",
            MovieModel::TABLE . ".title",
            MovieModel::TABLE . ".image",
            ReviewModel::TABLE . ".rating",
        ], [
            "ORDER" => [
                MovieModel::TABLE . ".createdAt" => "DESC"
            ]
        ]) ?? [];

        $result = $this->groupWithRating($data);
        return $result;
    }

    public function findByUser(): mixed
    {
        $userId = (int) (Session::get("user")["id"] ?? "");
        return $this->db->select(MovieModel::TABLE, [
            "[><]" . CategoryModel::TABLE => ["categoryId" => "id"]
        ], [
            MovieModel::TABLE . ".id",
            MovieModel::TABLE . ".title",
            MovieModel::TABLE . ".image",
            MovieModel::TABLE . ".duration",
            CategoryModel::TABLE . ".name (category)"
        ], [
            "userId" => $userId,
            "ORDER" => [
                MovieModel::TABLE . ".createdAt" => "DESC"
            ]
        ]);
    }

    public function findByTitle(string $title): array
    {
        $data = $this->db->select(MovieModel::TABLE, [
            "[><]" . ReviewModel::TABLE => ["id" => "movieId"]
        ], [
            MovieModel::TABLE . ".id",
            MovieModel::TABLE . ".title",
            MovieModel::TABLE . ".image",
            ReviewModel::TABLE . ".rating",
        ], [
            MovieModel::TABLE . ".title[~]" => $title,
            "ORDER" => [
                MovieModel::TABLE . ".createdAt" => "DESC"
            ]
        ]) ?? [];

        $result = $this->groupWithRating($data);
        return $result;
    }

    public function update(int $id, array $data): bool
    {
        try {
            return $this->movieService->update($id, $data);
        } catch (\Throwable $e) {
            error($e->getMessage());
            throw new \RuntimeException("Error updating movie", 0, $e);
        }
    }

    public function delete(int $id): bool
    {
        return $this->movieService->remove($id);
    }
}