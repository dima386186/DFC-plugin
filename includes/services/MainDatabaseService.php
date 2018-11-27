<?php

class MainDatabaseService {

    public static $table_main_settings;
    public static $table_fields;
    public static $table_fields_data;

    public static function get_wpdb() {
        global $wpdb;
        return $wpdb;
    }

    public static function properties_init($wpdb) {
        self::$table_main_settings = $wpdb->prefix . UNIQUE_DB_PREFIX . 'main_settings';
        self::$table_fields = $wpdb->prefix . UNIQUE_DB_PREFIX . 'fields';
        self::$table_fields_data = $wpdb->prefix . UNIQUE_DB_PREFIX . 'fields_data';
    }

    public static function plugin_activate() {
        self::properties_init(self::get_wpdb());
        self::activate_sql(self::get_wpdb());
    }

    public static function plugin_deactivate() {
        self::properties_init(self::get_wpdb());
        self::deactivate_sql(self::get_wpdb());
    }


    public static function activate_sql($wpdb) {
        $charset_collate = $wpdb->get_charset_collate();
        $admin_email = get_option('admin_email');

        $sql = "CREATE TABLE ".self::$table_main_settings." (
                    id SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    set_where VARCHAR (60),
                    set_from VARCHAR (60),
                    subject VARCHAR (150) NOT NULL,
                    message TEXT NOT NULL 
                ) {$charset_collate};

                INSERT INTO ".self::$table_main_settings." (
                    set_where, set_from, subject, message ) VALUES ( 
                    '".$admin_email."', '".$admin_email."', 'WP Subject', 'WP Message'
                );

                CREATE TABLE ".self::$table_fields." (
                    id SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    type VARCHAR (30) NOT NULL,
                    label BOOL,
                    label_text VARCHAR (50),
                    wrap_classes VARCHAR (255),
                    place SMALLINT,
                    value VARCHAR (50),
                    name VARCHAR (50) UNIQUE NOT NULL,
                    placeholder VARCHAR (50),
                    required BOOL
                ) {$charset_collate};
                
                INSERT INTO ".self::$table_fields." (
                    type, place, name, placeholder, required, label, label_text, wrap_classes ) VALUES ( 
                    'text', '1', 'name', 'name', '1', '1', 'Name', 'form-group' 
                );
                
                INSERT INTO ".self::$table_fields." (
                    type, place, name, placeholder, required, label, label_text, wrap_classes ) VALUES ( 
                    'email', '2', 'email', 'email', '1', '1', 'Email', 'form-group'
                );
                

                CREATE TABLE ".self::$table_fields_data." (
                    id SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    time DATETIME DEFAULT CURRENT_TIMESTAMP,
                    name VARCHAR (50),
                    email VARCHAR (50),
                    message TEXT,
                    file_1 VARCHAR (50)
                ) {$charset_collate};";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    public static function deactivate_sql($wpdb) {
    	$sql = [];
        $sql[] =  "DROP TABLE " . self::$table_main_settings;
	    $sql[] =  "DROP TABLE " . self::$table_fields;
	    $sql[] =  "DROP TABLE " . self::$table_fields_data;

        for($i=0; $i<count($sql); $i++){
            $wpdb->query($sql[$i]);
        }
    }
}
