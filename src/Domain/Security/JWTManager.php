<?php

namespace App\Domain\Security;

use App\Domain\Exceptions\JWTException;
use Lcobucci\JWT\Builder;
use DateTimeImmutable;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;
use Exception;
use Lcobucci\JWT\ValidationData;
use Symfony\Component\HttpFoundation\Response;

class JWTManager
{
    /** @var string $jwtKey */
    private $jwtKey;

    /** @var string $jwtIssuedBy */
    private $jwtIssuedBy;

    /** @var string $jwtPermittedFor */
    private $jwtPermittedFor;

    /** @var Sha256 $encoder */
    private $encoder;

    public function __construct(
        string $jwtKey,
        string $jwtIssuedBy,
        string $jwtPermittedFor
    ) {
        $this->jwtKey = $jwtKey;
        $this->jwtIssuedBy = $jwtIssuedBy;
        $this->jwtPermittedFor = $jwtPermittedFor;
        $this->encoder = new Sha256();
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

            foreach ($claims as $name => $value) {
                $token->withClaim($name, $value);
            }
        return $token->getToken($this->encoder, new key($this->jwtKey));
    }

    /**
     * @param array $auth
     * @return Token
     *
     * @throws JWTException
     */
    public function getToken(array $auth): Token
    {
        try {
            /** @var Token $token */
            $token = (new Parser())->parse((string) $auth[1]);
            $this->isValid($token);
        } catch (Exception $exception) {
            throw new JWTException(
                'Invalid token',
                Response::HTTP_UNAUTHORIZED
            );
        }

        if ($token->isExpired()) {
            throw new JWTException(
                'Expired token',
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $token;
    }

    /**
     * Valid data and passphrase of the token
     *
     * @param Token $token
     * @return bool
     */
    public function isValid(Token $token): bool
    {
        $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
        $data->setIssuer($this->jwtIssuedBy);
        $data->setAudience($this->jwtPermittedFor);
        $data->setId($token->getHeader('jti'));

        return ($token->validate($data) && $token->verify($this->encoder, $this->jwtKey));
    }
}