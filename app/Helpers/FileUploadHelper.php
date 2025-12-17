<?php

namespace App\Helpers;

class FileUploadHelper
{
    /**
     * Get the correct public path based on environment
     * For local: returns public_path()
     * For cPanel: returns path to public_html
     */
    public static function getPublicPath($subPath = '')
    {
        // Check if we're in production (cPanel environment)
        if (config('app.env') === 'production' && env('PUBLIC_PATH')) {
            // For cPanel: use public_html path
            $basePath = base_path(env('PUBLIC_PATH'));
        } else {
            // For local: use standard public path
            $basePath = public_path();
        }
        
        return $subPath ? $basePath . '/' . ltrim($subPath, '/') : $basePath;
    }

    /**
     * Get the correct public URL path
     * For local: returns /folder/file.jpg
     * For cPanel: returns /folder/file.jpg (same, as public_html is web root)
     */
    public static function getPublicUrl($path)
    {
        // Remove public_path or any base path prefix
        $path = str_replace(public_path(), '', $path);
        $path = str_replace('\\', '/', $path);
        return '/' . ltrim($path, '/');
    }

    /**
     * Upload file to correct location
     */
    public static function uploadFile($file, $folder, $fileName = null)
    {
        if (!$fileName) {
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        }
        
        $destinationPath = self::getPublicPath($folder);
        
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        
        $file->move($destinationPath, $fileName);
        
        return $folder . '/' . $fileName;
    }

    /**
     * Sanitize institution name for folder naming
     */
    public static function sanitizeInstitutionName($name)
    {
        $sanitized = preg_replace('/[^A-Za-z0-9\s]/', '', $name);
        $sanitized = preg_replace('/\s+/', '_', trim($sanitized));
        return $sanitized;
    }
}



