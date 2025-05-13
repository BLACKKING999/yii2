<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%usuarios_profesor}}`.
 */
class m240512_235500_create_usuarios_profesor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%usuarios_profesor}}', [
            'id_profesor' => $this->primaryKey(),
            'id_usuario' => $this->integer()->notNull(),
            'id_usuario_biblioteca' => $this->integer()->notNull(),
            'departamento' => $this->string(100)->notNull(),
            'especialidad' => $this->string(100)->notNull(),
            'tipo_contrato' => $this->string(50)->notNull(),
            'fecha_contratacion' => $this->date(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // Agregar índices
        $this->createIndex(
            'idx-usuarios_profesor-id_usuario',
            '{{%usuarios_profesor}}',
            'id_usuario'
        );

        $this->createIndex(
            'idx-usuarios_profesor-id_usuario_biblioteca',
            '{{%usuarios_profesor}}',
            'id_usuario_biblioteca',
            true
        );

        // Agregar claves foráneas
        $this->addForeignKey(
            'fk-usuarios_profesor-id_usuario',
            '{{%usuarios_profesor}}',
            'id_usuario',
            '{{%usuarios}}',
            'id_usuario',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-usuarios_profesor-id_usuario_biblioteca',
            '{{%usuarios_profesor}}',
            'id_usuario_biblioteca',
            '{{%usuarios_biblioteca}}',
            'id_usuario_biblioteca',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Eliminar claves foráneas
        $this->dropForeignKey(
            'fk-usuarios_profesor-id_usuario',
            '{{%usuarios_profesor}}'
        );

        $this->dropForeignKey(
            'fk-usuarios_profesor-id_usuario_biblioteca',
            '{{%usuarios_profesor}}'
        );

        // Eliminar índices
        $this->dropIndex(
            'idx-usuarios_profesor-id_usuario',
            '{{%usuarios_profesor}}'
        );

        $this->dropIndex(
            'idx-usuarios_profesor-id_usuario_biblioteca',
            '{{%usuarios_profesor}}'
        );

        // Eliminar tabla
        $this->dropTable('{{%usuarios_profesor}}');
    }
} 