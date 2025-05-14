#!/bin/bash


WEB_CONTAINER="web_server"
DB_CONTAINER="db"


SOURCE_FILE="docker/init/.superleft.menu_ext.php"
DEST_PATH="/var/www/bitrix/.superleft.menu_ext.php"


if [ ! -f "$SOURCE_FILE" ]; then
  echo "Ошибка: Файл $SOURCE_FILE не найден."
  exit 1
fi


read -p "Выполнить действия с контейнером $WEB_CONTAINER (копирование файла, изменение настроек, установка прав)? [y/N]: " web_confirm
if [[ "$web_confirm" == "y" || "$web_confirm" == "Y" ]]; then
  # 1. Копирование файла в контейнер web_server
  echo "Копирование файла $SOURCE_FILE в $WEB_CONTAINER:$DEST_PATH..."
  docker cp "$SOURCE_FILE" "$WEB_CONTAINER:$DEST_PATH"

  # 2. Включение debug => true в .settings.php
  echo "Включение debug в $WEB_CONTAINER:/var/www/bitrix/bitrix/.settings.php..."
  docker exec "$WEB_CONTAINER" sed -i "s/'debug' => false/'debug' => true/" /var/www/bitrix/bitrix/.settings.php

  # 3. Установка прав 1000:33 на файл
  echo "Установка прав 1000:33 на $WEB_CONTAINER:$DEST_PATH..."
  docker exec "$WEB_CONTAINER" chown 1000:33 "$DEST_PATH"
else
  echo "Действия с контейнером $WEB_CONTAINER пропущены."
fi

read -p "Выполнить подключение к MariaDB в контейнере $DB_CONTAINER? [y/N]: " db_confirm
if [[ "$db_confirm" == "y" || "$db_confirm" == "Y" ]]; then
  # 4. Выполнение команды в контейнере db  echo "Подключение к MariaDB в контейнере $DB_CONTAINER..."
  docker exec -it "$DB_CONTAINER" mariadb -u ulab -D ulab -p'ulab' < /tmp/dump_w_journal.sql
else
  echo "Подключение к MariaDB в контейнере $DB_CONTAINER пропущено."
fi