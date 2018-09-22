<?php 

class ListStudents extends ObjectModel
{
	public $id_student; // ID ученика
	
	public $name; // имя ученика
	
	public $date; // дата рождения
	
	public $status = false; // учится или нет
	
	public $score; // средний балл
    
	public static $definition = array(
        'table' => 'students',
        'primary' => 'id_student',
        'multilang' => true,
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 25),
			'date' => array('type' => self::TYPE_DATE),
            'status' => array('type' => self::TYPE_BOOL),
            'score' => array('type' => self::TYPE_INT),
        ),
    );
	
	// список учеников
	public static function getSudents($sql_lang = 1)
    {	
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT *
            FROM `'._DB_PREFIX_.'students` st
            LEFT JOIN `'._DB_PREFIX_.'students_lang` stl ON (st.`id_student` = stl.`id_student`)
            WHERE stl.`id_lang` = '.$id_lang.'
            ORDER BY stl.`name` ASC'
        );

        return $result;
    }
	
	// высший средний бал
	public static function getBestScore() 
	{
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT MAX(score) FROM `'._DB_PREFIX_.'students`');

        return $result;
	}
	
	// лучший ученик по среднему балу
	public static function getBestStudents($sql_lang = 1)
	{
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT *
            FROM `'._DB_PREFIX_.'students` st
            LEFT JOIN `'._DB_PREFIX_.'students_lang` stl ON (st.`id_student` = stl.`id_student`)
            WHERE stl.`id_lang` = '.$id_lang.' AND st.`score` = '.self::getBestScore().'
            ORDER BY stl.`name` ASC'
        );

        return $result;
	}
}

