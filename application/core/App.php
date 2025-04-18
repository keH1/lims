<?php

/**
 * Ядро - application
 * Class App
 */
class App
{
    protected $controller = "IndexController";
    protected $method = "index";
    protected $id = '';

    public function __construct()
    {
        $url = $this->parseUrl();

        $controllerName = ucfirst($url[0]);
        if (!empty($url[0]) && file_exists(
                APP_PATH . "modules/{$controllerName}/Controller/" . ucfirst($url[0]) . 'Controller.php',
            )) {
            $controller = $controllerName . 'Controller';
            $this->controller = $controller;
            unset($url[0]);
        }

        $this->controller = new $this->controller;

        if (isset($url[1])) {
            $method = $url[1];
            if (method_exists($this->controller, $method)) {
                $this->method = $method;
                unset($url[1]);
            }
        }

        if (isset($url[2]) && !empty($url[2])) {
            $tmp = explode('?', $url[2]);
            $this->id = $tmp[0] ?? '';
        }

        // костыль для extranet


        // если нет доступа
        $homePage = '';
//        if ( !$this->checkPermission($_SESSION['SESS_AUTH']['USER_ID'], $controllerName, $this->method, $homePage) ) {
//            $_SESSION['message_danger'] = "Недостаточно прав для просмотра или действия";
//            //TODO: перенаправление
//            if (isset($_SESSION['last_uri'])) {
//                $lastUri = $_SESSION['last_uri'];
//                unset($_SESSION['last_uri']);
//
////                header("Location: {$lastUri}");
//                header("Location: " . URI . $homePage);
//            } else {
//                header("Location: " . URI . $homePage);
//            }
//        } else {
//            // сохраняем текущую страницу
//            $_SESSION['last_uri'] = $_SERVER['REQUEST_URI'];
//
//            call_user_func([$this->controller, $this->method], $this->id);
//        }
        call_user_func([$this->controller, $this->method], $this->id);
    }

    protected function parseUrl()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = str_replace(URI, '', $_SERVER['REQUEST_URI']);

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($url, 'login') !== false) {
                $_SESSION['last_auth_post'] = true;
                $_SESSION['last_auth_post_time'] = time();
            }

            // Проверка, если нажали "Назад" после авторизации
            if ($_SERVER['REQUEST_METHOD'] === 'GET' &&
                isset($_SESSION['last_auth_post']) &&
                $_SESSION['last_auth_post'] === true &&
                (time() - $_SESSION['last_auth_post_time']) < 3600 &&
                strpos($url, 'login') !== false &&
                !isset($_GET['refresh'])) {
                $urlParts = parse_url($_SERVER['REQUEST_URI']);
                $query = isset($urlParts['query']) ? $urlParts['query'] : '';
                parse_str($query, $params);
                $params['refresh'] = '1';

                $newUrl = $urlParts['path'];
                if (!empty($params)) {
                    $newUrl .= '?' . http_build_query($params);
                }

                echo
                    '<!DOCTYPE html>
                            <html>
                            <head>
                                <meta charset="UTF-8">
                                <title>Перенаправление...</title>
                                <script>
                                window.onload = function() {
                                    // Используем History API для замены текущей записи истории
                                    if (window.history && window.history.replaceState) {
                                        // Заменяем текущую запись в истории браузера
                                        history.replaceState(null, "", "' . $newUrl . '");
                                        // Перезагружаем страницу для получения свежего контента
                                        location.reload();
                                    } else {
                                        // Для браузеров без поддержки History API
                                        location.href = "' . $newUrl . '";
                                    }
                                };
                                </script>
                            </head>
                            <body>
                                <p>Перенаправление...</p>
                            </body>
                        </html>';
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'GET' &&
                strpos($url, 'login') === false &&
                isset($_SESSION['last_auth_post'])) {
                unset($_SESSION['last_auth_post']);
                unset($_SESSION['last_auth_post_time']);
            }

            if (strpos($url, 'login=yes') !== false) {
                global $USER;
                $isAuthorized = false;

                if (isset($USER) && method_exists($USER, 'IsAuthorized') && $USER->IsAuthorized()) {
                    $isAuthorized = true;
                } elseif (isset($_SESSION['SESS_AUTH']) && !empty($_SESSION['SESS_AUTH']['USER_ID'])) {
                    $isAuthorized = true;
                }

                if ($isAuthorized) {
                    $urlParts = parse_url($_SERVER['REQUEST_URI']);
                    if (isset($urlParts['query'])) {
                        parse_str($urlParts['query'], $params);
                        unset($params['login']);
                        $urlParts['query'] = http_build_query($params);
                    }

                    $newUrl = $urlParts['path'];
                    if (!empty($urlParts['query'])) {
                        $newUrl .= '?' . $urlParts['query'];
                    }
                    if (isset($urlParts['fragment'])) {
                        $newUrl .= '#' . $urlParts['fragment'];
                    }

                    header("Location: {$newUrl}", true, 302);
                    exit;
                }
            }

            $uri = explode('?', $url);
            $uri = explode('/', filter_var(rtrim($uri[0], '/'), FILTER_SANITIZE_URL));
            array_shift($uri);
            return $uri;
        }

        return [];

        // if (isset($_SERVER['REQUEST_URI'])) {
        //     $url = str_replace(URI, '', $_SERVER['REQUEST_URI']);
        //     $uri = explode('?', $url);
        //     $uri = explode('/', filter_var(rtrim($uri[0], '/'), FILTER_SANITIZE_URL));
        //     array_shift($uri);
        //     return $uri;
        // }

        // return [];
    }

    /**
     * @return CUser|Exception
     * @throws Exception
     * Инициализия объекта пользователя Bitrix
     */
    protected static function bitrixUser(): \CUser|\Exception
    {
        global $USER;
        if (!$USER instanceof \CUser) {
            throw new \Exception("Класс CUser должен быть инициализирован");
        }
        return $USER;
    }

    /**
     * @return int
     * @throws Exception
     * ID пользователя Bitrix
     */
    public static function getUserId(): int
    {
        return self::bitrixUser()->GetID();
    }

    /**
     * @return bool
     * @throws Exception
     * Проверка авторизации пользователя
     */
    public static function isAuthorized(): bool
    {
        return self::bitrixUser()->isAuthorized();
    }

    /**
     * @return bool
     * @throws Exception
     * Проверка на наличие в группе администраторов Bitrix
     */
    public static function isAdmin(): bool
    {
        return self::bitrixUser()->isAdmin();
    }

    /**
     * @return int
     * @throws Exception
     * Получение ID организации
     */
    public static function getOrganizationId(): int
    {
        static $organizationId = 0;
        if ($organizationId > 0) {
            return $organizationId;
        }

        $userId = self::bitrixUser()->GetID();
        $by = "ID";
        $order = "DESC";
        $arFilter = ["ID" => $userId];
        $arParams["SELECT"] = ["UF_ORG_ID"];
        $arRes = CUser::GetList($by, $order, $arFilter, $arParams);
        if ($res = $arRes->Fetch()) {
            $organizationId = (int)$res["UF_ORG_ID"];
        }

        return $organizationId;
    }

    /**
     * @return array
     * @throws Exception
     * Массив ID груп пользователей
     */
    public static function getUserGroupIds(): array
    {
        return self::bitrixUser()->GetUserGroupArray();
    }

    /**
     * @param $userId - ид текущего пользователя
     * @param $controller - название контроллера
     * @param $method - название метода
     * @param $homePage
     * @return bool
     */
    protected function checkPermission($userId, $controller, $method, &$homePage)
    {
        global $DB;

        $row = $DB->Query(
            "SELECT p.* 
                    FROM `ulab_permission` as p  
                    LEFT JOIN `ulab_user_permission` as u ON p.id = u.permission_id
                    WHERE u.user_id = {$userId} OR p.id = 1",
        )->Fetch();

        $row['permission'] = json_decode($row['permission'], true);
        $homePage = $row['home_page'];


        if (self::isAdmin() || $row['permission'] == 'all') {
            return true;
        }

        $_SESSION['SESS_AUTH']['ROLE'] = $row['id'];

//		return isset($row['permission'][$controller][$method]);
        return true;
    }
}
