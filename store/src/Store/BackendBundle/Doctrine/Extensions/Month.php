<?php

namespace Store\BackendBundle\Doctrine\Extensions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;



/**
 * Classe qui ajoute la fonction MONTH() en DQL
 * Class Month
 * @package Store\BackendBundle\Doctrine\Extensions
 */
class Month extends FunctionNode {


    /**
     * @var attribut dateExpression
     */
    protected $dateExpression;



    /**
     * Parse qui va utiliser le Parser de Doctrine pour parser ma fonction MONTH() en DQL
     * @param \Doctrine\ORM\Query\Parser $parser
     *
     * @return void
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        // Identifie la fonction que l'on va utiliser, le nom de ma fonction : MONTH
        $parser->match(Lexer::T_IDENTIFIER);

        // Identifie la parenthèse ouvrante : (
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        // Identifie l'expression que l'on va donner dans la fonction MONTH()
        $this->dateExpression = $parser->ArithmeticExpression();

        // Identifie la parenthèse fermante : )
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }



    /**
     * Parse le DQL en SQL en utilisant SqlWalker
     * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
     *
     * @return string
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        // Je retourne la fonction en SQL MONTH() qui va prendre en argument ce que j'ai saisi en expression
        return 'MONTH(' .
        $sqlWalker->walkArithmeticExpression($this->dateExpression) .
        ')';
    }

}