FROM mysql:8.0

COPY docker/mysql/my.cnf /etc/mysql/conf.d/my.cnf
COPY docker/mysql/dump_w_journal.sql /tmp/dump_w_journal.sql

CMD ["mysqld"]

EXPOSE 3306