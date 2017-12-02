<?php

namespace AppBundle\Security;

use AppBundle\Entity\Game\Server;

class RsaEncryptionManager
{
    /** @var string **/
    protected $projectDir;
    
    /**
     * @param string $projectDir
     */
    public function __construct($projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @param Server $server
     * @param string $data
     * @return string
     */
    public function encrypt(Server $server, $data)
    {
        openssl_public_encrypt($data, $crypted, $server->getPublicKey());
        return $crypted;
    }
    
    /**
     * @param string $data
     * @return string
     */
    public function decrypt($data)
    {
        openssl_private_decrypt($data, $decrypted, file_get_contents("{$this->projectDir}/rsa_vault/portal_rsa"));
        return $decrypted;
    }
}