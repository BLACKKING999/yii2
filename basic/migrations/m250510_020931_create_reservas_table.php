<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reservas}}`.
 */
class m250510_020931_create_reservas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reservas}}', [
            'id_reserva' => $this->primaryKey(),
            'id_usuario' => $this->integer()->notNull(),
            'id_libro' => $this->integer()->notNull(),
            'fecha_reserva' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'activa' => $this->boolean()->defaultValue(true),
            'notificado' => $this->boolean()->defaultValue(false),
            'fecha_notificacion' => $this->dateTime(),
            'prioridad' => $this->integer()->defaultValue(0),
            'comentarios' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        
        // Agregar índices para mejorar rendimiento de consultas
        $this->createIndex('idx-reservas-usuario', '{{%reservas}}', 'id_usuario');
        $this->createIndex('idx-reservas-libro', '{{%reservas}}', 'id_libro');
        $this->createIndex('idx-reservas-activa', '{{%reservas}}', 'activa');
        
        // Agregar claves foráneas
        $this->addForeignKey('fk-reservas-usuario', '{{%reservas}}', 'id_usuario', '{{%usuarios}}', 'id_usuario', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-reservas-libro', '{{%reservas}}', 'id_libro', '{{%libros}}', 'id_libro', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%reservas}}');
    }
}
