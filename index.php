<?php

include_once('./Imap.php');

$arr = [
  "mail.test.com",
  "imap.test.com",
  "webmail.test.com",
];

new Imap($arr,'contact@test.com', 'xxxxx');





