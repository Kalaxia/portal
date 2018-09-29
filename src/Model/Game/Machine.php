<?php

namespace App\Model\Game;

abstract class Machine implements \JsonSerializable
{
    /** @var int **/
    protected $id;
    /** @var string **/
    protected $name;
    /** @var string **/
    protected $slug;
    /** @var string **/
    protected $host;
    /** @var string **/
    protected $publicKey;
    /** @var boolean **/
    protected $isLocal;
    
    public function setId(int $id): Machine
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function setName(string $name): Machine
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setSlug(string $slug): Machine
    {
        $this->slug = $slug;
        
        return $this;
    }
    
    public function getSlug(): string
    {
        return $this->slug;
    }
    
    public function setHost(string $host): Machine
    {
        $this->host = $host;
        
        return $this;
    }
    
    public function getHost(): string
    {
        return $this->host;
    }
    
    public function setPublicKey(string $publicKey): Machine
    {
        $this->publicKey = $publicKey;
        
        return $this;
    }
    
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
    
    public function setIsLocal(bool $isLocal): Machine
    {
        $this->isLocal = $isLocal;
        
        return $this;
    }
    
    public function getIsLocal(): bool
    {
        return $this->isLocal;
    }
    
    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'host' => $this->host,
            'public_key' => $this->publicKey,
            'is_local' => $this->isLocal
        ];
    }
}