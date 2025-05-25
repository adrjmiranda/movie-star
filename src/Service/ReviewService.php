<?php

namespace MovieStar\Service;

use Medoo\Medoo;
use MovieStar\Core\Database;
use MovieStar\Core\Session;
use MovieStar\Model\ReviewModel;

class ReviewService
{
    private Medoo $db;

    public function __construct()
    {
        $this->db = Database::instance();
    }

    public function save(ReviewModel $review): int
    {
        if ($this->userHasAlreadyLeftAReview($review->getMovieId())) {
            flashWarning("You cannot comment more than once on the same movie post.");
            return 0;
        }

        $createdReviewId = null;

        try {
            $this->db->insert(ReviewModel::TABLE, [
                "comment" => $review->getComment(),
                "rating" => $review->getRating(),
                "movieId" => $review->getMovieId(),
                "userId" => $review->getUserId(),
            ]);
            $createdReviewId = $this->db->id();
        } catch (\Throwable $e) {
            error($e->getMessage());
        }

        match ($createdReviewId) {
            null => flashError("Failed to create review."),
            default => flashSuccess("Review created successfully!"),
        };

        return (int) $createdReviewId;
    }

    public function update(int $id, array $data): bool
    {
        $updated = false;

        try {
            // verify for id
            $reviewById = $this->db->get(ReviewModel::TABLE, "*", ["id" => $id]);
            if (!$reviewById) {
                warning("Attempt to update non-existent review");
                flashWarning("Review not found.");
                return false;
            }

            $reviewModelKeys = array_keys($reviewById);
            $data = array_intersect_key($data, array_flip($reviewModelKeys));

            if (empty($data)) {
                return false;
            }

            $result = $this->db->update(ReviewModel::TABLE, $data, ["id" => $id]);
            $updated = $result->rowCount() > 0;
        } catch (\Throwable $e) {
            error($e->getMessage());
        }

        match ($updated) {
            false => flashError("Failed to update review."),
            true => flashSuccess("Review updated successfully!"),
        };

        return $updated;
    }

    public function remove(int $id): bool
    {
        $deleted = false;

        try {
            $user = $this->db->get(ReviewModel::TABLE, "*", ["id" => $id]);
            if (!$user) {
                warning("Attempt to remove non-existent reivew");
                flashWarning("Review not found.");
                return false;
            }

            $result = $this->db->delete(ReviewModel::TABLE, ["id" => $id]);
            $deleted = $result->rowCount() > 0;

        } catch (\Throwable $e) {
            error($e->getMessage());
        }

        match ($deleted) {
            false => flashError("Failed to remove review."),
            true => flashSuccess("Review removed successfully!"),
        };

        return $deleted;
    }

    public function userHasAlreadyLeftAReview(int $movieId): bool
    {
        $userId = (int) (Session::get("user")["id"] ?? "");
        $review = $this->db->get(ReviewModel::TABLE, "*", [
            "userId" => $userId,
            "movieId" => $movieId
        ]);
        if (!$review) {
            return false;
        }

        return true;
    }
}