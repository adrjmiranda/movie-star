<?php

namespace MovieStar\Service;

use DateTime;

class ImageService
{
    public const ALLOWED_TYPES = [
        "image/jpg",
        "image/jpeg",
        "image/png",
    ];
    public const HASH_SIZE = 72;

    public function upload(array $file, string $uploadDir): string
    {
        if ($file["error"] !== UPLOAD_ERR_OK) {
            FormMessageService::set("image", "Error uploading image.");
            throw new \RuntimeException("Error uploading image.");
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file["tmp_name"]);
        finfo_close($finfo);

        if (!in_array($mimeType, self::ALLOWED_TYPES)) {
            FormMessageService::set("image", "Image type not allowed. Enabled types: " . implode(", ", array_map(fn(string $type): string => explode("/", $type)[1], self::ALLOWED_TYPES)));
            throw new \InvalidArgumentException("Image type not allowed.");
        }

        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $hash = bin2hex(random_bytes(self::HASH_SIZE));
        $date = (new DateTime())->format("Ymd");

        $filename = "{$date}-{$hash}.{$ext}";
        $imageDir = fullPath($uploadDir);
        $imageFileName = $imageDir . DIRECTORY_SEPARATOR . $filename;
        if (!is_dir($imageDir)) {
            mkdir($imageDir, isDev() ? 0777 : 0755, true);
        }

        if (!move_uploaded_file($file["tmp_name"], $imageFileName)) {
            FormMessageService::set("image", "Error uploading image.");
            throw new \RuntimeException("Failed to move file.");
        }

        return $filename;
    }

    public function remove(string $imagePath): bool
    {
        $imagePath = __DIR__ . "/../../" . $imagePath;
        if (file_exists($imagePath)) {
            return unlink($imagePath);
        }

        return false;
    }
}