<?php

namespace app\components;

use yii\base\Component;

class Env extends Component
{
    public function init()
    {
        parent::init();
        $this->loadEnv();
    }

    protected function loadEnv()
    {
        $envFile = dirname(__DIR__) . '/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                // Ignorar comentarios
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }

                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                // Quitar comillas si existen
                if (strpos($value, '"') === 0 && substr($value, -2) === '"') {
                    $value = substr($value, 1, -1);
                } elseif (strpos($value, "'") === 0 && substr($value, -1) === "'") {
                    $value = substr($value, 1, -1);
                }
                
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }

    public static function get($key, $default = null)
    {
        return getenv($key) ?: $default;
    }
}
