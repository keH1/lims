FROM mariadb:11.5.2

COPY docker/mysql/my.cnf /etc/mysql/conf.d/my.cnf
COPY docker/mysql/dump_w_journal.sql /tmp/dump_w_journal.sql

CMD ["mariadbd"]

EXPOSE 3306