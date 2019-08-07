<?php
namespace App\Transformers;
use App\Model\Token;
use League\Fractal\TransformerAbstract;

class TokenTransformer extends TransformerAbstract{
    public function transform(Token $token)
    {
        return $token->toArray();
    }
}