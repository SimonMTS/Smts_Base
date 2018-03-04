<?php
    require_once "models/user.php";
    
    class setupController extends Controller 
    {
        private static $beheerderId;
        private static $klantUsersData;

        private static $superAdminID;
        private static $klantAdminID;

        private static $CMSModuleID;

        private static $CMSPermission;
        private static $googleanalPermission;
        private static $addUserPermission;
        private static $addPermissionPermission;
        private static $addRolPermission;
        private static $moduleGeneratorPermission;
        private static $langEditPermission;

        public static function beforeAction() 
        {
            self::$beheerderId = Smts::Generate_id();
        }

        public static function overview($var)
        {
            base_Smts::Render('setup/overview');
        }

        public static function init($var) 
        { 
            $pw = 'pw';
            $start_time = new DateTime();

            if ( !(isset($var[3]) && $var[3] == $pw) && !(isset($var[3]) && $var[3] == $pw.'confirmed') ) {
                echo json_encode(['msg' => 'error: wrong password<br><br>', 'isDone' => false]);exit;
            }

            if ($var[3] == $pw) {
                // echo json_encode('Are you sure you want to create/reset the database? <br><br>'.
                // ' als dit niet werkt moet de max_execution_time omhoog in php.ini <br><br>'.
                // ' <a href="'.$GLOBALS['config']['base_url'].'smts/setup/init/'.$pw.'confirmed">Yes</a>');
                exit;
            }

            echo json_encode(['msg' => 'Started Database reset <br><br>', 'isDone' => false]);
            ob_flush();flush();
            Smts::$session = [];
            self::setupdb();ob_flush();flush();
            self::addpermissions();ob_flush();flush();
            self::addrols();ob_flush();flush();
            self::addrol_permissions();ob_flush();flush();
            self::addusers();ob_flush();flush();
            self::addmodules();ob_flush();flush();
            self::addWebsites();ob_flush();flush();
            self::addPayments();ob_flush();flush();

            $end_time = new DateTime();
            echo json_encode(['msg' => 
            'Completed database reset. <br><br>Operation took ' . $start_time->diff($end_time)->i . 'min ' . $start_time->diff($end_time)->s . ' sec. <br><br>', 
            'isDone' => true]);
            ob_flush();flush();
            ob_end_flush();
        }

        private static function setupdb() 
        {
            Sql::RemoveDB($GLOBALS['config']['DataBaseName']);

            Sql::CreateDB($GLOBALS['config']['DataBaseName']);

            Sql::CreateTable('module', [
                'id' => 'varchar(256)',
                'price' => 'int(4)',
                'titel' => 'varchar(256)',
                'static_name' => 'varchar(256)',
                'omschr' => 'varchar(1024)',
                'pic' => 'longblob',
                'pic_type' => 'varchar(256)'
            ]);
            Sql::AddPKey('module', 'id');

            Sql::CreateTable('user', [
                'id' => 'varchar(256)',
                'name' => 'varchar(256)',
                'password' => 'varchar(256)',
                'salt' => 'varchar(256)',
                'role' => 'varchar(256)',
                'role_name' => 'varchar(256)',
                'parent' => 'varchar(256)',
                'show_popup' => 'varchar(1)'
            ]);
            Sql::AddPKey('user', 'id');

            Sql::CreateTable('rol', [
                'id' => 'varchar(256)',
                'naam' => 'varchar(256)',
                'static_name' => 'varchar(256)',
                'parent' => 'varchar(256)'
            ]);
            Sql::AddPKey('rol', 'id');

            Sql::CreateTable('permission', [
                'id' => 'varchar(256)',
                'naam' => 'varchar(256)',
                'static_name' => 'varchar(256)',
                'descr' => 'varchar(1024)'
            ]);
            Sql::AddPKey('permission', 'id');

            Sql::CreateTable('rol_permission', [
                'rol_id' => 'varchar(256)',
                'permission_id' => 'varchar(256)'
            ]);

            Sql::CreateTable('payment', [
                'id' => 'varchar(256)',
                'status' => 'varchar(256)',
                'state' => 'varchar(256)',
                'user_id' => 'varchar(256)',
                'module_id' => 'varchar(256)',
                'paid_until' => 'varchar(256)'
            ]);
            Sql::AddPKey('payment', 'id');

            Sql::CreateTable('orders', [
                'id' => 'varchar(256)',
                'user_id' => 'varchar(256)',
                'module_id' => 'varchar(256)',
                'mollie_id' => 'varchar(256)',
                'amount' => 'varchar(256)',
                'date' => 'varchar(256)',
                'paid_from' => 'varchar(256)',
                'paid_until' => 'varchar(256)'
            ]);
            Sql::AddPKey('orders', 'id');

            Sql::CreateTable('cms_website', [
                'id' => 'varchar(256)',
                'rol_id' => 'varchar(256)',
                'url' => 'varchar(256)',
                'template' => 'varchar(256)',
                'pages' => 'varchar(256)',
                'page_links' => 'varchar(256)',
                'db_data' => 'varchar(256)',
            ]);
            Sql::AddPKey('cms_website', 'id');

            echo json_encode(['msg' => 'Done creating database<br><br>', 'isDone' => false]);
        }

        private static function addusers() 
        {
            
            $salt = Smts::Generate_id();
            $user_data = [
                'id' => self::$beheerderId,
                'name' => 'beheerder',
                'password' => Smts::Hash_String('beheerder', $salt),
                'salt' => $salt,
                'role' => self::$superAdminID[0],
                'role_name' => self::$superAdminID[1],
                'parent' => 'false',
                'show_popup' => 'n'
            ];

            $user = new user();
            $user->load($user_data);

            $users[] = $user;
            
            for ($i=1; $i < 128; $i++) {
                $salt = Smts::Generate_id();

                $user_data = [
                    'id' => Smts::Generate_id(),
                    'name' => 'test'.$i,
                    'password' => Smts::Hash_String('test'.$i, $salt),
                    'salt' => $salt,
                    'role' => self::$klantAdminID[$i][0],
                    'role_name' => self::$klantAdminID[$i][1],
                    'parent' => self::$beheerderId,
                    'show_popup' => 'n'
                ];

                $user = new user();
                $user->load($user_data);

                $users[] = $user;
            }
            
            self::$klantUsersData = $users;
            
            foreach ($users as $user) {
                if ( !$user->save() ) {
                    echo json_encode(['msg' => 'error<br><br>', 'isDone' => false]);
                }
            }

            echo json_encode(['msg' => 'Done adding users<br><br>', 'isDone' => false]);
        }

        private static function addpermissions()
        {
            // -- -- -- -- //
                self::$googleanalPermission = Smts::Generate_id();
                $permission_data5 = [
                    'id' => self::$googleanalPermission,
                    'naam' => 'Google Analytics!@#|!@#Google Analytics!@#|!@#Google Analytics!@#|!@#Google Analytics!@#|!@#Google Analytics',
                    'static_name' => 'analytics',
                    'descr' => 'Google Analytics lets you measure your advertising ROI as well as track your Flash, video, and social networking sites and applications!@#|!@#Google Analytics lets you measure your advertising ROI as well as track your Flash, video, and social networking sites and applications!@#|!@#Google Analytics lets you measure your advertising ROI as well as track your Flash, video, and social networking sites and applications!@#|!@#Google Analytics lets you measure your advertising ROI as well as track your Flash, video, and social networking sites and applications!@#|!@#Google Analytics lets you measure your advertising ROI as well as track your Flash, video, and social networking sites and applications'
                ];

                $permission5 = new Permission();
                $permission5->load($permission_data5);

                $permissions[] = $permission5;
            // -- -- -- -- //
                self::$CMSPermission = Smts::Generate_id();
                $permission_data = [
                    'id' => self::$CMSPermission,
                    'naam' => 'CMS NL!@#|!@#CMS DE!@#|!@#CMS EN!@#|!@#CMS FR!@#|!@#CMS ES',
                    'static_name' => 'cms',
                    'descr' => 'Contentmanagement systeem!@#|!@#Inhaltsverwaltungssystem!@#|!@#Content Management System!@#|!@#Système de gestion de contenu!@#|!@#Sistema de gestión de contenidos'
                ];

                $permission = new Permission();
                $permission->load($permission_data);

                $permissions[] = $permission;
            // -- -- -- -- //
                self::$addUserPermission = Smts::Generate_id();
                $permission_data1 = [
                    'id' => self::$addUserPermission,
                    'naam' => 'Gebruikers beheren!@#|!@#Gebruikers beheren!@#|!@#Gebruikers beheren!@#|!@#Gebruikers beheren!@#|!@#Gebruikers beheren',
                    'static_name' => 'usermanagement',
                    'descr' => 'Met deze Permissie mag je gebruikers bekijken, aanmaken, aanpassen, en verwijderen.!@#|!@#Met deze Permissie mag je gebruikers bekijken, aanmaken, aanpassen, en verwijderen.!@#|!@#Met deze Permissie mag je gebruikers bekijken, aanmaken, aanpassen, en verwijderen.!@#|!@#Met deze Permissie mag je gebruikers bekijken, aanmaken, aanpassen, en verwijderen.!@#|!@#Met deze Permissie mag je gebruikers bekijken, aanmaken, aanpassen, en verwijderen.'
                ];

                $permission1 = new Permission();
                $permission1->load($permission_data1);

                $permissions[] = $permission1;
            // -- -- -- -- //
                self::$addPermissionPermission = Smts::Generate_id();
                $permission_data2 = [
                    'id' => self::$addPermissionPermission,
                    'naam' => 'Permissies beheren!@#|!@#Permissies beheren!@#|!@#Permissies beheren!@#|!@#Permissies beheren!@#|!@#Permissies beheren',
                    'static_name' => 'permissionmanagement',
                    'descr' => 'Met deze Permissie mag je permissies bekijken, aanmaken, aanpassen, en verwijderen.!@#|!@#Met deze Permissie mag je permissies bekijken, aanmaken, aanpassen, en verwijderen.!@#|!@#Met deze Permissie mag je permissies bekijken, aanmaken, aanpassen, en verwijderen.!@#|!@#Met deze Permissie mag je permissies bekijken, aanmaken, aanpassen, en verwijderen.!@#|!@#Met deze Permissie mag je permissies bekijken, aanmaken, aanpassen, en verwijderen.'
                ];

                $permission2 = new Permission();
                $permission2->load($permission_data2);

                $permissions[] = $permission2;
            // -- -- -- -- //
                self::$addRolPermission = Smts::Generate_id();
                $permission_data3 = [
                    'id' => self::$addRolPermission,
                    'naam' => 'Rollen beheren!@#|!@#Rollen beheren!@#|!@#Rollen beheren!@#|!@#Rollen beheren!@#|!@#Rollen beheren',
                    'static_name' => 'rolemanagement',
                    'descr' => 'Met deze Permissie mag je rollen bekijken, aanmaken, aanpassen, en verwijderen.!@#|!@#Met deze Permissie mag je rollen bekijken, aanmaken, aanpassen, en verwijderen.!@#|!@#Met deze Permissie mag je rollen bekijken, aanmaken, aanpassen, en verwijderen.!@#|!@#Met deze Permissie mag je rollen bekijken, aanmaken, aanpassen, en verwijderen.!@#|!@#Met deze Permissie mag je rollen bekijken, aanmaken, aanpassen, en verwijderen.'
                ];

                $permission3 = new Permission();
                $permission3->load($permission_data3);

                $permissions[] = $permission3;
            // -- -- -- -- //
                self::$moduleGeneratorPermission = Smts::Generate_id();
                $permission_data4 = [
                    'id' => self::$moduleGeneratorPermission,
                    'naam' => 'Module genereren!@#|!@#Module genereren!@#|!@#Module genereren!@#|!@#Module genereren!@#|!@#Module genereren',
                    'static_name' => 'modulegenerator',
                    'descr' => 'Met deze Permissie mag je de Module generator gebruiken!@#|!@#Met deze Permissie mag je de Module generator gebruiken!@#|!@#Met deze Permissie mag je de Module generator gebruiken!@#|!@#Met deze Permissie mag je de Module generator gebruiken!@#|!@#Met deze Permissie mag je de Module generator gebruiken'
                ];

                $permission4 = new Permission();
                $permission4->load($permission_data4);

                $permissions[] = $permission4;
            // -- -- -- -- //
                self::$langEditPermission = Smts::Generate_id();
                $permission_data7 = [
                    'id' => self::$langEditPermission,
                    'naam' => 'Language edit!@#|!@#Language edit!@#|!@#Language edit!@#|!@#Language edit!@#|!@#Language edit',
                    'static_name' => 'languageedit',
                    'descr' => 'Met deze Permissie mag je de talen van de website veranderen!@#|!@#Met deze Permissie mag je de talen van de website veranderen!@#|!@#Met deze Permissie mag je de talen van de website veranderen!@#|!@#Met deze Permissie mag je de talen van de website veranderen!@#|!@#Met deze Permissie mag je de talen van de website veranderen'
                ];

                $permission7 = new Permission();
                $permission7->load($permission_data7);

                $permissions[] = $permission7;
            // -- -- -- -- //

            foreach ($permissions as $permission) {
                if ( !$permission->save() ) {
                    echo json_encode(['msg' => 'error<br><br>', 'isDone' => false]);
                }
            }

            echo json_encode(['msg' => 'Done adding permissions<br><br>', 'isDone' => false]);
        }

        private static function addrols()
        {
            self::$superAdminID[0] = Smts::Generate_id();
            self::$superAdminID[1] = 'Super admin';
            $rol_data = [
                'id' => self::$superAdminID[0],
                'naam' => self::$superAdminID[1],
                'static_name' => 'superadmin',
                'parent' => 'false'
            ];

            $rol = new Rol();
            $rol->load($rol_data);

            $rols[] = $rol;

            for ($i=1; $i < 128; $i++) {
                self::$klantAdminID[$i][0] = Smts::Generate_id();
                self::$klantAdminID[$i][1] = 'Admin bedrijf_'.$i;
                $rol_data1 = [
                    'id' => self::$klantAdminID[$i][0],
                    'naam' => self::$klantAdminID[$i][1],
                    'static_name' => 'admin_bedrijf_'.$i,
                    'parent' => self::$superAdminID[0]
                ];

                $rol1 = new Rol();
                $rol1->load($rol_data1);

                $rols[] = $rol1;
            }
            
            foreach ($rols as $rol) {
                if ( !$rol->save() ) {
                    echo json_encode(['msg' => 'error<br><br>', 'isDone' => false]);
                }
            }

            echo json_encode(['msg' => 'Done adding rols<br><br>', 'isDone' => false]);
        }

        private static function addrol_permissions()
        {
            // -- -- -- -- //
                $rol_permission_data = [
                    'rol_id' => self::$superAdminID[0],
                    'permission_id' => self::$addUserPermission
                ];

                $rol_permission = new Rol_permission();
                $rol_permission->load($rol_permission_data);

                $rol_permissions[] = $rol_permission;
            // -- -- -- -- //
                $rol_permission_data10 = [
                    'rol_id' => self::$superAdminID[0],
                    'permission_id' => self::$googleanalPermission
                ];

                $rol_permission10 = new Rol_permission();
                $rol_permission10->load($rol_permission_data10);

                $rol_permissions[] = $rol_permission10;
            // -- -- -- -- //
                $rol_permission_data1 = [
                    'rol_id' => self::$superAdminID[0],
                    'permission_id' => self::$CMSPermission
                ];

                $rol_permission1 = new Rol_permission();
                $rol_permission1->load($rol_permission_data1);

                $rol_permissions[] = $rol_permission1;
            // -- -- -- -- //
                $rol_permission_data3 = [
                    'rol_id' => self::$superAdminID[0],
                    'permission_id' => self::$addPermissionPermission
                ];

                $rol_permission3 = new Rol_permission();
                $rol_permission3->load($rol_permission_data3);

                $rol_permissions[] = $rol_permission3;
            // -- -- -- -- //
                $rol_permission_data4 = [
                    'rol_id' => self::$superAdminID[0],
                    'permission_id' => self::$addRolPermission
                ];

                $rol_permission4 = new Rol_permission();
                $rol_permission4->load($rol_permission_data4);

                $rol_permissions[] = $rol_permission4;
            // -- -- -- -- //
                $rol_permission_data6 = [
                    'rol_id' => self::$superAdminID[0],
                    'permission_id' => self::$moduleGeneratorPermission
                ];

                $rol_permission6 = new Rol_permission();
                $rol_permission6->load($rol_permission_data6);

                $rol_permissions[] = $rol_permission6;
            // -- -- -- -- //
                $rol_permission_data15 = [
                    'rol_id' => self::$superAdminID[0],
                    'permission_id' => self::$langEditPermission
                ];

                $rol_permission15 = new Rol_permission();
                $rol_permission15->load($rol_permission_data15);

                $rol_permissions[] = $rol_permission15;
            // -- -- -- -- //
            // -- -- -- -- //
                for ($i=1; $i < 128; $i++) {
                    $rol_permission_data2 = [
                        'rol_id' => self::$klantAdminID[$i][0],
                        'permission_id' => self::$addRolPermission
                    ];

                    $rol_permission2 = new Rol_permission();
                    $rol_permission2->load($rol_permission_data2);

                    $rol_permissions[] = $rol_permission2;
                // -- -- -- -- //
                    $rol_permission_data5 = [
                        'rol_id' => self::$klantAdminID[$i][0],
                        'permission_id' => self::$addUserPermission
                    ];

                    $rol_permission5 = new Rol_permission();
                    $rol_permission5->load($rol_permission_data5);

                    $rol_permissions[] = $rol_permission5;
                // -- -- -- -- //
                    // $rol_permission_data5 = [
                    //     'rol_id' => self::$klantAdminID[$i][0],
                    //     'permission_id' => self::$CMSPermission
                    // ];

                    // $rol_permission5 = new Rol_permission();
                    // $rol_permission5->load($rol_permission_data5);

                    // $rol_permissions[] = $rol_permission5;
                    // -- -- -- -- //
                }
            // -- -- -- -- //

            foreach ($rol_permissions as $rol_permission) {
                if ( !$rol_permission->save() ) {
                    echo json_encode(['msg' => 'error<br><br>', 'isDone' => false]);
                }
            }

            echo json_encode(['msg' => 'Done adding rol_permissions<br><br>', 'isDone' => false]);
        }

        private static function addmodules()
        {
            self::$CMSModuleID = Smts::Generate_id();
            Sql::Save('module', [
                'id' => self::$CMSModuleID,
                'price' => 12,
                'titel' => 'CMS NL!@#|!@#CMS DE!@#|!@#CMS EN!@#|!@#CMS FR!@#|!@#CMS ES',
                'static_name' => 'cms',
                'omschr' => 'Contentmanagement systeem!@#|!@#Inhaltsverwaltungssystem!@#|!@#Content Management System!@#|!@#Système de gestion de contenu!@#|!@#Sistema de gestión de contenidos',
                'pic' => '',
                'pic_type' => ''
            ]);

            Sql::Save('module', [
                'id' => Smts::Generate_id(),
                'price' => 8,
                'titel' => 'Google Analytics!@#|!@#Google Analytics!@#|!@#Google Analytics!@#|!@#Google Analytics!@#|!@#Google Analytics',
                'static_name' => 'analytics',
                'omschr' => 'Google Analytics lets you measure your advertising ROI as well as track your Flash, and video!@#|!@#Google Analytics lets you measure your advertising ROI as well as track your Flash, and video!@#|!@#Google Analytics lets you measure your advertising ROI as well as track your Flash, and video!@#|!@#Google Analytics lets you measure your advertising ROI as well as track your Flash, and video!@#|!@#Google Analytics lets you measure your advertising ROI as well as track your Flash, and video',
                'pic' => '',
                'pic_type' => ''
            ]);

            echo json_encode(['msg' => 'Done adding modules<br><br>', 'isDone' => false]);
        }

        private static function addPayments()
        {
            for ($i=-64; $i < 64; $i++) {

                $payment = new payment();
                $payment->id = 'tr_fakemollieid'.$i;
                $payment->status = 'paid';
                $payment->state = 'enabled';
                $payment->user_id = self::$klantUsersData[$i+64]->id;
                $payment->module_id = self::$CMSModuleID;
                $payment->paid_until = strtotime('+'.$i.' days');

                // $payment->save();

            }

            // echo json_encode(['msg' => 'Done adding payments<br><br>', 'isDone' => false]);
        }

        private static function addWebsites()
        {
            Sql::Save('cms_website', [
                'id' => Smts::Generate_id(),
                'rol_id' => self::$superAdminID[0],
                'url' => 'http://localhost/topdowntestsite/',
                'template' => 'nummer3',
                'pages' => 'Home;Over;Diensten;Portfolio;Contact;Portfolio item;Blogpost',
                'page_links' => 'index.php;about.php;service.php;portfolio.php;contact.php;portfolio-item.php;blogpost.php',
                'db_data' => implode( '|||', [
                    'mysql:host=localhost;dbname=topdown_testsite',
                    'root', 
                    ''
                ])
            ]);

            echo json_encode(['msg' => 'Done adding websites<br><br>', 'isDone' => false]);
        }
    }