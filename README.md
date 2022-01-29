IMAP & POP3 connection
-------
Test the different most popular connection protocols, in case of failure the most common errors are managed.

Use the script
-------
Go to where you downloaded the file then run :
```
cd Imap/
php index.php
```

Test the connection manually in the shell
-------
```
php -r "print_r(imap_open('{host:993/imap/ssl/novalidate-cert}', 'user', 'password'));"
```

Credit
-------
Created by <a href="https://github.com/mlcpro">Mlc</a>
