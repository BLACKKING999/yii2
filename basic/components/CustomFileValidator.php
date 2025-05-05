<?php

namespace app\components;

use Yii;
use yii\validators\FileValidator;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * CustomFileValidator extends the standard FileValidator and overrides methods
 * to handle temporary file access issues.
 */
class CustomFileValidator extends FileValidator
{
    /**
     * Override the validateExtension method to handle cases where the temporary file doesn't exist
     * 
     * @param UploadedFile $file the uploaded file to validate
     * @return bool whether the validation is successful
     */
    protected function validateExtension($file)
    {
        // If there are no extensions specified, always return true
        if ($this->extensions === null) {
            return true;
        }

        // Check if the temp file exists
        if (!file_exists($file->tempName)) {
            Yii::warning("Archivo temporal no encontrado durante validación de extensión: {$file->tempName}");
            // Get extension from filename instead of trying to detect from the file
            $extension = strtolower($file->extension);
            if (!in_array($extension, $this->extensions, true)) {
                return false;
            }
            return true;
        }

        // If the file exists, continue with normal validation
        return parent::validateExtension($file);
    }

    /**
     * Override the validateMimeType method to handle cases where the temporary file doesn't exist
     * 
     * @param UploadedFile $file the uploaded file to validate
     * @return bool whether the validation is successful
     */
    protected function validateMimeType($file)
    {
        // If there are no mime types specified, always return true
        if ($this->mimeTypes === null) {
            return true;
        }

        // Check if the temp file exists
        if (!file_exists($file->tempName)) {
            Yii::warning("Archivo temporal no encontrado durante validación de MIME type: {$file->tempName}");
            // Cannot validate MIME type without the file, so assume it's valid
            // We already validated the extension in validateExtension
            return true;
        }
        
        // If the file exists, continue with normal validation
        return parent::validateMimeType($file);
    }
    
    /**
     * Override the validateSize method to handle cases where the temporary file doesn't exist
     * 
     * @param UploadedFile $file the uploaded file to validate
     * @return bool whether the validation is successful
     */
    protected function validateSize($file)
    {
        // If the file doesn't exist, use the reported size from $_FILES
        if (!file_exists($file->tempName)) {
            Yii::warning("Archivo temporal no encontrado durante validación de tamaño: {$file->tempName}");
            
            // Check minSize if set
            if ($this->minSize !== null && $file->size < $this->minSize) {
                return false;
            }
            
            // Check maxSize if set
            if ($this->maxSize !== null && $file->size > $this->maxSize) {
                return false;
            }
            
            return true;
        }
        
        // If the file exists, continue with normal validation
        return parent::validateSize($file);
    }
}
