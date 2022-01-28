IMAP & POP3 connection
-------
Test the different most popular connection protocols, in case of failure the most common errors are managed.

Test the connection manually in the shell
-------
  php -r "print_r(imap_open('{host:993/imap/ssl/novalidate-cert}', 'email', 'password'));"

Credit
-------
Created by <a href="https://github.com/mlcpro">Mlc</a>