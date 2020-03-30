<?php

namespace App\Domain\Security;

use Lcobucci\JWT\Builder;
use DateTimeImmutable;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;
use Exception;

class JWTManager
{
    /** @var string $jwtKey */
    private $jwtKey;

    /** @var string $jwtIssuedBy */
    private $jwtIssuedBy;

    /** @var string $jwtPermittedFor */
    private $jwtPermittedFor;

    public function __construct(
        string $jwtKey,
        string $jwtIssuedBy,
        string $jwtPermittedFor
    ) {
        $this->jwtKey = $jwtKey;
        $this->jwtIssuedBy = $jwtIssuedBy;
        $this->jwtPermittedFor = $jwtPermittedFor;
    }

    /**
     * @param int $exp
     * @param array<string, int|string> $claims
     * @return Token
     *
     * @throws Exception
     */
    public function createToken(int $exp, array $claims = []): Token
    {
        $now   = (new DateTimeImmutable())->getTimestamp();
        $token = (new Builder())
            // Configures the issuer (iss claim)
            ->issuedBy($this->jwtIssuedBy)
            // Configures the audience (aud claim)
            ->permittedFor($this->jwtPermittedFor)
            // Configures the id (jti claim)
            ->identifiedBy(uniqid(), true)
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($now)
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($exp);

            foreach ($claims as $name => $value){
                $token->withClaim($name, $value);
            }
        return $token->getToken(new Sha256(), new key($this->jwtKey));
    }

}