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
        $aesData = $this->encryptAesPayload($data);
        
        openssl_public_encrypt($aesData['key'], $aesData['key'], $server->getPublicKey());
        openssl_public_encrypt($aesData['iv'], $aesData['iv'], $server->getPublicKey());
        
        $aesData['key'] = base64_encode($aesData['key']);
        $aesData['iv'] = base64_encode($aesData['iv']);
        
        return $aesData;
    }
    
    /**
     * @param string $key
     * @param string $iv
     * @param string $data
     * @return string
     */
    public function decrypt($key, $iv, $data)
    {
        $privateKey = file_get_contents("{$this->projectDir}/rsa_vault/portal_rsa");
        
        $aesData = [];
        
        openssl_private_decrypt(base64_decode($key), $aesData['key'], $privateKey);
        openssl_private_decrypt(base64_decode($iv), $aesData['iv'], $privateKey);
        
        if (($decrypted = openssl_decrypt($data, 'aes-256-cbc', $aesData['key'], OPENSSL_RAW_DATA, $aesData['iv'])) === false) {
            throw new \ErrorException(openssl_error_string());
        }
        return $decrypted;
    }
    
    protected function encryptAesPayload($payload)
    {
        $key = $this->generateAesKey();
        
        if (($cipher = openssl_encrypt($payload, 'aes-256-cbc', $key['key'], OPENSSL_RAW_DATA, $key['iv'])) === false) {
            throw new \ErrorException(openssl_error_string());
        }
        return array_merge($key, [
            'cipher' => $cipher
        ]);
    }
    
    protected function generateAesKey()
    {
        $key = openssl_random_pseudo_bytes(32);
        $iv = openssl_random_pseudo_bytes(16);
        
        return [
            'key' => $key,
            'iv' => $iv
        ];
    }
}