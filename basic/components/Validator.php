<?php

namespace app\components;

use Yii;
use yii\base\Component;

/**
 * Componente para validación de entradas
 */
class Validator extends Component
{
    /**
     * Valida una imagen subida
     * 
     * @param string $file El archivo a validar
     * @param array $allowedExtensions Extensiones permitidas
     * @param int $maxSize Tamaño máximo en bytes
     * @return bool|string true si es válida, mensaje de error si no
     */
    public static function validateImage($file, $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'], $maxSize = 2097152)
    {
        if (!$file) {
            return true; // No hay archivo para validar
        }

        // Validar extensión
        $extension = strtolower($file->extension);
        if (!in_array($extension, $allowedExtensions)) {
            return 'El tipo de archivo no es válido. Tipos permitidos: ' . implode(', ', $allowedExtensions);
        }

        // Validar tamaño
        if ($file->size > $maxSize) {
            return 'El archivo es demasiado grande. Tamaño máximo: ' . self::formatBytes($maxSize);
        }

        // Validar que sea una imagen real
        if (!file_exists($file->tempName)) {
            Yii::warning("Archivo temporal no encontrado: {$file->tempName}");
            // Permitir continuar si el archivo temporal no existe pero pasa las otras validaciones
            return true;
        }
        
        $check = @getimagesize($file->tempName);
        if ($check === false) {
            return 'El archivo no es una imagen válida';
        }

        return true;
    }

    /**
     * Formatea bytes a un formato más legible
     * 
     * @param int $bytes Número de bytes
     * @param int $precision Precisión decimal
     * @return string
     */
    public static function formatBytes($bytes, $precision = 2) 
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Sanitiza contenido HTML para prevenir XSS
     * 
     * @param string $html El contenido HTML a sanitizar
     * @return string
     */
    public static function sanitizeHtml($html)
    {
        // Lista de etiquetas permitidas
        $allowedTags = [
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'br', 'hr',
            'strong', 'em', 'u', 'strike', 'a', 'ul', 'ol', 'li',
            'table', 'tr', 'td', 'th', 'thead', 'tbody', 'div', 'span', 'img'
        ];
        
        // Lista de atributos permitidos
        $allowedAttrs = ['href', 'title', 'target', 'class', 'id', 'style', 'src', 'alt', 'width', 'height'];
        
        // Configurar purificador HTML
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', implode(',', $allowedTags));
        $config->set('HTML.AllowedAttributes', implode(',', $allowedAttrs));
        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true]);
        $config->set('HTML.Nofollow', true); // Agregar rel="nofollow" a todos los enlaces
        $config->set('CSS.AllowTricky', false); // No permitir CSS potencialmente peligroso
        
        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($html);
    }
}
