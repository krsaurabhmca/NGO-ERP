<?php
namespace App\Helpers;

class UploadHelper {
    
    /**
     * General file processing: resizes/compresses images, normally uploads other files.
     */
    public static function processFile($fileArray, $uploadDir, $maxSizeKb = 300, $maxWidth = 1200) {
        if (!isset($fileArray['tmp_name']) || empty($fileArray['tmp_name'])) {
            return false;
        }

        $ext = strtolower(pathinfo($fileArray['name'], PATHINFO_EXTENSION));
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $imageExtensions)) {
            return self::processImage($fileArray, $uploadDir, $maxSizeKb, $maxWidth);
        }

        // Standard upload for non-images (PDFs, DOCs, etc)
        if ($fileArray['error'] !== UPLOAD_ERR_OK) return false;
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($fileArray['name'], PATHINFO_FILENAME));
        $newFilename = $baseName . '_' . time() . '_' . uniqid() . '.' . $ext;
        $destination = rtrim($uploadDir, '/') . '/' . $newFilename;
        
        if (move_uploaded_file($fileArray['tmp_name'], $destination)) {
            return $newFilename;
        }
        return false;
    }

    /**
     * Process, resize, compress and convert an uploaded image to WebP format.
     * Ensure the final file size is less than $maxSizeKb.
     *
     * @param array $fileArray The $_FILES['input_name'] array.
     * @param string $uploadDir The destination directory path (e.g. 'uploads/news/')
     * @param int $maxSizeKb The maximum file size in KB (default 300)
     * @param int $maxWidth The maximum width to resize to if larger (default 1200)
     * @return string|false The final uploaded filename (e.g., 'image_123.webp') on success, or false on failure.
     */
    public static function processImage($fileArray, $uploadDir, $maxSizeKb = 300, $maxWidth = 1200) {
        if (!isset($fileArray['tmp_name']) || empty($fileArray['tmp_name'])) {
            return false;
        }

        $tmpName = $fileArray['tmp_name'];
        $originalName = $fileArray['name'];
        $fileSize = $fileArray['size'];
        $error = $fileArray['error'];

        if ($error !== UPLOAD_ERR_OK) {
            return false;
        }

        // Verify it is an image
        $imageInfo = @getimagesize($tmpName);
        if ($imageInfo === false) {
            return false; // Not a valid image
        }

        list($width, $height, $type) = $imageInfo;

        // Load image based on type
        $image = null;
        switch ($type) {
            case IMAGETYPE_JPEG:
                $image = @imagecreatefromjpeg($tmpName);
                break;
            case IMAGETYPE_PNG:
                $image = @imagecreatefrompng($tmpName);
                break;
            case IMAGETYPE_WEBP:
                $image = @imagecreatefromwebp($tmpName);
                break;
            case IMAGETYPE_GIF:
                $image = @imagecreatefromgif($tmpName);
                break;
            default:
                return false; // Unsupported type
        }

        if (!$image) {
            return false;
        }

        // Calculate new dimensions if resizing is needed
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int)(($height / $width) * $newWidth);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        // Create new image resource for resized/processed image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG and GIF
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
        }

        // Copy and resize
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Ensure directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename with .webp extension
        $pathInfo = pathinfo($originalName);
        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $pathInfo['filename']);
        $newFilename = $baseName . '_' . time() . '_' . uniqid() . '.webp';
        $destination = rtrim($uploadDir, '/') . '/' . $newFilename;

        // Compress and save as WebP
        $quality = 90; // Start with 90 quality
        $maxSizeBytes = $maxSizeKb * 1024;
        $saved = false;

        // Loop to find right quality if it exceeds max size
        while ($quality >= 10) {
            // Save to buffer to check size
            ob_start();
            imagewebp($newImage, null, $quality);
            $imageData = ob_get_clean();

            if (strlen($imageData) <= $maxSizeBytes) {
                // Save to file
                $saved = file_put_contents($destination, $imageData) !== false;
                break;
            }
            $quality -= 10;
        }

        // Cleanup memory
        imagedestroy($image);
        imagedestroy($newImage);

        return $saved ? $newFilename : false;
    }
}
