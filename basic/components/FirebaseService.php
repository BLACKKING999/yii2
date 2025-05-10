<?php

namespace app\components;

use Yii;
use yii\base\Component;

/**
 * Servicio simple para autenticación con Google
 * Implementación minimalista usando solo bibliotecas estándar de Yii2
 */
class FirebaseService extends Component
{
    // Credenciales de Google Cloud y Firebase
    public $clientId = '238597549460-6lp1570kgkgo4kc3amot045v7jk016tg.apps.googleusercontent.com'; // Completa este ID con el resto de tu client_id
    public $apiKey = 'AIzaSyAby_NJ7qzsZAlAgM0ZSYAlCaAD63zKQoQ';
    public $projectId ='apis-practicas';
    
    // Necesario para el intercambio de token
    public $clientSecret = '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'; // Reemplaza esto con tu client secret real
    
    // URL de redirección
    public $redirectUri = 'http://localhost:8080/auth/callback';
    
    /**
     * Inicializa el componente
     */
    public function init()
    {
        parent::init();
        Yii::info('Firebase Service inicializado. URL de redirección: ' . $this->redirectUri);
    }
    
    /**
     * Genera la URL para iniciar el proceso de autenticación con Google
     * @return string URL para iniciar autenticación
     */
    public function getAuthUrl()
    {
        // Estos son los parámetros estándar para OAuth 2.0 con Google
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'online',
            'prompt' => 'select_account'
        ];
        
        return 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($params);
    }
    
    /**
     * Obtiene un token de acceso usando el código de autorización
     * @param string $code Código de autorización
     * @return array|null Datos del token o null si hay error
     */
    public function getAccessToken($code)
    {
        if (empty($this->clientSecret)) {
            Yii::error('No se ha configurado GOOGLE_CLIENT_SECRET, imposible obtener token');
            return null;
        }
        
        try {
            // Inicializar cURL
            $ch = curl_init('https://oauth2.googleapis.com/token');
            
            // Configurar opciones de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'code' => $code,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUri,
                'grant_type' => 'authorization_code'
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
            
            // Ejecutar cURL y obtener respuesta
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpcode >= 200 && $httpcode < 300) {
                return json_decode($response, true);
            }
            
            Yii::error('Error en respuesta de token: ' . $response);
        } catch (\Exception $e) {
            Yii::error('Error obteniendo token de acceso: ' . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Obtiene información del usuario con el token de acceso
     * @param string $accessToken Token de acceso
     * @return array|null Datos del usuario o null si hay error
     */
    public function getUserInfo($accessToken)
    {
        try {
            // Inicializar cURL
            $ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
            
            // Configurar opciones de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken
            ]);
            
            // Ejecutar cURL y obtener respuesta
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpcode >= 200 && $httpcode < 300) {
                $userData = json_decode($response, true);
                return [
                    'id' => $userData['id'] ?? '',
                    'email' => $userData['email'] ?? '',
                    'name' => $userData['name'] ?? '',
                    'picture' => $userData['picture'] ?? ''
                ];
            }
            
            Yii::error('Error en respuesta de userinfo: ' . $response);
        } catch (\Exception $e) {
            Yii::error('Error obteniendo información de usuario: ' . $e->getMessage());
        }
        
        return null;
    }
    

}
