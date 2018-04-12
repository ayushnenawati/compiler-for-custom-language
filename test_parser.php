<?php

require_once('ListLexer.php');
require_once('Token.php');
require_once('ListParser.php');


$input = '[ male_user &&, (active_member || mobile_user) !IN indian_user]';
$lexer = new ListLexer($input);
print_r($lexer);
exit('-------');
$parser = new ListParser($lexer);
$parser->rlist(); // begin parsing at rule list

?>