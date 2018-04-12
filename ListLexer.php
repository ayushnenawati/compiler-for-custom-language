<?php

require_once('lexer.php');
/* 
    Created By: Ayush N  
    This class checks for the syntax of tokens and list their types
*/
class ListLexer extends Lexer {
    const SEGMENT   = 2;
    const OPERATOR  = 3;
    const LPARAN    = 4;
    const RPARAN    = 5;
    const LBRACK    = 6;
    const RBRACK    = 7;
    static $tokenNames = array("n/a", "<EOF>",
                               "SEGMENT", "OPERATOR",
                               "LPARAN", "RPARAN", "LBRACK", "RBRACK" );
    private $operator="";
    
    public function getTokenName($x) {
        return ListLexer::$tokenNames[$x];
    }

    public function ListLexer($input) {
        parent::__construct($input);
    }
    
    public function isLETTER() {
        return $this->c >= 'a' &&
               $this->c <= 'z' ||
               $this->c >= 'A' &&
               $this->c <= 'Z' ||
               $this->c >= '0' &&
               $this->c <= '9' ||
               $this->c == '_';
    }

    public function isOPERATOR() {
        $buf = '';
        if($this->c == '&'){
            do {
                $buf .= $this->c;
                $this->consume();
            } while ($this->c == '&');
        }
        elseif ($this->c == '|') {
            do {
                $buf .= $this->c;
                $this->consume();
            } while ($this->c == '|');
        }
        elseif ($this->c == '!') {
            for ($i=0; $i <=2 ; $i++) { 
                $buf .= $this->c;
                $this->consume();
            }
        }
        $this->operator = $buf;
        if($buf=='&&' || $buf=='||' || $buf=='!IN')
            return true;
    }

    public function nextToken() {
        while ( $this->c != self::EOF ) {
            switch ( $this->c ) {
                case ' ' :  case '\t': case '\n': case '\r': $this->WS();
                           continue;
                case '(' : $this->consume();
                           return new Token(self::LPARAN, "(");
                case ')' : $this->consume();
                           return new Token(self::RPARAN, ")");
                case '[' : $this->consume();
                           return new Token(self::LBRACK, "[");
                case ']' : $this->consume();
                           return new Token(self::RBRACK, "]");
                default:
                    if ($this->isLETTER() ) return $this->SEGMENT();
                    else if ($this->isOPERATOR() ) return $this->OPERATOR();
                    throw new Exception("invalid character: " . $this->c);
            }
        }
        return new Token(self::EOF_TYPE,"<EOF>");
    }

    /** SEGMENT : ('a'..'z'|'A'..'Z'|'0'..'9'|'_')+; */
    public function SEGMENT() {
        $buf = '';
        do {
            $buf .= $this->c;
            $this->consume();
        } while ($this->isLETTER());
        return new Token(self::SEGMENT, $buf);
    }

    public function OPERATOR() {
        return new Token(self::OPERATOR, $this->operator);
    }

    /** WS : (' '|'\t'|'\n'|'\r')* ; // ignore any whitespace */
    public function WS() {
        while(ctype_space($this->c)) {
            $this->consume();
        }
    }
}

?>