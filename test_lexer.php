<?php

require_once('ListLexer.php');
require_once('Token.php');
require_once('LLParser.php');

$input = '[ male_user && (active_member || mobile_user) !IN indian_user]';

$lexer = new ListLexer($input);
$token = $lexer->nextToken();

while($token->type != Lexer::EOF_TYPE) {
    echo $token . "\n";
    $token = $lexer->nextToken();
}
$lexer = new ListLexer($input);

$llparser = new LLParser();
$ast = $llparser->parse($lexer);



?>