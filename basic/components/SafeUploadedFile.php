<?php

namespace app\components;

use Yii;
use yii\web\UploadedFile;

/**
 * SafeUploadedFile extends UploadedFile to provide safer handling of temporary files
 */
class SafeUploadedFile extends UploadedFile
{
    /**
     * @var string|null cached mimetype
     */
    private $_mimeType = null;
    
    /**
     * Override getInstance to return our SafeUploadedFile instance
     */
    public static function getInstance($model, $attribute)
    {
        $file = parent::getInstance($model, $attribute);
        if ($file instanceof UploadedFile) {
            // Convert standard UploadedFile to SafeUploadedFile
            $safeFile = new self();
            foreach (get_object_vars($file) as $name => $value) {
                $safeFile->$name = $value;
            }
            
            // Cache MIME type from $_FILES if available
            if (isset($_FILES[$model->formName()]['type'][$attribute])) {
                $safeFile->_mimeType = $_FILES[$model->formName()]['type'][$attribute];
            }
            
            return $safeFile;
        }
        return $file;
    }
    
    /**
     * Override saveAs to add additional checks
     */
    public function saveAs($file, $deleteTempFile = true)
    {
        if (!$this->tempName || !file_exists($this->tempName)) {
            Yii::warning("El archivo temporal no existe: {$this->tempName}");
            return false;
        }
        
        return parent::saveAs($file, $deleteTempFile);
    }
    
    /**
     * Get MIME type without relying on the temporary file
     * This will be used by our custom file validator
     */
    public function getMimeType()
    {
        if ($this->_mimeType !== null) {
            return $this->_mimeType;
        }
        
        // If the temp file exists, try to get the MIME type
        if ($this->tempName && file_exists($this->tempName)) {
            try {
                $info = @finfo_open(FILEINFO_MIME_TYPE);
                if ($info) {
                    $result = @finfo_file($info, $this->tempName);
                    finfo_close($info);
                    if ($result !== false) {
                        $this->_mimeType = $result;
                        return $result;
                    }
                }
            } catch (\Exception $e) {
                Yii::warning("Error al determinar el MIME type: " . $e->getMessage());
            }
        }
        
        // Fallback: get extension from filename and map to common MIME types
        $ext = strtolower(pathinfo($this->name, PATHINFO_EXTENSION));
        $map = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'txt' => 'text/plain',
            'csv' => 'text/csv',
        ];
        
        return $map[$ext] ?? 'application/octet-stream';
    }
}
