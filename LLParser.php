<?php

/* 
    Created By: Ayush N  
*/
class LLParser{

    public function parse(ListLexer $lexer) {
    
        $outputQueue = new \SplQueue();
        $operatorStack = new \SplStack();

        $token = $lexer->nextToken();
        print_r($token);
        while($token->type != Lexer::EOF_TYPE) {
            if (ListLexer::$tokenNames[$token->type] == "LPARAN"){
                echo('Found Left Paran');
                //print_r($token);
                $operatorStack->push($token->text);
            }
            elseif (ListLexer::$tokenNames[$token->type] == "SEGMENT")
                $outputQueue->push($token->text);

            else if (ListLexer::$tokenNames[$token->type] == "OPERATOR"){
                /*while(operatorStack.top().precedence >= $token->text.precedence):
                    operator = operatorStack.pop()
                    # Careful! The second operand was pushed last.
                    e2 = exprStack.pop()
                    e1 = exprStack.pop()
                    exprStack.push(ExprNode(operator, e1, e2))*/
                $operatorStack->push($token->text);
            }
            else if (ListLexer::$tokenNames[$token->type] == "RPARAN"){
                while ($operatorStack->top() != '('){
                    $outputQueue->push($operatorStack->pop());
                    # Careful! The second operand was pushed last.
                    # e2 = exprStack.pop()
                    #e1 = exprStack.pop()
                    #exprStack.push(ExprNode(operator, e1, e2))

                }
                # Pop the '(' off the operator stack.
                $operatorStack->pop();
            }
            
        # There should only be one item on exprStack.
        # It's the root node, so we return it.
            $token = $lexer->nextToken();
        //return exprStack.pop();
        }
        if ($token->type == Lexer::EOF_TYPE){
            while (!$operatorStack->isEmpty()){    //there are still operator tokens on the stack:
                /* if the operator token on the top of the stack is a bracket, then there are mismatched parentheses. */
                $outputQueue->push($operatorStack->pop());//pop the operator from the operator stack onto the output queue.
            }        
        }
        //echo('Top of Stack:'); print_r($operatorStack->top());

        print_r($operatorStack);
        print_r($outputQueue);
    
        exit('------');
    }
}

?>