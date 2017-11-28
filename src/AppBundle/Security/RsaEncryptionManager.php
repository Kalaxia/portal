<?php

namespace AppBundle\Security;

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
     * @param string $data
     * @return string
     */
    public function encrypt($data)
    {
        openssl_private_encrypt(
            $data,
            $crypted,
            openssl_get_privatekey(file_get_contents("{$this->projectDir}/rsa_vault/portal_rsa"))
        );
        return $crypted;
    }
}