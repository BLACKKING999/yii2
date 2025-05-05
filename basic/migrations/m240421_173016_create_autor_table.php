<?php

use yii\db\Migration;

/**
 * Class m240421_173016_create_autor_table
 */
class m240421_173016_create_autor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('autor', [
            'id_autor' => $this->primaryKey(),
            'nombre' => $this->string(255)->notNull(),
            'apellido' => $this->string(255)->notNull(),
            'biografia' => $this->text()->notNull(),
            'fecha_nacimiento' => $this->date()->notNull(),
            'nacionalidad' => $this->string(255)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('autor');
    }
} 