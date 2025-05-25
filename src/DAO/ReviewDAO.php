<?php

namespace MovieStar\DAO;

use MovieStar\Core\Session;
use MovieStar\Model\ReviewModel;
use MovieStar\Service\ImageService;
use MovieStar\Service\ReviewService;

class ReviewDAO extends AbstractDAO
{
    public function __construct(
        private ReviewService $reviewService,
        private ImageService $imageService,
    ) {
        parent::__construct();
    }

    private function model(array $data): ReviewModel
    {
        $comment = $data["comment"] ?? "";
        $rating = (int) ($data["rating"] ?? "");
        $movieId = (int) ($data["movie_id"] ?? "");
        $userId = (int) (Session::get("user")["id"] ?? "");

        $review = new ReviewModel();
        $review->setComment($comment);
        $review->setRating($rating);
        $review->setMovieId($movieId);
        $review->setUserId($userId);

        return $review;
    }

    public function create(array $data): int
    {
        try {
            $review = $this->model($data);
            return $this->reviewService->save($review);
        } catch (\Throwable $e) {
            error($e->getMessage());
            throw new \RuntimeException("Error creating review", 0, $e);
        }
    }

    public function findById(int $id): mixed
    {
        return $this->db->get(ReviewModel::TABLE, null, "*", ["id" => $id]);
    }

    public function find(): mixed
    {
        return $this->db->select(ReviewModel::TABLE, "*");
    }

    public function update(int $id, array $data): bool
    {
        try {
            return $this->reviewService->update($id, $data);
        } catch (\Throwable $e) {
            error($e->getMessage());
            throw new \RuntimeException("Error updating review", 0, $e);
        }
    }

    public function delete(int $id): bool
    {
        return $this->reviewService->remove($id);
    }
}