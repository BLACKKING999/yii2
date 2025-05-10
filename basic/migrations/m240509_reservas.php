<?php

use yii\db\Migration;

/**
 * Clase para la creación de la tabla de reservas
 */
class m240509_reservas extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('reservas', [
            'id_reserva' => $this->primaryKey(),
            'id_usuario' => $this->integer()->notNull(),
            'id_libro' => $this->integer()->notNull(),
            'fecha_reserva' => $this->date()->notNull(),
            'activa' => $this->boolean()->defaultValue(true),
            'notificado' => $this->boolean()->defaultValue(false),
        ]);

        // Agregar claves foráneas
        $this->addForeignKey(
            'fk-reservas-id_usuario',
            'reservas',
            'id_usuario',
            'usuarios',
            'id_usuario',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-reservas-id_libro',
            'reservas',
            'id_libro',
            'libros',
            'id_libro',
            'CASCADE'
        );

        // Agregar índices para mejorar el rendimiento
        $this->createIndex('idx-reservas-id_usuario', 'reservas', 'id_usuario');
        $this->createIndex('idx-reservas-id_libro', 'reservas', 'id_libro');
        $this->createIndex('idx-reservas-activa', 'reservas', 'activa');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('reservas');
    }
}
